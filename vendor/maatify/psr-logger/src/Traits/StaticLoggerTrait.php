<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/psr-logger
 * @Project     maatify:psr-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 21:41
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/psr-logger  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\PsrLogger\Traits;

use Maatify\PsrLogger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * 🧩 **Trait StaticLoggerTrait**
 *
 * 🎯 **Purpose:**
 * Provides an elegant, framework-agnostic way to access PSR-3 compliant loggers
 * from **static contexts** (e.g., utility classes, facades, or global initializers)
 * without requiring instance creation.
 *
 * 🧠 **Key Features:**
 * - Offers a static `getLogger()` method for quick logger retrieval.
 * - Internally leverages {@see LoggerFactory::create()} to ensure
 *   consistent configuration and directory structure across projects.
 * - Returns a fully PSR-3 compliant {@see LoggerInterface} logger.
 * - Perfect for static initialization flows, global event hooks, or
 *   bootstrap operations where dependency injection is unavailable.
 *
 * ✅ **Example Usage:**
 * ```php
 * final class Bootstrap
 * {
 *     use StaticLoggerTrait;
 *
 *     public static function init(): void
 *     {
 *         $logger = self::getLogger('bootstrap');
 *         $logger->info('✅ Bootstrap initialized successfully.');
 *     }
 * }
 * ```
 */
trait StaticLoggerTrait
{
    /**
     * ⚙️ **Get Static Logger**
     *
     * Instantiates a PSR-3 compliant logger for use within a static context.
     * The `$context` parameter defines the log folder or namespace identifier
     * under which log files are organized.
     *
     * @param string $context Optional logging context or subdirectory name.
     *                        Default: `"app"`.
     *
     * @return LoggerInterface Returns a PSR-3 logger instance ready for use.
     *
     * ✅ **Example:**
     * ```php
     * $logger = self::getLogger('system/cron');
     * $logger->error('Failed to start cron job.');
     * ```
     */
    protected static function getLogger(string $context = 'app'): LoggerInterface
    {
        // 🧩 Delegate logger creation to LoggerFactory for standardized setup
        return LoggerFactory::create($context);
    }
}
