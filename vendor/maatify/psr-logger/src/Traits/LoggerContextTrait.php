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

namespace Maatify\PsrLogger\Traits;

use Maatify\PsrLogger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * 🧩 **Trait LoggerContextTrait**
 *
 * 🎯 **Purpose:**
 * Provides a lightweight, reusable way to initialize a PSR-3 compliant logger
 * in any class — ensuring consistent logging standards across all Maatify projects.
 *
 * 🧠 **Highlights:**
 * - Automatically creates a logger instance via {@see LoggerFactory::create()}.
 * - Supports optional `$context` to categorize or namespace log output.
 * - Exposes `$this->logger` as a PSR-3 compliant logger ready for immediate use.
 * - Encourages unified structured logging throughout the ecosystem.
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\PsrLogger\Traits\LoggerContextTrait;
 *
 * class ExampleService
 * {
 *     use LoggerContextTrait;
 *
 *     public function __construct()
 *     {
 *         $this->initLogger(); // Auto-detect class context
 *     }
 *
 *     public function process(): void
 *     {
 *         $this->logger->info('Processing started.');
 *     }
 * }
 * ```
 */
trait LoggerContextTrait
{
    /**
     * 🧱 **Logger Instance**
     *
     * Holds the initialized PSR-3 logger used by the consuming class.
     * Accessible after calling {@see initLogger()}.
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * ⚙️ **Initialize Logger**
     *
     * Creates a PSR-3 compliant logger instance using the `LoggerFactory`.
     * If `$context` is omitted, it auto-detects one based on the calling class name.
     *
     * @param string|null $context Optional logging context
     *                             (e.g. `"services/payment"` or `"api/v1/auth"`).
     *                             Defines log file path or category.
     *
     * @return LoggerInterface The initialized PSR-3 logger instance.
     *
     * @since  v1.0.0
     * @update v1.0.1  Method now returns the logger instance for direct usage.
     *
     * @see LoggerFactory::create()
     * ✅ **Example 1:**
     * ```php
     *  // Auto context
     * *  $this->initLogger();
     * ```
     *
     * ✅ **Example 2:**
     * ```php
     * $this->initLogger('api/v1/auth');
     * $this->logger->warning('Invalid login attempt.');
     * ```
     *
     * ✅ **Example 3:**
     * ```php
     * $logger = $this->initLogger('services/payment');
     * $logger->error('Payment gateway timeout.');
     * ```
     */
    protected function initLogger(?string $context = null): LoggerInterface
    {
        // 🧩 Create logger using the central LoggerFactory for consistent configuration
        $this->logger = LoggerFactory::create($context);
        return $this->logger;
    }
}
