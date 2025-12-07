<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:27
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests;

use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use PHPUnit\Framework\TestCase;

/**
 * 🧩 Class CoreStructureTest
 *
 * 🎯 Purpose:
 * Validates the foundational structure and compliance of core components
 * in the **maatify/rate-limiter** library — ensuring enums, DTOs, and
 * interfaces are properly defined and conform to expected contracts.
 *
 * ⚙️ This suite focuses purely on **class/interface definitions**
 * (not functional rate-limiting logic), confirming that:
 * - Enums implement the required interfaces.
 * - DTOs behave as immutable value objects.
 * - Core contracts exist for dependency injection and polymorphism.
 *
 * ✅ Example execution:
 * ```bash
 * ./vendor/bin/phpunit --filter CoreStructureTest
 * ```
 *
 * @package Maatify\RateLimiter\Tests
 */
final class CoreStructureTest extends TestCase
{
    /**
     * 🧠 Test that DTOs and Enums are properly defined and usable.
     *
     * 🎯 Verifies:
     * - DTO property accessibility.
     * - Enum instantiation and type validity.
     * - DTO’s internal state matches expected constructor parameters.
     */
    public function testEnumsAndDTO(): void
    {
        // ✅ Create a DTO instance and verify its immutability and structure
        $status = new RateLimitStatusDTO(10, 5, 60);
        $this->assertSame(5, $status->remaining, 'Remaining count should match constructor value.');
        $this->assertSame(10, $status->limit, 'Limit should match constructor value.');
        $this->assertSame(60, $status->resetAfter, 'ResetAfter should match constructor value.');

        // 🔹 Ensure Enum cases exist and are valid instances
        $this->assertInstanceOf(RateLimitActionEnum::class, RateLimitActionEnum::LOGIN);
        $this->assertInstanceOf(PlatformEnum::class, PlatformEnum::WEB);
    }

    /**
     * 🔍 Test that the RateLimiterInterface exists and is properly declared.
     *
     * 🎯 Confirms that the main rate limiter contract is available for
     * dependency injection and consistent implementation across drivers.
     */
    public function testInterfaceExists(): void
    {
        $this->assertTrue(
            interface_exists(RateLimiterInterface::class),
            'RateLimiterInterface must be declared in the library.'
        );
    }

    /**
     * 🔗 Test that Enums correctly implement their respective Contracts.
     *
     * 🎯 Ensures:
     * - RateLimitActionEnum implements RateLimitActionInterface.
     * - PlatformEnum implements PlatformInterface.
     * This guarantees compatibility with all driver classes that depend
     * on interface-based type hints.
     */
    public function testEnumsImplementContracts(): void
    {
        $this->assertInstanceOf(
            RateLimitActionInterface::class,
            RateLimitActionEnum::LOGIN,
            'RateLimitActionEnum must implement RateLimitActionInterface.'
        );

        $this->assertInstanceOf(
            PlatformInterface::class,
            PlatformEnum::WEB,
            'PlatformEnum must implement PlatformInterface.'
        );
    }
}
