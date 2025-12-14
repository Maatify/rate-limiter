<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 16:20
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

use Maatify\Bootstrap\Helpers\{EnvHelper, PathHelper};
use PHPUnit\Framework\TestCase;

/**
 * 🧪 Class HelpersTest
 *
 * 🧩 Purpose:
 * Unit tests for the core helper classes of `maatify/bootstrap` —
 * specifically {@see EnvHelper} and {@see PathHelper}.
 *
 * ✅ Verifies:
 * - Environment variable access and caching.
 * - Path resolution and consistency across platforms.
 *
 * ⚙️ Example:
 * ```bash
 * vendor/bin/phpunit --filter HelpersTest
 * ```
 */
final class HelpersTest extends TestCase
{
    /**
     * 🎯 Ensures that EnvHelper returns and caches expected environment values.
     *
     * Verifies:
     * - Retrieval from `$_ENV`.
     * - Existence detection via `has()`.
     * - Value consistency between multiple calls (cache test).
     */
    public function testEnvHelperReturnsExpectedValue(): void
    {
        $_ENV['APP_MODE'] = 'testing';

        $this->assertEquals(
            'testing',
            EnvHelper::get('APP_MODE'),
            'EnvHelper::get() should return the correct environment value.'
        );

        $this->assertTrue(
            EnvHelper::has('APP_MODE'),
            'EnvHelper::has() should return true for an existing variable.'
        );

        $this->assertArrayHasKey(
            'APP_MODE',
            EnvHelper::cached(),
            'EnvHelper::cached() should contain the retrieved variable.'
        );
    }

    /**
     * 🧭 Ensures that PathHelper correctly resolves and constructs consistent base paths.
     *
     * Verifies:
     * - Base directory exists.
     * - Generated path contains project name (maatify-bootstrap).
     * - Joins paths correctly with consistent formatting.
     */
    public function testPathHelperBuildsConsistentPaths(): void
    {
        $base = PathHelper::base();

        $this->assertDirectoryExists(
            $base,
            'PathHelper::base() should point to a valid directory.'
        );

        $this->assertStringContainsString(
            'bootstrap',
            $base,
            'Base path should contain the project directory name.'
        );

        $logPath = PathHelper::logs('test.log');
        $this->assertStringEndsWith(
            'storage/logs/test.log',
            $logPath,
            'PathHelper::logs() should correctly join the subpath.'
        );
    }
}
