<?php
/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/bootstrap
 * @Project     maatify:bootstrap
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 15:57
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/bootstrap  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Bootstrap\Core;

use Psr\Log\LoggerInterface;
use Throwable;

/**
 * ⚙️ Class ErrorHandler
 *
 * Handles PHP errors and exceptions in a PSR-3-compatible way.
 */
final class ErrorHandler
{
    public function __construct(private readonly ?LoggerInterface $logger = null)
    {
    }

    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    public function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        $message = "⚠️ [PHP Error] {$errstr} in {$errfile}:{$errline}";
        $this->logger?->error($message);

        return false;
    }

    public function handleException(Throwable $e): void
    {
        $this->logger?->critical("💥 Unhandled Exception: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
        fwrite(STDERR, "💥 Exception: {$e->getMessage()}\n");
    }
}
