<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-11 19:21
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Core;

use Maatify\DataAdapters\Core\BaseAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use Maatify\DataAdapters\Core\Exceptions\ConnectionException;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class BaseAdapterTest**
 *
 * 🎯 **Purpose:**
 * Validates core functionality of {@see BaseAdapter} and its dependency
 * {@see EnvironmentConfig}, ensuring environment handling and exception logic
 * behave as expected.
 *
 * 🧠 **Key Tests:**
 * - Confirms `EnvironmentConfig` loads correctly from the given path.
 * - Ensures `requireEnv()` throws a {@see ConnectionException} when a required key is missing.
 *
 * ✅ **Example Run:**
 * ```bash
 * vendor/bin/phpunit --filter BaseAdapterTest
 * ```
 */
final class BaseAdapterTest extends TestCase
{
    /**
     * ✅ **Test EnvironmentConfig Initialization**
     *
     * Ensures that {@see EnvironmentConfig} can be instantiated successfully
     * using the current project path.
     *
     * @return void
     */
    public function testEnvironmentConfigLoadsProperly(): void
    {
        $config = new EnvironmentConfig(__DIR__ . '/../../');
        $this->assertInstanceOf(EnvironmentConfig::class, $config);
    }

    /**
     * 🚫 **Test Missing Environment Variable Handling**
     *
     * Verifies that calling `requireEnv()` with a non-existent key
     * triggers a {@see ConnectionException}.
     *
     * @return void
     */
    public function testRequireEnvThrowsConnectionExceptionForMissingKey(): void
    {
        $config = new EnvironmentConfig(__DIR__ . '/../../');
        $adapter = $this->getMockForAbstractClass(BaseAdapter::class, [$config]);

        $this->expectException(ConnectionException::class);
        $this->invokeRequireEnv($adapter, 'NON_EXISTENT_KEY');
    }

    /**
     * 🧩 **Invoke Private Method via Reflection**
     *
     * Helper to access and invoke the protected `requireEnv()` method
     * for testing purposes.
     *
     * @param BaseAdapter $adapter Instance of the adapter under test.
     * @param string      $key     The environment key to request.
     *
     * @return void
     */
    private function invokeRequireEnv(BaseAdapter $adapter, string $key): void
    {
        $method = new \ReflectionMethod($adapter, 'requireEnv');
        $method->setAccessible(true);
        $method->invoke($adapter, $key);
    }
}
