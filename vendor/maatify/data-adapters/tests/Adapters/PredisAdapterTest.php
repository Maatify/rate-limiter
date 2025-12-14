<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:54
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Adapters;

use Exception;
use Maatify\DataAdapters\Adapters\PredisAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 Class PredisAdapterTest
 *
 * 🧩 Purpose:
 * Verifies that {@see PredisAdapter} can be properly instantiated and exposes
 * its essential methods (`connect`, `healthCheck`, etc.) without establishing
 * a real Redis connection.
 *
 * ✅ What It Tests:
 * - The adapter class can be constructed with a valid {@see EnvironmentConfig}.
 * - The required interface methods exist as expected.
 *
 * ⚙️ Example Execution:
 * ```bash
 * ./vendor/bin/phpunit --filter PredisAdapterTest
 * ```
 *
 * @package Maatify\DataAdapters\Tests\Adapters
 */
final class PredisAdapterTest extends TestCase
{
    /**
     * 🎯 Test that PredisAdapter can be instantiated successfully.
     *
     * Ensures that the adapter is constructed with valid configuration and
     * exposes core interface methods such as `connect()` and `healthCheck()`.
     *
     * ✅ Expected:
     * - Object is instance of {@see PredisAdapter}.
     * - Methods `connect` and `healthCheck` exist.
     *
     * @throws Exception
     */
    public function testPredisAdapterInstantiates(): void
    {
        // 🧩 Load environment configuration for the adapter
        $config = new EnvironmentConfig(dirname(__DIR__, 3));

        // ⚙️ Instantiate Predis adapter (no actual connection performed)
        $adapter = new PredisAdapter($config);

        // ✅ Validate adapter structure and available methods
        $this->assertInstanceOf(PredisAdapter::class, $adapter);
        $this->assertTrue(method_exists($adapter, 'connect'), 'PredisAdapter must implement connect()');
        $this->assertTrue(method_exists($adapter, 'healthCheck'), 'PredisAdapter must implement healthCheck()');
    }
}
