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
 * Provides a high-performance Redis-based implementation of the
 * {@see RateLimiterInterface}, leveraging Redis atomic operations
 * to handle rate limiting efficiently and safely under concurrency.
 *
 * ✅ Features:
 * - Atomic increments (`INCR`, `EXPIRE`) ensure accurate counting.
 * - Automatic TTL expiration for resetting counters.
 * - Suitable for distributed and high-throughput systems.
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
    public function __construct(private readonly Redis $redis) {}

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
        return sprintf("rate:%s:%s:%s", $platform->value(), $action->value(), $key);
    }

    /**
     * 🎯 Attempt an action under rate-limit constraints.
     *
     * Increments the counter for the specified key/action/platform
     * and throws {@see TooManyRequestsException} if the limit is exceeded.
     *
     * @param string $key Unique identifier (IP, user ID, etc.).
     * @param RateLimitActionInterface $action The logical action being rate-limited.
     * @param PlatformInterface $platform The platform context.
     *
     * @return RateLimitStatusDTO Current rate-limit state after increment.
     *
     * @throws TooManyRequestsException When the configured limit is exceeded.
     *
     * ✅ Example:
     * ```php
     * $status = $limiter->attempt('192.168.1.5', RateLimitActionEnum::OTP_REQUEST, PlatformEnum::API);
     * ```
     */
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 🔹 Retrieve rate-limit configuration for action
        $config = RateLimitConfig::get($action->value());
        $docKey = $this->key($key, $action, $platform);
        $limit = $config['limit'];
        $interval = $config['interval'];

        // ⚙️ Increment request count atomically
        $current = (int) $this->redis->incr($docKey);

        // 🕒 Apply TTL on first increment
        if ($current === 1) {
            $this->redis->expire($docKey, $interval);
        }

        // 📊 Calculate remaining requests and TTL
        $ttl = (int) $this->redis->ttl($docKey);
        $remaining = max(0, $limit - $current);

        // 🚫 Throw exception when over limit
        if ($current > $limit) {
            throw new TooManyRequestsException('Rate limit exceeded', 429);
        }

        // ✅ Return structured DTO with live state
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
     *
     * ✅ Example:
     * ```php
     * $limiter->reset('user456', RateLimitActionEnum::REGISTER, PlatformEnum::MOBILE);
     * ```
     */
    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        return (bool) $this->redis->del($this->key($key, $action, $platform));
    }

    /**
     * 🔍 Retrieve rate-limit status without incrementing counters.
     *
     * Reads the current counter and TTL from Redis without affecting state.
     * Useful for dashboards, monitoring, or debugging rate-limit state.
     *
     * @param string $key Unique identifier (IP, user ID, token, etc.).
     * @param RateLimitActionInterface $action The rate-limited action.
     * @param PlatformInterface $platform The platform context.
     *
     * @return RateLimitStatusDTO Snapshot of the current rate-limit state.
     *
     * ✅ Example:
     * ```php
     * $status = $limiter->status('192.168.1.2', RateLimitActionEnum::API_CALL, PlatformEnum::API);
     * echo $status->remaining;
     * ```
     */
    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 🔹 Retrieve configuration and current state
        $config = RateLimitConfig::get($action->value());
        $docKey = $this->key($key, $action, $platform);

        // 📊 Read counter and TTL from Redis
        $count = (int) $this->redis->get($docKey);
        $ttl = (int) $this->redis->ttl($docKey);
        $remaining = max(0, $config['limit'] - $count);

        // 🧠 Return a static snapshot DTO
        return new RateLimitStatusDTO(
            limit: $config['limit'],
            remaining: $remaining,
            resetAfter: $ttl > 0 ? $ttl : $config['interval']
        );
    }
}
