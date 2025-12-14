<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/psr-logger
 * @Project     maatify:psr-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 21:46
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/psr-logger  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\PsrLogger\Tests\Unit;

use Maatify\PsrLogger\Tests\Mocks\MockStaticLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * 🧩 **Test Class StaticLoggerTraitTest**
 *
 * 🎯 **Purpose:**
 * Ensures {@see StaticLoggerTrait} returns valid PSR-3 logger instances.
 */
final class StaticLoggerTraitTest extends TestCase
{
    public function testLoggerInstanceIsPsrCompliant(): void
    {
        $logger = MockStaticLogger::logger('unit');
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }

    public function testDifferentContextsReturnDistinctLoggers(): void
    {
        $loggerA = MockStaticLogger::logger('contextA');
        $loggerB = MockStaticLogger::logger('contextB');

        $this->assertNotSame($loggerA, $loggerB);
    }

    public function testLoggerCreationIsStable(): void
    {
        $this->expectNotToPerformAssertions();

        try {
            $logger = MockStaticLogger::logger('stability');
            $logger->info('StaticLoggerTrait creation successful');
        } catch (\Throwable $e) {
            $this->fail("Logger creation failed: {$e->getMessage()}");
        }
    }
}
