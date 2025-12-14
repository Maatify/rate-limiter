<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 21:15
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Diagnostics;

use Exception;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use Maatify\DataAdapters\Diagnostics\DiagnosticService;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class DiagnosticServiceTest**
 *
 * 🧩 **Purpose:**
 * Verifies that {@see DiagnosticService} functions correctly in a real environment
 * by registering multiple adapters and ensuring diagnostic collection produces
 * valid structured output.
 *
 * ✅ **Test Goals:**
 * - Confirm that `collect()` returns an array structure.
 * - Ensure each diagnostic entry includes the `"adapter"` key.
 * - Validate adapter registration (Redis, Mongo, MySQL) using the resolver.
 *
 * 🧠 **Why This Matters:**
 * Ensures that the integration between configuration, adapter resolver, and
 * diagnostics layer behaves predictably in test or production environments.
 *
 * 🧰 **Typical Usage:**
 * Run this test after configuration setup to verify correct `.env` variable loading
 * and that the diagnostic service operates without runtime exceptions.
 *
 * ✅ **Example Run:**
 * ```bash
 * APP_ENV=testing vendor/bin/phpunit --filter DiagnosticServiceTest
 * ```
 */
final class DiagnosticServiceTest extends TestCase
{
    /**
     * 🧩 **Test Diagnostic Array Structure**
     *
     * Validates that {@see DiagnosticService::collect()} returns a structured array
     * containing the required diagnostic keys and data for registered adapters.
     *
     * @throws Exception
     */
    public function testDiagnosticsReturnsArray(): void
    {
        // ⚙️ Initialize environment configuration and database resolver
        $config   = new EnvironmentConfig(dirname(__DIR__, 3));
        $resolver = new DatabaseResolver($config);

        // 🧠 Create diagnostic service and register adapter types
        $service = new DiagnosticService($config, $resolver);
        $service->register([
            'redis',
            'mongo',
            'mysql',
        ]);

        // 🧩 Collect diagnostics
        $result = $service->collect();

        // ✅ Assertions: Ensure structure and key existence
        $this->assertIsArray(
            $result,
            '❌ Expected DiagnosticService::collect() to return an array.'
        );

        $this->assertArrayHasKey(
            'adapter',
            $result[0],
            '❌ Expected key "adapter" not found in diagnostics result.'
        );
    }
}
