<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 23:38
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Lock;

/**
 * Class LockCleaner
 *
 * 🧹 Utility class for cleaning up stale lock files.
 *
 * This helper is typically used with {@see FileLockManager} or {@see HybridLockManager}
 * to ensure no leftover lock files block execution after crashes or timeouts.
 *
 * Example:
 * ```php
 * LockCleaner::cleanOldLocks(sys_get_temp_dir() . '/maatify/locks', 900);
 * ```
 */
final class LockCleaner
{
    /**
     * Delete old `.lock` files exceeding the specified age.
     *
     * @param string $path    Directory containing lock files.
     * @param int    $maxAge  Maximum allowed age (in seconds) before deletion. Default: 600 (10 minutes).
     *
     * @return void
     */
    public static function cleanOldLocks(string $path, int $maxAge = 600): void
    {
        $files = glob($path . '/*.lock') ?: [];

        foreach ($files as $file) {
            if (time() - filemtime($file) > $maxAge) {
                @unlink($file);
            }
        }
    }
}
