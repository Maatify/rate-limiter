<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:00
 * Project: maatify/rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Contracts;

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

/**
 * 🎯 Interface RateLimiterInterface
 *
 * 🧩 Purpose:
 * Defines the contract for any Rate Limiter implementation (e.g., Redis-based, MySQL-based, or hybrid).
 * This interface ensures a consistent API for checking, resetting, and querying rate-limit states
 * across different platforms and actions.
 *
 * ⚙️ Responsibilities:
 * - Verify if an IP address has exceeded allowed request limits.
 * - Track and update rate-limit counters per action/platform.
 * - Reset rate-limit records when needed.
 * - Retrieve the current rate-limit status without side effects.
 *
 * ✅ Typical usage:
 * ```php
 * use Maatify\RateLimiter\Contracts\RateLimiterInterface;
 * use Maatify\RateLimiter\Enums\RateLimitActionEnum;
 * use Maatify\RateLimiter\Enums\PlatformEnum;
 *
 * $limiter->attempt('192.168.1.1', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
 * ```
 *
 * @package Maatify\RateLimiter\Contracts
 */
interface RateLimiterInterface
{
    /**
     * 🧠 Check and update rate limit for a given action/platform/IP.
     *
     * 🎯 This method validates if the given IP has exceeded its request quota.
     * If the limit is not reached, it increments the counter and returns the updated status.
     * If the limit is exceeded, it throws a {@see TooManyRequestsException}.
     *
     * @param string $ip The client IP address.
     * @param RateLimitActionEnum $action The rate-limited action (e.g., LOGIN, OTP_REQUEST).
     * @param PlatformEnum $platform The target platform (e.g., WEB, API, MOBILE).
     *
     * @return RateLimitStatusDTO Contains current usage, remaining attempts, and next available time.
     *
     * @throws TooManyRequestsException If the client has exceeded the allowed rate limit.
     *
     * ✅ Example:
     * ```php
     * try {
     *     $status = $limiter->attempt('127.0.0.1', RateLimitActionEnum::OTP_REQUEST, PlatformEnum::API);
     *     echo $status->remainingAttempts;
     * } catch (TooManyRequestsException $e) {
     *     echo "Too many requests. Try again later.";
     * }
     * ```
     */
    public function attempt(string $ip, RateLimitActionEnum $action, PlatformEnum $platform): RateLimitStatusDTO;

    /**
     * ♻️ Manually reset the rate limit for a specific key.
     *
     * 🧩 Useful when an admin, system job, or recovery action needs to clear
     * a user’s IP-based rate-limit record before the automatic reset period.
     *
     * @param string $ip The client IP address.
     * @param RateLimitActionEnum $action The action to reset (e.g., LOGIN, PASSWORD_RESET).
     * @param PlatformEnum $platform The platform context.
     *
     * @return bool True if reset was successful, false otherwise.
     *
     * ✅ Example:
     * ```php
     * $limiter->reset('192.168.1.10', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
     * ```
     */
    public function reset(string $ip, RateLimitActionEnum $action, PlatformEnum $platform): bool;

    /**
     * 🔍 Get the current status without incrementing counters.
     *
     * 🧠 Use this method to inspect current rate-limit status safely —
     * e.g., in dashboards or monitoring tools — without affecting limits.
     *
     * @param string $ip The client IP address.
     * @param RateLimitActionEnum $action The target rate-limit action.
     * @param PlatformEnum $platform The target platform.
     *
     * @return RateLimitStatusDTO Snapshot of current rate-limit information.
     *
     * ✅ Example:
     * ```php
     * $status = $limiter->status('127.0.0.1', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
     * echo $status->remainingAttempts;
     * ```
     */
    public function status(string $ip, RateLimitActionEnum $action, PlatformEnum $platform): RateLimitStatusDTO;
}
