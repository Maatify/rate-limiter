<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-09 00:15
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Integration;

use Exception;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class MockMongoActivityIntegrationTest**
 *
 * 🎯 **Purpose:**
 * Verifies the mock-level integration between {@see DatabaseResolver}
 * and the Mongo adapter implementation without requiring an active MongoDB instance.
 *
 * 🧠 **Key Verifications:**
 * - Confirms that the Mongo adapter can be resolved successfully.
 * - Ensures that it exposes core operational methods:
 *   - `connect()` → establishes the database connection.
 *   - `healthCheck()` → validates connection health.
 *
 * 🧩 **Use Case:**
 * This mock integration test serves as a **CI/CD-safe adapter readiness check**,
 * validating adapter class structure and integration consistency independently
 * of external dependencies.
 *
 * ✅ **Example Run:**
 * ```bash
 * APP_ENV=testing vendor/bin/phpunit --filter MockMongoActivityIntegrationTest
 * ```
 */
final class MockMongoActivityIntegrationTest extends TestCase
{
    /**
     * 🧩 **Test: Mongo Adapter Structural Integrity**
     *
     * Ensures that the Mongo adapter can be instantiated and exposes
     * the expected methods essential for runtime interaction.
     *
     * ⚙️ **What It Does:**
     * 1️⃣ Loads environment configuration.
     * 2️⃣ Resolves the Mongo adapter via {@see DatabaseResolver}.
     * 3️⃣ Validates presence of key adapter methods.
     *
     * @throws Exception If the resolver or environment initialization fails.
     *
     * @return void
     */
    public function testMongoMockIntegration(): void
    {
        // 🧱 Arrange: Setup configuration and resolver
        $config = new EnvironmentConfig(__DIR__ . '/../../');
        $resolver = new DatabaseResolver($config);

        // ⚙️ Act: Resolve the Mongo adapter
        $mongo = $resolver->resolve('MONGO');

        // ✅ Assert: Verify essential methods exist
        $this->assertTrue(
            method_exists($mongo, 'connect'),
            '❌ Expected method connect() not found on Mongo adapter.'
        );

        $this->assertTrue(
            method_exists($mongo, 'healthCheck'),
            '❌ Expected method healthCheck() not found on Mongo adapter.'
        );
    }
}
