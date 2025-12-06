<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-06
 * Time: 21:30
 * Project: maatify/rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * 🧩 Class SampleTest
 *
 * 🎯 Purpose:
 * Basic placeholder test to confirm that PHPUnit and the testing
 * environment are correctly configured for the **maatify/rate-limiter** project.
 *
 * ⚙️ This test serves as a sanity check — ensuring that the test runner
 * executes successfully before adding more complex test cases.
 *
 * ✅ Example execution:
 * ```bash
 * ./vendor/bin/phpunit --filter SampleTest
 * ```
 *
 * @package Maatify\RateLimiter\Tests
 */
final class SampleTest extends TestCase
{
    /**
     * ✅ Verifies that the PHPUnit environment and configuration are functioning.
     *
     * 🧠 If this test passes, your PHPUnit setup is ready for additional tests.
     */
    public function testInitialSetup(): void
    {
        // 🎯 Simple assertion to confirm test environment readiness
        $this->assertTrue(1 === 1, 'Environment setup is working correctly.');
    }
}
