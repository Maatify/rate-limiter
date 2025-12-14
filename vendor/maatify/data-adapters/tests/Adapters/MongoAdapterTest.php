<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:55
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Adapters;

use Exception;
use Maatify\DataAdapters\Adapters\MongoAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 Class MongoAdapterTest
 *
 * 🧩 Purpose:
 * Ensures that the {@see MongoAdapter} can be successfully instantiated and
 * exposes the required methods defined by the {@see \Maatify\Common\Contracts\Adapter\AdapterInterface;
}.
 *
 * ⚙️ Behavior:
 * - This test **does not** perform a live MongoDB connection.
 * - It validates structural integrity and method availability only.
 *
 * ✅ What It Tests:
 * - {@see MongoAdapter} class loads correctly.
 * - Required methods (`connect`, `healthCheck`) exist.
 *
 * ⚙️ Example Execution:
 * ```bash
 * ./vendor/bin/phpunit --filter MongoAdapterTest
 * ```
 *
 * @package Maatify\DataAdapters\Tests\Adapters
 */
final class MongoAdapterTest extends TestCase
{
    /**
     * 🎯 Verifies that MongoAdapter class is loadable and exposes expected methods.
     *
     * Checks:
     * - Proper instantiation of the adapter with a valid configuration.
     * - Existence of key methods: `connect()` and `healthCheck()`.
     *
     * ✅ Expected:
     * - Adapter instance of {@see MongoAdapter}.
     * - Both methods available.
     *
     * @throws Exception
     */
    public function testMongoAdapterClassLoads(): void
    {
        // 🧩 Load environment configuration
        $config = new EnvironmentConfig(dirname(__DIR__, 3));

        // ⚙️ Instantiate MongoAdapter
        $adapter = new MongoAdapter($config);

        // ✅ Verify adapter structure and interface compliance
        $this->assertInstanceOf(MongoAdapter::class, $adapter);
        $this->assertTrue(method_exists($adapter, 'connect'), 'MongoAdapter must implement connect()');
        $this->assertTrue(method_exists($adapter, 'healthCheck'), 'MongoAdapter must implement healthCheck()');
    }
}
