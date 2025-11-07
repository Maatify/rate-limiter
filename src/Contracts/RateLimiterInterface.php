<?php
declare(strict_types=1);

namespace Maatify\RateLimiter\Contracts;

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

/**
 * 🎯 Interface RateLimiterInterface
 *
 * 🧩 Purpose:
 * Defines the **core contract** for all rate limiter driver implementations
 * (e.g., Redis, MongoDB, MySQL). Each driver enforces request limits
 * for different actions and platforms while maintaining consistent API behavior.
 *
 * This interface allows any driver to interact seamlessly with
 * {@see RateLimitActionInterface} and {@see PlatformInterface} instances.
 *
 * ⚙️ Responsibilities:
 * - Control and track request attempts per unique key.
 * - Reset or clear rate-limit counters.
 * - Provide detailed status reports without altering state.
 *
 * ✅ Example Implementation:
 * ```php
 * use Maatify\RateLimiter\Contracts\RateLimiterInterface;
 * use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
 * use Maatify\RateLimiter\Contracts\PlatformInterface;
 * use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
 *
 * final class RedisRateLimiter implements RateLimiterInterface
 * {
 *     public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
 *     {
 *         // Implementation logic
 *     }
 *
 *     public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool
 *     {
 *         // Reset logic
 *     }
 *
 *     public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO
 *     {
 *         // Status logic
 *     }
 * }
 * ```
 *
 * @package Maatify\RateLimiter\Contracts
 */
interface RateLimiterInterface
{
    /**
     * 🚀 Attempt to perform an action under rate limit rules.
     *
     * 🧠 This method checks whether a specific key (IP, user_id, token, etc.)
     * is still allowed to perform the given action within the defined limits.
     * It increments usage counters and throws {@see TooManyRequestsException}
     * when the threshold is exceeded.
     *
     * @param string $key Unique identifier for the entity (e.g., IP, user_id, API key).
     * @param RateLimitActionInterface $action The logical action being rate-limited.
     * @param PlatformInterface $platform The execution context (e.g., web, api, admin).
     *
     * @return RateLimitStatusDTO Contains the limit, remaining quota, and reset timing.
     *
     * @throws TooManyRequestsException When the limit is exceeded for this key.
     *
     * ✅ Example:
     * ```php
     * try {
     *     $status = $rateLimiter->attempt('127.0.0.1', $action, $platform);
     *     echo $status->remaining;
     * } catch (TooManyRequestsException $e) {
     *     echo 'Too many requests!';
     * }
     * ```
     */
    public function attempt(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO;

    /**
     * ♻️ Reset the rate-limit record for a specific key and action.
     *
     * Removes or clears all stored usage counters, effectively allowing
     * the key to start fresh as if it had made no previous requests.
     *
     * @param string $key The unique identifier (IP, user_id, token, etc.).
     * @param RateLimitActionInterface $action The logical action to reset.
     * @param PlatformInterface $platform The associated platform or environment.
     *
     * @return bool True if reset succeeded, false otherwise.
     *
     * ✅ Example:
     * ```php
     * $rateLimiter->reset('192.168.1.1', $action, $platform);
     * ```
     */
    public function reset(string $key, RateLimitActionInterface $action, PlatformInterface $platform): bool;

    /**
     * 🔍 Retrieve the current rate-limit status without modifying counters.
     *
     * Provides an immutable snapshot of the rate-limit state for
     * monitoring, diagnostics, or user notifications.
     *
     * @param string $key The unique identifier (IP, user_id, token, etc.).
     * @param RateLimitActionInterface $action The logical action being queried.
     * @param PlatformInterface $platform The execution context.
     *
     * @return RateLimitStatusDTO Snapshot of current rate-limit values.
     *
     * ✅ Example:
     * ```php
     * $status = $rateLimiter->status('127.0.0.1', $action, $platform);
     * echo $status->remaining . " requests left.";
     * ```
     */
    public function status(string $key, RateLimitActionInterface $action, PlatformInterface $platform): RateLimitStatusDTO;
}
