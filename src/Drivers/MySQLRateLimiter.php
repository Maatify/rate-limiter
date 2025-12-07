<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 01:06
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
use PDO;

/**
 * ⚙️ Class MySQLRateLimiter
 *
 * 🧩 Purpose:
 * Implements the {@see RateLimiterInterface} using **MySQL** as the persistent storage backend.
 * Tracks and enforces rate limits based on key identifiers (IP, user ID, token, etc.)
 * for different actions and platforms using atomic SQL operations.
 *
 * ✅ Key Features:
 * - Fully compatible with distributed systems that share a MySQL database.
 * - Uses `INSERT ... ON DUPLICATE KEY UPDATE` for atomic, race-free increments.
 * - Enables persistent tracking for analytics, debugging, or administrative purposes.
 *
 * ⚙️ Example:
 * ```php
 * use Maatify\RateLimiter\Drivers\MySQLRateLimiter;
 * use Maatify\RateLimiter\Enums\RateLimitActionEnum;
 * use Maatify\RateLimiter\Enums\PlatformEnum;
 * use PDO;
 *
 * $pdo = new PDO('mysql:host=localhost;dbname=maatify', 'root', '');
 * $limiter = new MySQLRateLimiter($pdo);
 * $status = $limiter->attempt('127.0.0.1', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
 *
 * echo json_encode($status->toArray(), JSON_PRETTY_PRINT);
 * ```
 *
 * @package Maatify\RateLimiter\Drivers
 */
final class MySQLRateLimiter implements RateLimiterInterface
{
    /**
     * @var PDO Active PDO instance used for all MySQL operations.
     */
    private readonly PDO $pdo;

    /**
     * 🧠 Constructor
     *
     * Initializes the MySQL-based rate limiter with a PDO connection.
     *
     * @param PDO $pdo Active PDO instance connected to the database.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * 🎯 Attempt an action and increment the rate-limit counter.
     *
     * Inserts or updates a record in the `ip_rate_limits` table for the specified key.
     * If the key already exists, its counter is incremented atomically.
     * Throws {@see TooManyRequestsException} when the request limit is reached or exceeded.
     *
     * @param string $key Unique identifier (IP, user ID, token, etc.).
     * @param RateLimitActionInterface $action The logical action being rate-limited.
     * @param PlatformInterface $platform The platform or request context (e.g., web, api, mobile).
     *
     * @return RateLimitStatusDTO A DTO describing the updated rate-limit state.
     *
     * @throws TooManyRequestsException When the configured rate limit is exceeded.
     *
     * ✅ Example:
     * ```php
     * $limiter->attempt('192.168.1.1', RateLimitActionEnum::REGISTER, PlatformEnum::WEB);
     * ```
     */
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 🔹 Load configuration and compose unique rate-limit key
        $config = RateLimitConfig::get($action->value());
        $key = "{$platform->value()}_{$action->value()}_{$key}";

        // ⚙️ Atomic upsert: insert or increment counter
        $stmt = $this->pdo->prepare('
            INSERT INTO ip_rate_limits (key_name, count, last_attempt)
            VALUES (:key, 1, NOW())
            ON DUPLICATE KEY UPDATE count = count + 1, last_attempt = NOW()
        ');
        if ($stmt) {
            $stmt->execute(['key' => $key]);
        }

        // 📊 Retrieve current request count
        $stmt = $this->pdo->prepare("SELECT count FROM ip_rate_limits WHERE key_name = ?");
        $count = 0;
        if ($stmt) {
            $stmt->execute([$key]);
            $count = (int) $stmt->fetchColumn();
        }

        // 🚫 If count exceeds configured limit, block the request
        if ($count > $config['limit']) {
            throw new TooManyRequestsException('Rate limit exceeded', 429);
        }

        // ✅ Return DTO representing the new rate-limit state
        return new RateLimitStatusDTO(
            limit: $config['limit'],
            remaining: $config['limit'] - $count,
            resetAfter: $config['interval']
        );
    }

