<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/psr-logger
 * @Project     maatify:psr-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 21:45
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/psr-logger  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\PsrLogger\Tests\Mocks;

use Maatify\PsrLogger\Traits\StaticLoggerTrait;
use Psr\Log\LoggerInterface;

/**
 * 🧩 **Class MockStaticLogger**
 *
 * Used for testing {@see StaticLoggerTrait}.
 * Provides a static access method to retrieve logger instances.
 */
final class MockStaticLogger
{
    use StaticLoggerTrait;

    public static function logger(string $context = 'test'): LoggerInterface
    {
        return self::getLogger($context);
    }
}
