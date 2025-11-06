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

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use PHPUnit\Framework\TestCase;

/**
 * 🧩 Class CoreStructureTest
 *
 * 🎯 Purpose:
 * Ensures the structural integrity of the core components in the
 * **maatify/rate-limiter** package — verifying that essential Enums,
 * DTOs, and Interfaces exist and function as expected.
 *
 * ⚙️ This test does **not** validate runtime behavior or Redis/MySQL logic;
 * it only checks that the basic class contracts are correctly defined.
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
     * - DTO property integrity.
     * - Enum instantiation validity.
     */
    public function testEnumsAndDTO(): void
    {
        // ✅ Create a DTO instance and verify field values
        $status = new RateLimitStatusDTO(10, 5, 60);
        $this->assertSame(5, $status->remaining, 'Remaining count should match constructor value.');

        // 🔹 Ensure Enum cases exist and are valid instances
        $this->assertTrue(RateLimitActionEnum::LOGIN instanceof RateLimitActionEnum);
        $this->assertTrue(PlatformEnum::WEB instanceof PlatformEnum);
    }

    /**
     * 🔍 Test that the RateLimiter interface is properly declared.
     *
     * 🎯 Ensures the main contract exists for dependency injection and implementation.
     */
    public function testInterfaceExists(): void
    {
        $this->assertTrue(interface_exists(RateLimiterInterface::class), 'RateLimiterInterface should exist.');
    }
}
