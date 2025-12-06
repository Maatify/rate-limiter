<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:05
 * Project: maatify/rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Config;

/**
 * 🎯 Class RateLimitConfig
 *
 * 🧩 Purpose:
 * Provides a centralized configuration map for rate-limiting actions
 * such as login, OTP requests, or password resets.
 *
 * Each action defines:
 * - `limit`: Maximum number of allowed requests within the interval.
 * - `interval`: Time window (in seconds) for request counting.
 * - `ban_time`: Duration (in seconds) for which the client is blocked once the limit is exceeded.
 *
 * ⚙️ Usage:
 * ```php
 * use Maatify\RateLimiter\Config\RateLimitConfig;
 *
 * $config = RateLimitConfig::get('login');
 * // Example result:
 * // [
 * //     'limit' => 5,
 * //     'interval' => 60,
 * //     'ban_time' => 600
 * // ]
 * ```
 *
 * 🔹 Default configuration applies when the action name is not explicitly defined.
 *
 * @package Maatify\RateLimiter\Config
 */
final class RateLimitConfig
{
    /**
     * 🧠 Retrieve configuration for a specific rate-limit action.
     *
     * 🎯 This method maps each action type (like `login`, `otp_request`, or `password_reset`)
     * to its predefined rate-limit parameters. If the given action
     * does not have a custom configuration, a default one will be used.
     *
     * @param string $action The action name (e.g., 'login', 'otp_request', 'password_reset').
     *
     * @return array{
     *     limit: int,
     *     interval: int,
     *     ban_time: int
     * } Associative array containing rate-limit settings.
     *
     * ✅ Example:
     * ```php
     * $settings = RateLimitConfig::get('otp_request');
     * echo $settings['limit']; // 3
     * ```
     */
    public static function get(string $action): array
    {
        // 🔹 Default rate-limit parameters (used when action not matched)
        $defaults = [
            'limit' => 5,
            'interval' => 60, // seconds
            'ban_time' => 300, // 5 minutes
        ];

        // 🎯 Return matching configuration or fall back to defaults
        return match ($action) {
            'login' => ['limit' => 5, 'interval' => 60, 'ban_time' => 600],
            'otp_request' => ['limit' => 3, 'interval' => 120, 'ban_time' => 900],
            'password_reset' => ['limit' => 2, 'interval' => 300, 'ban_time' => 1200],
            default => $defaults,
        };
    }
}
