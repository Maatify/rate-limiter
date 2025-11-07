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
 * Implements the {@see RateLimiterInterface} using **MySQL** as the storage backend.
 * Tracks request counts per unique key (IP, user, token, etc.) and enforces
 * rate-limit rules defined in {@see RateLimitConfig}.
 *
 * ✅ Features:
 * - Compatible with distributed systems sharing the same database.
 * - Uses `INSERT ... ON DUPLICATE KEY UPDATE` for atomic counter increments.
 * - Persists rate-limit activity for analysis and long-term tracking.
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
     * 🧠 Constructor
     *
     * Initializes the MySQL-based rate limiter with a PDO connection.
     *
     * @param PDO $pdo Active PDO instance connected to the database.
     */
    public function __construct(private readonly PDO $pdo) {}

    /**
     * 🎯 Attempt an action and increment the rate-limit counter.
     *
     * Inserts or updates a record in the `ip_rate_limits` table for the specified key.
     * Throws {@see TooManyRequestsException} when the configured request limit is exceeded.
     *
     * @param string $key Unique key (e.g., IP, user ID, token).
     * @param RateLimitActionInterface $action The rate-limited logical action.
     * @param PlatformInterface $platform The platform or context of the request.
     *
     * @return RateLimitStatusDTO Updated rate-limit status.
     *
     * @throws TooManyRequestsException When the limit is reached or exceeded.
     *
     * ✅ Example:
     * ```php
     * $limiter->attempt('192.168.1.1', RateLimitActionEnum::REGISTER, PlatformEnum::WEB);
     * ```
     */
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 🔹 Load configuration and compose unique key
        $config = RateLimitConfig::get($action->value());
        $key = "{$platform->value()}_{$action->value()}_{$key}";

        // ⚙️ Insert or increment count atomically
        $stmt = $this->pdo->prepare("
            INSERT INTO ip_rate_limits (key_name, count, last_attempt)
            VALUES (:key, 1, NOW())
            ON DUPLICATE KEY UPDATE count = count + 1, last_attempt = NOW()
        ");
        $stmt->execute(['key' => $key]);

        // 🔍 Retrieve current count
        $count = (int) $this->pdo
            ->query("SELECT count FROM ip_rate_limits WHERE key_name = '{$key}'")
            ->fetchColumn();

        // 🚫 Throw if exceeded
        if ($count > $config['limit']) {
            throw new TooManyRequestsException('Rate limit exceeded', 429);
        }

        // ✅ Return DTO
        return new RateLimitStatusDTO(
            limit: $config['limit'],
            remaining: $config['limit'] - $count,
            resetAfter: $config['interval']
        );
    }

    /**
     * ♻️ Reset the rate-limit record for a given key/action/platform.
     *
     * Deletes the rate-limit entry from the database for the specific key.
     * Useful for manual resets or administrative overrides.
     *
     * @param string $key Unique key (e.g., IP, user ID, token).
     * @param RateLimitActionInterface $action The rate-limited logical action.
     * @param PlatformInterface $platform The platform or context of the request.
     *
     * @return bool True if the record was deleted successfully.
     *
     * ✅ Example:
     * ```php
     * $limiter->reset('user123', RateLimitActionEnum::API_CALL, PlatformEnum::API);
     * ```
     */
    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
    {
        $key = "{$platform->value()}_{$action->value()}_{$key}";
        $stmt = $this->pdo->prepare("DELETE FROM ip_rate_limits WHERE key_name = ?");
        return $stmt->execute([$key]);
    }

    /**
     * 🔍 Retrieve current rate-limit status without incrementing counters.
     *
     * Fetches the current count for the given key and returns a snapshot of
     * the remaining quota and reset time, without updating the record.
     *
     * @param string $key Unique key (e.g., IP, user ID, token).
     * @param RateLimitActionInterface $action The rate-limited logical action.
     * @param PlatformInterface $platform The platform or context of the request.
     *
     * @return RateLimitStatusDTO Snapshot of the rate-limit state.
     *
     * ✅ Example:
     * ```php
     * $status = $limiter->status('user123', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
     * echo $status->remaining;
     * ```
     */
    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
    {
        // 🔹 Retrieve configuration and key
        $config = RateLimitConfig::get($action->value());
        $key = "{$platform->value()}_{$action->value()}_{$key}";

        // 📊 Query the current count
        $stmt = $this->pdo->prepare("SELECT count FROM ip_rate_limits WHERE key_name = ?");
        $stmt->execute([$key]);
        $count = (int) $stmt->fetchColumn();

        // 🧠 Return the current rate-limit status
        return new RateLimitStatusDTO(
            limit: $config['limit'],
            remaining: max(0, $config['limit'] - $count),
            resetAfter: $config['interval']
        );
    }
}
