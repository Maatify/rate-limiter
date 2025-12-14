<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 16:47
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

use Maatify\Bootstrap\Core\{IntegrationManager, IntegrationValidator};
use PHPUnit\Framework\TestCase;

/**
 * 🧪 Class IntegrationTest
 *
 * 🧩 Purpose:
 * Validates cross-library integration and environment readiness
 * for all Maatify components relying on the Bootstrap system.
 *
 * ✅ Verifies:
 * - Libraries can be successfully registered through {@see IntegrationManager}.
 * - Environment variables and timezone are properly loaded.
 * - Error handlers and system integrations are functional.
 *
 * ⚙️ Example:
 * ```bash
 * vendor/bin/phpunit --filter IntegrationTest
 * ```
 */
final class IntegrationTest extends TestCase
{
    /**
     * 🎯 Ensures multiple libraries can integrate seamlessly with Bootstrap.
     *
     * This test registers several Maatify libraries and then validates
     * that environment, timezone, and handler diagnostics all pass.
     *
     * ✅ Steps:
     * 1. Register mock libraries via {@see IntegrationManager::register()}.
     * 2. Retrieve registered list and verify expected entries.
     * 3. Run {@see IntegrationValidator::diagnostics()} and confirm system readiness.
     */
    public function testIntegrationAcrossLibraries(): void
    {
        // 🔹 Register multiple Maatify libraries for bootstrap validation
        IntegrationManager::register('maatify/data-adapters', __DIR__ . '/../');
        IntegrationManager::register('maatify/rate-limiter', __DIR__ . '/../');
        IntegrationManager::register('maatify/security-guard', __DIR__ . '/../');

        // ✅ Verify that registration succeeded
        $libs = IntegrationManager::registered();
        $this->assertContains('maatify/data-adapters', $libs, 'Data adapters library should be registered.');
        $this->assertContains('maatify/security-guard', $libs, 'Security guard library should be registered.');

        // 🧠 Run diagnostic checks
        $diag = IntegrationValidator::diagnostics();

        // ✅ Verify environment setup correctness
        $this->assertTrue($diag['env_loaded'], 'Environment variables should be loaded.');
        $this->assertNotEmpty($diag['timezone'], 'Timezone should be properly set.');
        $this->assertTrue($diag['handlers_ok'], 'Error handlers should be functional.');
    }
}
