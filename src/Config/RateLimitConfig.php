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
 * Holds configuration for different rate limit actions.
 */
final class RateLimitConfig
{
    public static function get(string $action): array
    {
        $defaults = [
            'limit' => 5,
            'interval' => 60, // seconds
            'ban_time' => 300, // 5 minutes
        ];

        return match ($action) {
            'login' => ['limit' => 5, 'interval' => 60, 'ban_time' => 600],
            'otp_request' => ['limit' => 3, 'interval' => 120, 'ban_time' => 900],
            'password_reset' => ['limit' => 2, 'interval' => 300, 'ban_time' => 1200],
            default => $defaults,
        };
    }
}