    /**
     * ♻️ Reset the rate-limit record for a given key/action/platform.
     *
     * Deletes the rate-limit entry from the database for a specific key.
     * Commonly used for admin resets, testing, or programmatic overrides.
     *
     * @param string $key Unique key (e.g., IP, user ID, token).
     * @param RateLimitActionInterface $action The action being reset.
     * @param PlatformInterface $platform The platform or execution context.
     *
     * @return bool True if the record was successfully deleted, false otherwise.
     *
     * ✅ Example:
     * ```php
     * $limiter->reset('user123', RateLimitActionEnum::API_CALL, PlatformEnum::API);
     * ```
     */
    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        // 🧩 Compose composite rate-limit key
        $key = "{$platform->value()}_{$action->value()}_{$key}";

        // 🗑️ Delete record from table
        $stmt = $this->pdo->prepare('DELETE FROM ip_rate_limits WHERE key_name = ?');
        if ($stmt) {
            return $stmt->execute([$key]);
        }
        return false;
    }

    /**
     * 🔍 Retrieve the current rate-limit status without modifying counters.
     *
     * Returns a static snapshot of the rate-limit state (remaining requests, reset interval)
     * without altering or incrementing the counter.
     *
     * @param string $key Unique identifier (e.g., IP, user ID, token).
     * @param RateLimitActionInterface $action The logical action being inspected.
     * @param PlatformInterface $platform The platform context (e.g., web, api).
     *
     * @return RateLimitStatusDTO A DTO representing the current rate-limit state.
     *
     * ✅ Example:
     * ```php
     * $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
     * echo $status->remaining;
     * ```
     */
    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 🔹 Build full rate-limit key and load configuration
        $config = RateLimitConfig::get($action->value());
        $key = "{$platform->value()}_{$action->value()}_{$key}";

        // 📊 Query for the current counter value
        $stmt = $this->pdo->prepare('SELECT count FROM ip_rate_limits WHERE key_name = ?');
        $count = 0;
        if ($stmt) {
            $stmt->execute([$key]);
            $count = (int) $stmt->fetchColumn();
        }

        // 🧠 Return a snapshot DTO describing the current rate-limit status
        return new RateLimitStatusDTO(
            limit: $config['limit'],
            remaining: max(0, $config['limit'] - $count),
            resetAfter: $config['interval']
        );
    }

    /**
     * ⚙️ Calculate exponential backoff duration.
     *
     * 🧠 Computes the delay (in seconds) before the next allowed attempt
     * using an exponential backoff strategy.
     *
     * @param int $attempts Number of consecutive failed or blocked attempts.
     * @param int $base Base growth multiplier (default = 2).
     * @param int $max Maximum backoff duration (default = 3600 seconds).
     *
     * @return int Calculated backoff duration in seconds.
     *
     * ✅ Example:
     * ```php
     * $delay = $this->calculateBackoff(3); // Returns 8 seconds
     * ```
     */
    private function calculateBackoff(int $attempts, int $base = 2, int $max = 3600): int
    {
        return min(pow($base, $attempts), $max);
    }

    /**
     * 🔒 Apply exponential backoff to a MySQL-stored rate-limit record.
     *
     * Updates or inserts a record in the database to reflect a temporary block period,
     * including `blocked_until` and `backoff_seconds` metadata.
     *
     * @param string $key Unique identifier (e.g., IP, user ID, token).
     * @param int $attempts The current number of failed or blocked attempts.
     *
     * @return RateLimitStatusDTO A DTO describing the applied backoff and next allowed time.
     *
     * ✅ Example:
     * ```php
     * $status = $this->applyBackoff('user123', 4);
     * echo $status->nextAllowedAt;
     * ```
     */
    private function applyBackoff(string $key, int $attempts): RateLimitStatusDTO
    {
        // ⏱️ Compute next backoff duration
        $backoff = $this->calculateBackoff($attempts);
        $nextAllowed = (new \DateTimeImmutable("+{$backoff} seconds"))->format('Y-m-d H:i:s');

        // 🧾 Update or insert backoff data into database
        $stmt = $this->pdo->prepare('
            INSERT INTO ip_rate_limits (rate_key, blocked_until, backoff_seconds)
            VALUES (:key, :until, :backoff)
            ON DUPLICATE KEY UPDATE
                blocked_until = VALUES(blocked_until),
                backoff_seconds = VALUES(backoff_seconds)
        ');
        $stmt->execute([
            'key' => $key,
            'until' => $nextAllowed,
            'backoff' => $backoff
        ]);

        // 📦 Return structured DTO summarizing current backoff status
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
