<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:52
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
use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;

/**
 * ⚙️ Class MongoRateLimiter
 *
 * 🧩 Purpose:
 * Provides a MongoDB-backed implementation of {@see RateLimiterInterface}.
 * This driver uses MongoDB documents to track rate-limit usage counts per
 * key (IP, user, token, etc.) across different actions and platforms.
 *
 * ✅ Features:
 * - Persistent rate-limit tracking across distributed systems.
 * - Atomic operations for concurrency safety.
 * - Ideal for multi-node, horizontally scaled environments.
 * - Stores creation timestamps for analytics or future cleanup.
 *
 * ⚙️ Example Usage:
 * ```php
 * use Maatify\RateLimiter\Drivers\MongoRateLimiter;
 * use Maatify\RateLimiter\Enums\RateLimitActionEnum;
 * use Maatify\RateLimiter\Enums\PlatformEnum;
 * use MongoDB\Client;
 *
 * $client = new Client('mongodb://localhost:27017');
 * $collection = $client->selectCollection('rate_limiter', 'limits');
 *
 * $limiter = new MongoRateLimiter($collection);
 * $status = $limiter->attempt('192.168.0.5', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
 *
 * echo json_encode($status->toArray(), JSON_PRETTY_PRINT);
 * ```
 *
 * @package Maatify\RateLimiter\Drivers
 */
final class MongoRateLimiter implements RateLimiterInterface
{
    /**
     * @var Collection MongoDB collection instance for rate-limit storage.
     */
    private readonly Collection $collection;

    /**
     * 🧠 Constructor
     *
     * Initializes a MongoDB collection for persistent rate-limit tracking.
     *
     * @param Collection $collection MongoDB collection used for storing rate-limit entries.
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * 🎯 Attempt an action and increment the rate-limit counter.
     *
     * This method increments (or creates) a MongoDB record that tracks the
     * number of requests performed under a specific key/action/platform.
     * Throws {@see TooManyRequestsException} when the rate limit is exceeded.
     *
     * @param string $key Unique key (e.g., IP, user ID, token).
     * @param RateLimitActionInterface $action Logical action being rate-limited.
     * @param PlatformInterface $platform Platform or execution context.
     *
     * @return RateLimitStatusDTO Updated rate-limit status.
     *
     * @throws TooManyRequestsException When the configured limit is exceeded.
     *
     * ✅ Example:
     * ```php
     * $limiter->attempt('user123', RateLimitActionEnum::REGISTER, PlatformEnum::MOBILE);
     * ```
     */
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // ⚙️ Fetch configuration and construct document key
        $config = RateLimitConfig::get($action->value());
        $now = new UTCDateTime();
        $docKey = "{$platform->value()}_{$action->value()}_{$key}";

        // 🔄 Increment counter atomically; create record if missing
        $this->collection->updateOne(
            ['_id' => $docKey],
            [
                '$inc' => ['count' => 1],
                '$setOnInsert' => ['created_at' => $now],
                '$set' => ['last_attempt' => $now],
            ],
            ['upsert' => true]
        );

        // 📊 Retrieve current record
        $record = (array)$this->collection->findOne(['_id' => $docKey]);
        $count = isset($record['count']) && is_numeric($record['count']) ? (int) $record['count'] : 1;

        // 🚫 Throw if limit exceeded
        if ($count > $config['limit']) {
            throw new TooManyRequestsException('Rate limit exceeded', 429);
        }

        // ✅ Return updated status as DTO
        return new RateLimitStatusDTO(
            limit: $config['limit'],
            remaining: $config['limit'] - $count,
            resetAfter: $config['interval']
        );
    }

    /**
     * ♻️ Reset the rate-limit record for a given key/action/platform.
     *
     * Deletes the MongoDB document corresponding to this unique combination.
     * Useful for admin overrides, testing, or resetting bans.
     *
     * @param string $key Unique identifier (e.g., IP, user_id, API key).
     * @param RateLimitActionInterface $action Action being reset.
     * @param PlatformInterface $platform Platform or context.
     *
     * @return bool True if a record was deleted successfully.
     *
     * ✅ Example:
     * ```php
     * $limiter->reset('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
     * ```
     */
    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        // 🔹 Compose the unique document identifier
        $docId = "{$platform->value()}_{$action->value()}_{$key}";

        // 🗑️ Delete and return success flag
        return (bool) $this->collection
            ->deleteOne(['_id' => $docId])
            ->getDeletedCount();
    }

    /**
     * 🔍 Retrieve current rate-limit status without incrementing counters.
     *
     * Returns a snapshot of current usage (remaining requests and reset interval)
     * without altering or incrementing the stored counter.
     *
     * @param string $key Unique key (e.g., IP, user_id, token).
     * @param RateLimitActionInterface $action Logical action being inspected.
     * @param PlatformInterface $platform Platform or environment.
     *
     * @return RateLimitStatusDTO Snapshot of the current rate-limit state.
     *
     * ✅ Example:
     * ```php
     * $status = $limiter->status('user123', RateLimitActionEnum::API_CALL, PlatformEnum::API);
     * echo $status->remaining;
     * ```
     */
    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 🔹 Retrieve current configuration and document
        $config = RateLimitConfig::get($action->value());
        $docId = "{$platform->value()}_{$action->value()}_{$key}";
        $record = (array)$this->collection->findOne(['_id' => $docId]);
        $count = isset($record['count']) && is_numeric($record['count']) ? (int) $record['count'] : 0;

        // 🧠 Return structured status object
        return new RateLimitStatusDTO(
            limit: $config['limit'],
            remaining: $config['limit'] - $count,
            resetAfter: $config['interval']
        );
    }

    /**
     * ⚙️ Calculate exponential backoff duration.
     *
     * 🧠 This method computes the delay time (in seconds) before the next
     * allowed attempt using exponential growth logic.
     *
     * @param int $attempts Number of failed or consecutive attempts.
     * @param int $base Base multiplier for the backoff (default 2).
     * @param int $max Maximum allowed backoff time (in seconds).
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
     * 🔒 Apply exponential backoff and update MongoDB state.
     *
     * Stores the calculated backoff duration and next allowed attempt time
     * in the MongoDB record for analytical or enforcement use.
     *
     * @param string $key Unique key (e.g., IP, user_id, token).
     * @param int $attempts Current number of failed or blocked attempts.
     *
     * @return RateLimitStatusDTO DTO describing the applied backoff status.
     *
     * ✅ Example:
     * ```php
     * $dto = $this->applyBackoff('user123', 3);
     * echo $dto->nextAllowedAt;
     * ```
     */
    private function applyBackoff(string $key, int $attempts): RateLimitStatusDTO
    {
        // ⏱️ Calculate next backoff window
        $backoff = $this->calculateBackoff($attempts);
        $nextAllowed = (new \DateTimeImmutable("+{$backoff} seconds"))->format('Y-m-d H:i:s');

        // 🧾 Update Mongo document with new blocked_until and backoff metadata
        $this->collection->updateOne(
            ['_id' => $key],
            [
                '$set' => [
                    'blocked_until' => new \MongoDB\BSON\UTCDateTime(strtotime($nextAllowed) * 1000),
                    'backoff_seconds' => $backoff,
                ]
            ],
            ['upsert' => true]
        );

        // 📦 Return a structured DTO describing the applied backoff
        return new RateLimitStatusDTO(
            limit: 0,
            remaining: 0,
            resetAfter: $backoff,
            retryAfter: $backoff,
            blocked: true,
            backoffSeconds: $backoff,
            nextAllowedAt: $nextAllowed
        );
    }
}
