<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 22:24
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Lock;

use ErrorException;
use Maatify\PsrLogger\Traits\LoggerContextTrait;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class FileCronLock
 *
 * Implements a simple file-based locking mechanism for Cron jobs.
 * Ensures that the same scheduled task does not execute multiple instances concurrently.
 *
 * 🧩 Features:
 * - Uses a lock file on the filesystem.
 * - Automatically expires after a configurable TTL (default: 5 minutes).
 * - Includes safe directory creation and error-logging via PSR-3.
 *
 * Typical usage:
 * ```php
 * $lock = new FileCronLock('/tmp/maatify/cron/myjob.lock', ttl: 600);
 * if (! $lock->acquire()) {
 *     echo "Job already running. Exiting.";
 *     exit;
 * }
 *
 * // ... your Cron logic ...
 *
 * $lock->release();
 * ```
 */
final class FileLockManager implements LockInterface
{
    use LoggerContextTrait;
    private string $lockFile;
    private int $ttl;

    /**
     * @param string                $lockFile  Absolute path to the lock file.
     * @param int                   $ttl       Time-to-live for the lock in seconds (default: 300).
     * @param LoggerInterface|null  $logger    Optional PSR-3 logger instance.
     */
    public function __construct(string $lockFile, int $ttl = 300, ?LoggerInterface $logger = null)
    {
        $this->lockFile = $lockFile;
        $this->ttl = $ttl;

        // 🧠 Initialize logger (custom or default context)
        if ($logger !== null) {
            $this->logger = $logger;
        } else {
            $this->initLogger('lock/lock');
        }

        $dir = dirname($this->lockFile);
        if (!is_dir($dir)) {
            try {
                mkdir($dir, 0777, true);
            } catch (Throwable $e) {
                $this->logger->error('Failed to create lock directory', [
                    'path' => $dir,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Attempt to acquire the Cron lock.
     *
     * Creates a lock file if it doesn't exist or has expired.
     * If the lock file is still valid (based on TTL), it denies re-entry.
     *
     * @return bool True if the lock was acquired successfully, false otherwise.
     */
    public function acquire(): bool
    {
        try {
            if (file_exists($this->lockFile)) {
                $lockAge = time() - filemtime($this->lockFile);
                if ($lockAge < $this->ttl) {
                    // Lock still valid, prevent re-entry
                    return false;
                }
                unlink($this->lockFile); // stale lock
            }

            $result = file_put_contents($this->lockFile, (string) time(), LOCK_EX);
            if ($result === false) {
                throw new ErrorException('Failed to create lock file: ' . $this->lockFile);
            }

            return true;
        } catch (Throwable $e) {
            $this->logger->error('CronLock acquire error', [
                'file' => $this->lockFile,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Check if a valid lock currently exists.
     *
     * @return bool True if a lock file exists and has not expired, false otherwise.
     */
    public function isLocked(): bool
    {
        if (!file_exists($this->lockFile)) {
            return false;
        }

        $lockAge = time() - filemtime($this->lockFile);
        return $lockAge < $this->ttl;
    }

    /**
     * Manually release the lock.
     *
     * Removes the lock file if it exists.
     * Any errors during deletion are logged but do not throw exceptions.
     */
    public function release(): void
    {
        try {
            if (file_exists($this->lockFile)) {
                unlink($this->lockFile);
            }
        } catch (Throwable $e) {
            $this->logger->error('CronLock release error', [
                'file' => $this->lockFile,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
