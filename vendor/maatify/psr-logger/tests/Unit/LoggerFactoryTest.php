<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/psr-logger
 * @Project     maatify:psr-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 10:12
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/psr-logger  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\PsrLogger\Tests\Unit;

use Maatify\PsrLogger\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * 🧪 **LoggerFactoryTest**
 *
 * ✅ Ensures `LoggerFactory::create()` correctly produces a PSR-compliant logger instance
 * and writes log files to the configured path.
 *
 * This test:
 * - Verifies the returned object implements `LoggerInterface`.
 * - Confirms log file creation and content integrity.
 * - Cleans up temporary log files after test execution.
 *
 * @covers \Maatify\PsrLogger\LoggerFactory
 */
final class LoggerFactoryTest extends TestCase
{
    /** @var string Temporary log directory for isolated testing. */
    private string $logPath;

    /**
     * ⚙️ Prepares test environment by creating a temporary log directory
     * and setting the `LOG_PATH` environment variable.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->logPath = sys_get_temp_dir() . '/maatify-psr-logger-tests';

        if (! is_dir($this->logPath)) {
            mkdir($this->logPath, 0777, true);
        }

        // Simulate environment variable used by LoggerFactory
        putenv('LOG_PATH=' . $this->logPath);
    }

    /**
     * 🧹 Cleans up all generated log files and directories after test completion.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $files = glob($this->logPath . '/*');

        foreach ($files as $f) {
            if (is_file($f)) {
                @unlink($f);
            }

            if (is_dir($f)) {
                foreach (glob($f . '/*', GLOB_MARK) as $s) {
                    if (is_file($s)) {
                        @unlink($s);
                    }
                }
                @rmdir($f);
            }
        }

        @rmdir($this->logPath);
    }

    /**
     * 🧩 **Test: PSR Logger creation and log file generation**
     *
     * Verifies that `LoggerFactory::create()`:
     * - Returns a valid PSR-3 LoggerInterface instance.
     * - Writes the expected log message to a file under `LOG_PATH`.
     *
     * @return void
     */
    public function testCreateReturnsPsrLoggerAndWritesFile(): void
    {
        $logger = LoggerFactory::create('tests/logger-test');

        $this->assertInstanceOf(
            LoggerInterface::class,
            $logger,
            'Expected LoggerFactory::create() to return a PSR LoggerInterface instance.'
        );

        // Write a sample log entry
        $logger->info('unit test message', ['k' => 'v']);
        clearstatcache();

        // Traverse LOG_PATH recursively to locate the log file
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->logPath)
        );

        $found = false;

        foreach ($iterator as $file) {
            if ($file->isFile() && str_contains($file->getFilename(), 'logger-test')) {
                $found = true;
                $contents = file_get_contents($file->getPathname());

                $this->assertStringContainsString(
                    'unit test message',
                    $contents,
                    'Expected log file to contain the written message.'
                );
                break;
            }
        }

        $this->assertTrue($found, 'Expected a log file for "logger-test" to be created.');
    }
}
