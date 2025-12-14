<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:56
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Adapters;

use Exception;
use Maatify\DataAdapters\Adapters\MySQLAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class MySQLAdapterTest**
 *
 * 🎯 **Purpose:**
 * Verifies the structural integrity and autoloading of {@see MySQLAdapter}
 * without establishing a real database connection — ideal for CI/CD pipeline validation.
 *
 * 🧠 **Core Verifications:**
 * - Confirms that the adapter class is autoloaded and instantiable.
 * - Ensures that required methods (`connect`, `healthCheck`) exist.
 * - Avoids any real I/O or network dependency to maintain test isolation.
 *
 * 🧩 **When to Use:**
 * This mock-level test is designed for structural verification in automated
 * environments or local development setups where no live MySQL instance is needed.
 *
 * ✅ **Example Run:**
 * ```bash
 * APP_ENV=testing vendor/bin/phpunit --filter MySQLAdapterTest
 * ```
 */
final class MySQLAdapterTest extends TestCase
{
    /**
     * 🧩 **Test MySQLAdapter Autoloading and Structure**
     *
     * Ensures that the {@see MySQLAdapter} class can be instantiated
     * and exposes its required methods (`connect`, `healthCheck`).
     *
     * @throws Exception
     *
     * @return void
     */

    public function testMySQLAdapterClassLoads(): void
    {

        // ⚙️ Initialize environment config (without connecting)
        $config = new EnvironmentConfig(dirname(__DIR__, 3));

        // 🧠 Instantiate adapter for syntax and dependency validation
        $adapter = new MySQLAdapter($config);

        // ✅ Confirm instance type
        $this->assertInstanceOf(
            MySQLAdapter::class,
            $adapter,
            '❌ MySQLAdapter could not be instantiated or autoloaded.'
        );

        // ✅ Verify essential methods exist
        $this->assertTrue(
            method_exists($adapter, 'connect'),
            '❌ Expected method connect() not found on MySQLAdapter.'
        );

        $this->assertTrue(
            method_exists($adapter, 'healthCheck'),
            '❌ Expected method healthCheck() not found on MySQLAdapter.'
        );
    }
}
