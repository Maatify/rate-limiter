<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-09 00:14
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
 * 🧪 **Class MockSecurityGuardIntegrationTest**
 *
 * 🎯 **Purpose:**
 * Validates the mock-level integration between the {@see DatabaseResolver}
 * and the MySQL adapter implementation without requiring a real database connection.
 *
 * 🧠 **Core Validations:**
 * - Ensures the `MySQL` adapter is correctly resolved via the resolver.
 * - Confirms presence of essential connection methods:
 *   - `connect()` → establishes database connection logic.
 *   - `getConnection()` → retrieves underlying connection instance.
 * - Acts as a CI/CD-safe test ensuring structural and interface compliance.
 *
 * 🧩 **Use Case:**
 * This mock test helps guarantee that the `maatify/security-guard`
 * and related modules relying on MySQL adapters remain compatible
 * even when no database is available.
 *
 * ✅ **Example Run:**
 * ```bash
 * APP_ENV=testing vendor/bin/phpunit --filter MockSecurityGuardIntegrationTest
 * ```
 */
final class MockSecurityGuardIntegrationTest extends TestCase
{
    /**
     * 🧩 **Test MySQL Mock Integration**
     *
     * Verifies that the MySQL adapter can be resolved from the {@see DatabaseResolver}
     * and exposes the necessary interface methods used throughout the ecosystem.
     *
     * ⚙️ **Validation Steps:**
     * 1️⃣ Load environment configuration.
     * 2️⃣ Resolve the MySQL adapter via {@see DatabaseResolver}.
     * 3️⃣ Check for existence of critical methods (`connect`, `getConnection`).
     *
     * @throws Exception If environment or adapter resolution fails.
     *
     * @return void
     */
    public function testMySQLMockIntegration(): void
    {
        // 🧱 Arrange: Initialize environment configuration and resolver
        $config = new EnvironmentConfig(__DIR__ . '/../../');
        $resolver = new DatabaseResolver($config);

        // ⚙️ Act: Resolve MySQL adapter without auto-connection
        $mysql = $resolver->resolve('MYSQL');

        // ✅ Assert: Validate structural integrity and method availability
        $this->assertTrue(
            method_exists($mysql, 'connect'),
            '❌ Expected method connect() not found on MySQL adapter.'
        );

        $this->assertTrue(
            method_exists($mysql, 'getConnection'),
            '❌ Expected method getConnection() not found on MySQL adapter.'
        );
    }
}
