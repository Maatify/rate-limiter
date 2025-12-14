<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim
 * @since       2025-11-09
 * @link        https://github.com/Maatify/common
 */

declare(strict_types=1);

namespace Maatify\Common\Lock;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\PsrLogger\Traits\LoggerContextTrait;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * ⚙️ **Class HybridLockManager**
 *
 * 🎯 **Purpose:**
 * Provides a smart, unified, and fault-tolerant lock manager that automatically
 * selects the best locking backend based on runtime availability.
 *
 * 🧠 **Core Concept:**
 * - Prefer **Redis-based distributed locks** when a healthy adapter is detected.
 * - Fall back to **file-based local locks** automatically when Redis is offline.
 * - Behaves transparently for callers; no code change is required between modes.
 *
 * 🧩 **Key Features**
 * - Hybrid auto-detection between Redis and File locks.
 * - Full PSR-3 logging for observability and debugging.
 * - Two operational modes:
 *   - `LockModeEnum::EXECUTION` → Non-blocking (skip if locked).
 *   - `LockModeEnum::QUEUE` → Blocking (wait until available).
 *
 * ✅ **Example Usage**
 * ```php
 * use Maatify\Common\Lock\HybridLockManager;
 * use Maatify\Common\Lock\LockModeEnum;
 * use Maatify\Common\Adapters\RedisAdapter;
 *
 * $adapter = new RedisAdapter($config);
 * $adapter->connect();
 *
 * $lock = new HybridLockManager('generate_reports', LockModeEnum::QUEUE, adapter: $adapter);
 * $lock->run(function () {
 *     echo "Executing safely under hybrid lock.";
 * });
 * ```
 *
 */
final class HybridLockManager implements LockInterface
{
    use LoggerContextTrait;

    /**
     * @var LockInterface Active lock driver instance (RedisLockManager or FileLockManager).
     */
    private LockInterface $driver;

    /**
     * @var LockModeEnum Execution mode — determines whether the lock waits or skips.
     */
    private LockModeEnum $mode;

    /**
     * 🧩 **Constructor**
     *
     * Initializes the hybrid lock manager and selects the optimal locking mechanism.
     * If a healthy Redis adapter is available, it is used; otherwise a file lock is created.
     *
     * @param string                $key      Unique lock identifier.
     * @param LockModeEnum          $mode     Determines blocking or non-blocking mode.
     * @param int                   $ttl      Time-to-live (seconds) before lock auto-expires.
     * @param AdapterInterface|null $adapter  Optional Redis adapter (dependency-injected).
     * @param LoggerInterface|null  $logger   Optional PSR-3 logger for diagnostics.
     */
    public function __construct(
        string $key,
        LockModeEnum $mode = LockModeEnum::EXECUTION,
        int $ttl = 300,
        ?AdapterInterface $adapter = null,
        ?LoggerInterface $logger = null
    ) {
        $this->mode = $mode;

        // 🧠 Initialize logger (custom or default context)
        if ($logger !== null) {
            $this->logger = $logger;
        } else {
            $this->initLogger('lock/hybrid');
        }


        // 🧠 Prefer Redis if adapter is valid and responsive
        if ($adapter !== null && $this->canUseAdapter($adapter)) {
            $this->driver = new RedisLockManager($key, $adapter, $ttl, $this->logger);
            $this->logger->info("HybridLockManager initialized using Redis adapter for '$key'");
        } else {
            // 🧱 Fallback: use file-based lock when Redis unavailable
            $lockFile = sys_get_temp_dir() . "/maatify/locks/$key.lock";
            $this->driver = new FileLockManager($lockFile, $ttl, $this->logger);
            $this->logger->info("HybridLockManager fallback to FileLockManager for '$key'");
        }
    }

    // -----------------------------------------------------------
    // 🔹 Public Lock Interface Implementation
    // -----------------------------------------------------------

    /**
     * 🔏 **Acquire the underlying lock (Redis or File).**
     *
     * Attempts to obtain an exclusive lock.
     *
     * @return bool Returns `true` if the lock was successfully acquired, otherwise `false`.
     */
    public function acquire(): bool
    {
        return $this->driver->acquire();
    }

    /**
     * 🔍 **Check whether the current lock is active.**
     *
     * Useful for verifying lock state before performing guarded operations.
     *
     * @return bool Returns `true` if the lock is currently held, otherwise `false`.
     */
    public function isLocked(): bool
    {
        return $this->driver->isLocked();
    }

    /**
     * 🔓 **Release the active lock (any driver).**
     *
     * Ensures that the lock resource is freed.
     * Safe to call even if the lock has already expired.
     *
     * @return void
     */
    public function release(): void
    {
        $this->driver->release();
    }

    // -----------------------------------------------------------
    // 🔹 Queue Mode Helpers
    // -----------------------------------------------------------

    /**
     * 🕓 **Wait until the lock becomes available and acquire it.**
     *
     * Used in blocking (QUEUE) mode. Repeatedly attempts to acquire the lock until success.
     *
     * @param int $retryDelay Delay between retries in microseconds.
     *                        Default: `500_000` (0.5 s).
     *
     * @return void
     */
    public function waitAndAcquire(int $retryDelay = 500_000): void
    {
        while (! $this->acquire()) {
            // ⏳ Sleep briefly before retrying to reduce contention
            usleep($retryDelay);
        }
    }

    /**
     * 🚀 **Execute a callback under exclusive lock protection.**
     *
     * Handles acquiring, executing, and releasing automatically.
     * - In `QUEUE` mode → waits until lock is free.
     * - In `EXECUTION` mode → skips silently if lock is already held.
     *
     * @param callable $callback   Function or closure to execute under lock.
     * @param int      $retryDelay Retry delay in microseconds for QUEUE mode.
     *
     * @return void
     */
    public function run(callable $callback, int $retryDelay = 500_000): void
    {
        if ($this->mode === LockModeEnum::QUEUE) {
            $this->waitAndAcquire($retryDelay);
        } elseif (! $this->acquire()) {
            // 🚫 Skip silently in non-blocking mode (EXECUTION)
            return;
        }

        try {
            $callback();
        } finally {
            $this->release();
        }
    }

    // -----------------------------------------------------------
    // 🔹 Adapter Validation Logic
    // -----------------------------------------------------------

    /**
     * 🧠 **Determine if the given Redis adapter is healthy and usable.**
     *
     * Checks connectivity and performs a lightweight health check before using Redis.
     * Logs a warning and returns `false` if any error occurs.
     *
     * @param AdapterInterface $adapter The Redis-compatible adapter.
     *
     * @return bool `true` if the adapter connection is healthy and operational; otherwise `false`.
     */
    private function canUseAdapter(AdapterInterface $adapter): bool
    {
        try {
            if (! $adapter->isConnected()) {
                $adapter->connect();
            }

            return $adapter->healthCheck();
        } catch (Throwable $e) {
            $this->logger->warning('Adapter health check failed — falling back to FileLockManager.', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
