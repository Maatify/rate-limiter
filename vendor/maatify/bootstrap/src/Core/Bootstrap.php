<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 15:55
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Bootstrap\Core;

use Exception;
use Psr\Log\LoggerInterface;
use Maatify\PsrLogger\LoggerFactory;

/**
 * 🧩 Class Bootstrap
 *
 * Provides unified initialization for all Maatify projects:
 *  - Loads environment configuration
 *  - Sets the default time zone
 *  - Registers error/exception handlers
 *  - Prevents multiple initialization
 */
final class Bootstrap
{
    private static bool $initialized = false;
    private static ?LoggerInterface $logger = null;

    public static function init(string $basePath): void
    {
        if (self::$initialized) {
            return; // idempotent safety
        }

        try {
            // 1️⃣ Load environment
            (new EnvironmentLoader($basePath))->load();

            // 2️⃣ Initialize logger (optional)
            self::$logger = LoggerFactory::create('bootstrap');

            // 3️⃣ Set error & exception handlers
            (new ErrorHandler(self::$logger))->register();

            // 4️⃣ Mark initialized
            self::$initialized = true;
        } catch (Exception $e) {
            echo "❌ Bootstrap initialization failed: {$e->getMessage()}\n";
            throw $e;
        }
    }

    public static function logger(): ?LoggerInterface
    {
        return self::$logger;
    }

    public static function isInitialized(): bool
    {
        return self::$initialized;
    }
}
