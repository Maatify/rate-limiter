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

namespace Maatify\RateLimiter\Tests;

use PHPUnit\Framework\TestCase;
use Maatify\RateLimiter\Config\ActionRateLimitConfig;
use Maatify\RateLimiter\Config\GlobalRateLimitConfig;
use Maatify\RateLimiter\Config\InMemoryActionRateLimitConfigProvider;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;

/**
 * 🧩 Class DriversTest
 *
 * 🎯 Purpose:
 * Validates configuration structure and integrity for the **maatify/rate-limiter** drivers.
 * Ensures that all defined rate-limit actions (via {@see RateLimitActionEnum})
 * return properly structured configurations from the in-memory provider.
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
     * 🧠 Test configuration structure returned by the in-memory provider.
     *
     * 🎯 Ensures each rate-limit action has immutable configuration available.
     */
    public function testConfigValues(): void
    {
        $provider = new InMemoryActionRateLimitConfigProvider(
            new GlobalRateLimitConfig(defaultLimit: 5, defaultInterval: 60, defaultBanTime: 300),
            [RateLimitActionEnum::LOGIN->value => new ActionRateLimitConfig(5, 60, 600)]
        );

        $login = $provider->getForAction(RateLimitActionEnum::LOGIN);

        $this->assertInstanceOf(ActionRateLimitConfig::class, $login);
        $this->assertSame(5, $login->limit());
        $this->assertSame(60, $login->interval());
    }
}
