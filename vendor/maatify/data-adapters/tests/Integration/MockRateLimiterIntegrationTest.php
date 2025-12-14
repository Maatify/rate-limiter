<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-09 00:13
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
 * 🧪 **Class MockRateLimiterIntegrationTest**
 *
 * 🎯 **Purpose:**
 * Validates the structural and interface-level integrity of the Redis adapter
 * resolved via {@see DatabaseResolver}, without initiating a real Redis connection.
 *
 * 🧠 **Core Verifications:**
 * - Confirms that the Redis adapter can be resolved successfully.
 * - Verifies presence of the critical adapter methods:
 *   - `connect()` — to establish connection logic.
 *   - `healthCheck()` — to verify connection health status.
 * - Ensures compatibility with the `maatify/rate-limiter` integration layer.
 *
 * 🧩 **Context:**
 * Used in CI/CD pipelines and automated tests where Redis connectivity
 * is mocked or unavailable, ensuring the adapter class remains
 * autoloadable and API-compliant.
 *
 * ✅ **Example Run:**
 * ```bash
 * APP_ENV=testing vendor/bin/phpunit --filter MockRateLimiterIntegrationTest
 * ```
 */
final class MockRateLimiterIntegrationTest extends TestCase
{
    /**
     * 🧩 **Test: Redis Mock Integration**
     *
     * Ensures the Redis adapter can be resolved and exposes
     * all required methods necessary for rate-limiting functionality.
     *
     * ⚙️ **What It Validates:**
     * 1️⃣ The Redis adapter can be instantiated through {@see DatabaseResolver}.
     * 2️⃣ The essential methods (`connect` and `healthCheck`) exist.
     * 3️⃣ No live Redis connection is required.
     *
     * @throws Exception If environment loading or adapter resolution fails.
     *
     * @return void
     */
    public function testRedisMockIntegration(): void
    {
        // 🧱 Arrange: Initialize configuration and resolver
        $config = new EnvironmentConfig(__DIR__ . '/../../');
        $resolver = new DatabaseResolver($config);

        // ⚙️ Act: Resolve Redis adapter
        $redis = $resolver->resolve('REDIS');

        // ✅ Assert: Check adapter method availability
        $this->assertTrue(
            method_exists($redis, 'connect'),
            '❌ Expected method connect() not found on Redis adapter.'
        );

        $this->assertTrue(
            method_exists($redis, 'healthCheck'),
            '❌ Expected method healthCheck() not found on Redis adapter.'
        );
    }
}
