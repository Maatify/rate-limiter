<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-05
 * Time: 22:26
 * Project: maatify:common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Lock;

/**
 * Interface CronLockInterface
 *
 * Defines a contract for implementing locking mechanisms in scheduled Cron jobs.
 * The goal is to ensure that a Cron task does not run multiple instances simultaneously,
 * especially in distributed or multi-threaded environments.
 *
 * Implementations may use:
 * - Filesystem-based locks
 * - Redis locks
 * - Database row locks
 * - OS-level semaphores
 *
 * Example usage:
 * ```php
 * $lock = new FileCronLock('/tmp/myjob.lock');
 * if (! $lock->acquire()) {
 *     echo "Another instance is already running.";
 *     exit;
 * }
 *
 * // ... execute job ...
 *
 * $lock->release();
 * ```
 */
interface LockInterface
{
    /**
     * Attempt to acquire the lock for the current Cron job.
     *
     * Should create a new lock (e.g., file, Redis key, DB record)
     * and return `true` only if the lock was obtained successfully.
     *
     * @return bool True if lock acquired successfully, false otherwise.
     */
    public function acquire(): bool;

    /**
     * Check whether the Cron job is currently locked (already running).
     *
     * Implementations should verify if the lock resource still exists
     * and has not expired (depending on the lock strategy).
     *
     * @return bool True if a valid lock is active, false otherwise.
     */
    public function isLocked(): bool;

    /**
     * Manually release the acquired lock.
     *
     * Should safely remove or expire the lock resource.
     * Always call this at the end of a successful job execution.
     */
    public function release(): void;
}
