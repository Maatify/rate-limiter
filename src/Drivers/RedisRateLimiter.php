<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:43
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Drivers;

use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Config\RateLimitConfig;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Redis;

/**
 * ⚙️ Class RedisRateLimiter
 *
 * 🧩 Purpose:
 * Provides a high-performance Redis-based implementation of
 * {@see RateLimiterInterface} for tracking and enforcing request limits
 * with atomic operations and millisecond precision.
 *
 * ✅ Features:
 * - Atomic counter increments (`INCR`, `EXPIRE`) for thread-safe limits.
 * - Automatic expiration after each interval.
 * - Exponential backoff handling for aggressive clients.
 * - Fast, scalable, and distributed by design.
 *
 * ⚙️ Example Usage:
 * ```php
 * use Maatify\RateLimiter\Drivers\RedisRateLimiter;
 * use Maatify\RateLimiter\Enums\RateLimitActionEnum;
 * use Maatify\RateLimiter\Enums\PlatformEnum;
 *
 * $redis = new Redis();
 * $redis->connect('127.0.0.1');
 *
 * $limiter = new RedisRateLimiter($redis);
 * $status = $limiter->attempt('192.168.1.10', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
 *
 * echo json_encode($status->toArray(), JSON_PRETTY_PRINT);
 * ```
 *
 * @package Maatify\RateLimiter\Drivers
 */
final class RedisRateLimiter implements RateLimiterInterface
{
    /**
     * 🧠 Constructor
     *
     * Initializes a Redis-based rate limiter using an active Redis connection.
     *
     * @param Redis $redis A connected Redis client instance.
     */
    public function __construct(private readonly Redis $redis)
    {
    }

    /**
     * 🔹 Build a Redis key for a specific key/action/platform combination.
     *
     * @param string $key Unique identifier (IP, user ID, token, etc.).
     * @param RateLimitActionInterface $action Rate-limited action.
     * @param PlatformInterface $platform Platform context (web, api, etc.).
     *
     * @return string Fully-qualified Redis key name.
     *
     * ✅ Example:
     * ```php
     * $key = $this->key('user123', $action, $platform);
     * // rate:web:login:user123
     * ```
     */
    private function key(string $key, RateLimitActionInterface $action, PlatformInterface $platform): string
    {
        return sprintf('rate:%s:%s:%s', $platform->value(), $action->value(), $key);
    }

    /**
     * 🎯 Attempt an action under rate-limit constraints.
     *
     * Increments the counter for the specified key/action/platform.
     * Throws {@see TooManyRequestsException} if the configured limit is exceeded.
     * Can also apply exponential backoff when requests exceed hard thresholds.
     *
     * @param string $key Unique identifier (IP, user ID, etc.).
     * @param RateLimitActionInterface $action The logical action being rate-limited.
     * @param PlatformInterface $platform The platform context.
     *
     * @return RateLimitStatusDTO Current rate-limit state after increment.
     *
     * @throws TooManyRequestsException When the configured limit is exceeded.
     */
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 🔹 Retrieve rate-limit configuration
        $config = RateLimitConfig::get($action->value());
        $docKey = $this->key($key, $action, $platform);
        $limit = $config['limit'];
        $interval = $config['interval'];

        // ⚙️ Atomically increment the request counter
        $current = (int) $this->redis->incr($docKey);

        // 🕒 Apply TTL only on first increment
        if ($current === 1) {
            $this->redis->expire($docKey, $interval);
        }

        // 📊 Calculate remaining requests and time-to-reset
        $ttl = (int) $this->redis->ttl($docKey);
        $remaining = max(0, $limit - $current);

        // 🚫 Exceeded limit → apply exponential backoff logic
        if ($current > $limit) {
            // Generate a dynamic backoff DTO (for observability)
            $backoffStatus = $this->applyBackoff($docKey, $current - $limit);
            throw new TooManyRequestsException(
                sprintf(
                    'Rate limit exceeded. Retry after %d seconds (next allowed at %s).',
                    $backoffStatus->backoffSeconds,
                    $backoffStatus->nextAllowedAt
                ),
                429
            );
        }

        // ✅ Return structured DTO
        return new RateLimitStatusDTO(
            limit: $limit,
            remaining: $remaining,
            resetAfter: $ttl,
            blocked: $remaining <= 0
        );
    }

    /**
     * ♻️ Reset the rate-limit record for a given key/action/platform.
     *
     * Deletes the Redis key, effectively resetting the rate-limit counter.
     *
     * @param string $key Unique identifier (IP, user ID, token, etc.).
     * @param RateLimitActionInterface $action The rate-limited action.
     * @param PlatformInterface $platform The platform context.
     *
     * @return bool True if the Redis key was deleted successfully.
     */
    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        return (bool) $this->redis->del($this->key($key, $action, $platform));
    }

    /**
     * 🔍 Retrieve rate-limit status without incrementing counters.
     *
     * Reads the current counter and TTL from Redis without altering the state.
     * Useful for dashboards, monitoring, or diagnostics.
     *
     * @param string $key Unique identifier (IP, user ID, token, etc.).
     * @param RateLimitActionInterface $action The rate-limited action.
     * @param PlatformInterface $platform The platform context.
     *
     * @return RateLimitStatusDTO Snapshot of the current rate-limit state.
     */
    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        $config = RateLimitConfig::get($action->value());
        $docKey = $this->key($key, $action, $platform);

        $value = $this->redis->get($docKey);
        // Cast to string first to handle possible mixed type from stub, then int.
        // Redis::get returns string|false. (int)false is 0. (int)string is parsed.
        $count = (int) ((string) $value);

        $ttl = (int) $this->redis->ttl($docKey);
        $remaining = max(0, $config['limit'] - $count);

        return new RateLimitStatusDTO(
            limit: $config['limit'],
            remaining: $remaining,
            resetAfter: $ttl > 0 ? $ttl : $config['interval']
        );
    }

    /**
     * 🧮 Calculate an exponential backoff delay.
     *
     * 🎯 Formula:
     * ```
     * delay = min(base^attempts, max)
     * ```
     *
     * @param int $attempts Number of excessive attempts beyond the limit.
     * @param int $base Exponential growth factor (default: 2).
     * @param int $max Maximum backoff time in seconds (default: 3600).
     *
     * @return int Calculated backoff duration in seconds.
     *
     * ✅ Example:
     * ```php
     * $delay = $this->calculateBackoff(3); // 8 seconds
     * ```
     */
    private function calculateBackoff(int $attempts, int $base = 2, int $max = 3600): int
    {
        return min(pow($base, $attempts), $max);
    }

    /**
     * 🕒 Apply exponential backoff logic after exceeding the limit.
     *
     * Resets the Redis TTL to enforce a cooldown period, then returns
     * a DTO describing when the next allowed attempt will be permitted.
     *
     * @param string $key Redis key being throttled.
     * @param int $attempts Number of attempts beyond the limit.
     *
     * @return RateLimitStatusDTO DTO containing backoff information.
     */
    private function applyBackoff(string $key, int $attempts): RateLimitStatusDTO
    {
        $backoff = $this->calculateBackoff($attempts);
        $this->redis->expire($key, $backoff);

        $nextAllowed = (new \DateTimeImmutable())
            ->modify("+{$backoff} seconds")
            ->format('Y-m-d H:i:s');

        return new RateLimitStatusDTO(
            limit: 0,
            remaining: 0,
            resetAfter: $backoff,
            retryAfter: $backoff,
            backoffSeconds: $backoff,
            nextAllowedAt: $nextAllowed
        );
    }
}
