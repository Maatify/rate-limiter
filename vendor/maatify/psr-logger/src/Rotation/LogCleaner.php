<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/psr-logger
 * @Project     maatify:psr-logger
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-05 08:36
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/psr-logger  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */


declare(strict_types=1);

namespace Maatify\PsrLogger\Rotation;

use DateTime;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class LogCleaner
 *
 * Responsible for automatically cleaning up old log files based on a configurable
 * retention period. Designed for use in CRON jobs or maintenance scripts.
 *
 * Features:
 *  - Recursively traverses the log directory and deletes files older than a threshold.
 *  - Removes empty subdirectories after cleanup.
 *  - Fully configurable via environment variables:
 *      - `LOG_PATH` → Base path for logs (default: `/storage/logs`)
 *      - `LOG_RETENTION_DAYS` → Number of days to retain logs (default: 7)
 *
 * Example:
 * ```php
 * // In a scheduled cron job (e.g., daily at midnight)
 * Maatify\PsrLogger\Rotation\LogCleaner::cleanOldLogs();
 * ```
 *
 * Log directory structure example:
 * ```
 * storage/logs/
 * ├── 2025/
 * │   ├── 11/
 * │   │   ├── 04/
 * │   │   │   ├── 22/
 * │   │   │   │   └── repositories/offers.log
 * │   │   │   └── 21/
 * │   │   │       └── app.log
 * │   │   └── 05/
 * │   │       └── 08/
 * │   │           └── auth/login.log
 * ```
 *
 * @package Maatify\PsrLogger\Rotation
 */
final class LogCleaner
{
    /**
     * Clean log files older than the retention threshold.
     *
     * This method:
     *  - Reads `LOG_PATH` from environment (defaults to `/storage/logs`).
     *  - Reads `LOG_RETENTION_DAYS` from environment (defaults to 7).
     *  - Recursively walks through all subdirectories.
     *  - Deletes files whose modification time is older than the retention threshold.
     *  - Removes empty directories after file cleanup.
     *
     * Intended to be run periodically (e.g., via cron).
     *
     * @return void
     *
     * @example
     * ```bash
     * # Example cron job (runs daily at 00:00)
     * 0 0 * * * php /var/www/project/bin/clean-logs.php
     * ```
     */
    public static function cleanOldLogs(): void
    {
        $basePath = getenv('LOG_PATH') ?: ($_ENV['LOG_PATH'] ?? __DIR__ . '/../../../storage/logs');
        $retentionDays = (int)(getenv('LOG_RETENTION_DAYS') ?: ($_ENV['LOG_RETENTION_DAYS'] ?? 7));

        if (!is_dir($basePath)) {
            return;
        }

        $now = new DateTime();
        $threshold = $now->modify("-{$retentionDays} days")->getTimestamp();

        // 🔍 Recursively iterate over all log files and folders
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $fileInfo) {
            // 🧹 Delete files older than retention limit
            if ($fileInfo->isFile()) {
                if ($fileInfo->getMTime() < $threshold) {
                    @unlink($fileInfo->getPathname());
                }
            }
            // 🗑️ Remove empty directories after cleanup
            elseif ($fileInfo->isDir()) {
                $files = scandir($fileInfo->getPathname());
                if (count($files) <= 2) {
                    @rmdir($fileInfo->getPathname());
                }
            }
        }
    }
}
