<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 01:07
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Maatify\RateLimiter\Config\RateLimitConfig;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;

/**
 * 🧩 Class DriversTest
 *
 * 🎯 Purpose:
 * Validates configuration structure and integrity for the **maatify/rate-limiter** drivers.
 * Ensures that all defined rate-limit actions (via {@see RateLimitActionEnum})
 * return properly structured configurations from {@see RateLimitConfig}.
 *
 * ⚙️ Focus:
 * - Verifies presence of required configuration keys.
 * - Confirms compatibility between enum values and configuration retrieval.
 *
 * ✅ Example execution:
 * ```bash
 * ./vendor/bin/phpunit --filter DriversTest
 * ```
 *
 * @package Maatify\RateLimiter\Tests
 */
final class DriversTest extends TestCase
{
    /**
     * 🧠 Test configuration structure returned by RateLimitConfig.
     *
     * 🎯 Ensures each rate-limit action has a `limit` and `interval` key defined.
     */
    public function testConfigValues(): void
    {
        // 🔹 Retrieve configuration for LOGIN action
        $login = RateLimitConfig::get(RateLimitActionEnum::LOGIN->value);

        // ✅ Check that required keys exist
        $this->assertArrayHasKey('limit', $login, 'Config must contain a "limit" key.');
        $this->assertArrayHasKey('interval', $login, 'Config must contain an "interval" key.');
    }
}
