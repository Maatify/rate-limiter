<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:34
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Core;

use Exception;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class CoreStructureTest**
 *
 * 🎯 **Purpose:**
 * Validates the foundational components of the Maatify Data Adapters core layer,
 * ensuring that environment configuration and adapter resolution are properly initialized.
 *
 * 🧠 **Core Verifications:**
 * - Confirms that {@see EnvironmentConfig} loads environment variables from `.env`.
 * - Ensures that {@see DatabaseResolver} can be instantiated successfully.
 *
 * 🧩 **Why This Matters:**
 * This test ensures that all higher-level adapters (Redis, MySQL, Mongo)
 * can rely on a stable and consistent bootstrap foundation.
 *
 * ✅ **Example Run:**
 * ```bash
 * APP_ENV=testing vendor/bin/phpunit --filter CoreStructureTest
 * ```
 */
final class CoreStructureTest extends TestCase
{
    /**
     * 🧩 **Test Environment Configuration Loader**
     *
     * Ensures that {@see EnvironmentConfig} can properly load `.env` variables
     * and expose them through the `get()` method.
     *
     * @throws Exception
     *
     * @return void
     */
    public function testEnvironmentConfigLoadsVariables(): void
    {
        $env = new EnvironmentConfig(dirname(__DIR__, 3));

        // ✅ Ensure a key environment variable is loaded (e.g., APP_ENV)
        $this->assertNotNull(
            $env->get('APP_ENV'),
            '❌ Expected environment variable APP_ENV not loaded or missing.'
        );
    }

    /**
     * 🧩 **Test Database Resolver Initialization**
     *
     * Confirms that the {@see DatabaseResolver} class can be instantiated successfully
     * using a valid {@see EnvironmentConfig} instance.
     *
     * @throws Exception
     *
     * @return void
     */
    public function testDatabaseResolverExists(): void
    {
        $resolver = new DatabaseResolver(new EnvironmentConfig(dirname(__DIR__, 3)));

        // ✅ Verify correct resolver instance creation
        $this->assertInstanceOf(
            DatabaseResolver::class,
            $resolver,
            '❌ Failed to instantiate DatabaseResolver.'
        );
    }
}
