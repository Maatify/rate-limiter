<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 21:48
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Bootstrap\Tests\Integration;

use Maatify\Bootstrap\Core\Bootstrap;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * 🧩 **Class BootstrapLoggerIntegrationTest**
 *
 * 🎯 **Purpose:**
 * Validates the integration between {@see Bootstrap} (core system initializer)
 * and {@see maatify/psr-logger}, ensuring proper logger setup and behavior.
 *
 * 🧠 **Key Test Scenarios:**
 * - ✅ Confirms that `Bootstrap::init()` initializes successfully.
 * - ✅ Verifies that `Bootstrap::logger()` returns a PSR-3 compliant logger.
 * - ✅ Ensures multiple initialization calls remain idempotent.
 * - ✅ Confirms that logger operations (like `info()`) execute without errors.
 *
 * 🧪 **Test Context:**
 * These are integration-level tests that interact with filesystem paths
 * and logging subsystems, designed to ensure stable environment initialization
 * within the Maatify Bootstrap ecosystem.
 */
final class BootstrapLoggerIntegrationTest extends TestCase
{
    /**
     * @var string Temporary directory for test operations.
     */
    private string $basePath;

    /**
     * ⚙️ **Setup Environment**
     *
     * Creates a temporary directory for log and bootstrap initialization.
     * Executed before each test method.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->basePath = sys_get_temp_dir() . '/maatify-bootstrap-tests';
        if (! is_dir($this->basePath)) {
            mkdir($this->basePath, 0777, true);
        }
    }

    /**
     * 🧩 **Test Bootstrap Initialization**
     *
     * Ensures that calling `Bootstrap::init()` properly initializes
     * the system environment and sets up the logger.
     *
     * @return void
     */
    public function testBootstrapInitializesLogger(): void
    {
        Bootstrap::init($this->basePath);

        $this->assertTrue(
            Bootstrap::isInitialized(),
            'Bootstrap must be initialized after calling init().'
        );
    }

    /**
     * 🔍 **Test PSR Logger Availability**
     *
     * Ensures that `Bootstrap::logger()` returns a valid instance
     * of {@see LoggerInterface}, and that logging operations
     * can be executed without exceptions.
     *
     * @return void
     */
    public function testLoggerIsAvailableAndPsrCompliant(): void
    {
        Bootstrap::init($this->basePath);

        $logger = Bootstrap::logger();

        $this->assertInstanceOf(
            LoggerInterface::class,
            $logger,
            'Bootstrap::logger() should return a PSR-3 logger instance.'
        );

        // 🧠 Try logging a message — should not throw any errors.
        $logger->info('Integration Test: Bootstrap logger initialized successfully.');
        $this->assertTrue(true, 'Logger should log without throwing exceptions.');
    }

    /**
     * 🔁 **Test Idempotent Initialization**
     *
     * Confirms that multiple calls to `Bootstrap::init()` do not cause reinitialization
     * issues or exceptions, maintaining consistent state.
     *
     * @return void
     */
    public function testMultipleInitCallsAreIdempotent(): void
    {
        Bootstrap::init($this->basePath);
        Bootstrap::init($this->basePath);

        $this->assertTrue(
            Bootstrap::isInitialized(),
            'Bootstrap initialization should be idempotent.'
        );
    }

    /**
     * 🧹 **Teardown Environment**
     *
     * Cleans up any temporary files or directories created during testing.
     * Ensures isolation between tests.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        if (is_dir($this->basePath)) {
            foreach (glob("{$this->basePath}/*") as $file) {
                @unlink($file);
            }
            @rmdir($this->basePath);
        }
    }
}
