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
 * 🔐 **Class RedisLockManager**
 *
 * 🎯 **Purpose:**
 * Implements a distributed lock manager using a pluggable {@see AdapterInterface}
 * (e.g., RedisAdapter, PredisAdapter) to ensure mutual exclusion across distributed systems.
 *
 * 🧩 **Core Features:**
 * - **Adapter-driven architecture:** No direct Redis dependency — works with any Redis-compatible adapter.
 * - **Atomic operations:** Uses `SET NX EX` for safe lock acquisition.
 * - **Automatic expiration (TTL):** Prevents deadlocks if the process dies before releasing the lock.
 * - **PSR-3 logging:** Provides traceability for lock lifecycle events.
 *
 * ⚙️ **Example Usage:**
 * ```php
 * use Maatify\Common\Lock\RedisLockManager;
 * use Maatify\Common\Adapters\RedisAdapter;
 *
 * $adapter = new RedisAdapter($config);
 * $adapter->connect();
 *
 * $lock = new RedisLockManager('cleanup', $adapter, ttl: 600);
 * if ($lock->acquire()) {
 *     echo "Lock acquired — safe to proceed.";
 *     $lock->release();
 * }
 * ```
 */
final class RedisLockManager implements LockInterface
{
    use LoggerContextTrait;

    /** @var string Lock key used for Redis operations. */
    private string $key;

    /** @var int Lock TTL in seconds (auto-expiration). */
    private int $ttl;

    /** @var AdapterInterface Adapter providing Redis-like commands. */
    private AdapterInterface $adapter;


    /**
     * 🧩 **Constructor**
     *
     * Initializes the distributed lock manager with an adapter and optional logger.
     * Automatically ensures a connected state before any operation.
     *
     * @param string               $key     Unique lock identifier (e.g., job or process name).
     * @param AdapterInterface     $adapter Adapter implementing Redis-like behavior.
     * @param int                  $ttl     Time-to-live for the lock (default: 300 seconds).
     * @param LoggerInterface|null $logger  Optional PSR-3 logger for observability.
     */
    public function __construct(
        string $key,
        AdapterInterface $adapter,
        int $ttl = 300,
        ?LoggerInterface $logger = null
    ) {
        $this->key = "lock:$key";
        $this->ttl = $ttl;
        $this->adapter = $adapter;

        // 🧠 Initialize logger (custom or default context)
        if ($logger !== null) {
            $this->logger = $logger;
        } else {
            $this->initLogger('lock/redis');
        }


        // 🔌 Ensure adapter is connected before operations
        if (! $this->adapter->isConnected()) {
            $this->adapter->connect();
        }
    }

    /**
     * 🔏 **Acquire the distributed lock atomically.**
     *
     * Attempts to set a key using Redis’s atomic `SET NX EX` semantics.
     * Returns `true` if the lock was successfully acquired, or `false`
     * if another process already holds it.
     *
     * @return bool True on successful lock acquisition, false otherwise.
     */
    public function acquire(): bool
    {
        try {
            $redis = $this->adapter->getConnection();

            /*if (! $redis instanceof \Redis && ! $redis instanceof \Predis\Client) {
                $this->logger->error('Invalid Redis connection instance in acquire()', [
                    'type' => get_debug_type($redis),
                    'key'  => $this->key,
                ]);
                return false;
            }*/

            if (
                !is_object($redis)
                || !method_exists($redis, 'set')
                || !method_exists($redis, 'exists')
                || !method_exists($redis, 'del')
            ) {
                $this->logger->error('Invalid Redis-like connection instance in acquire()', [
                    'type' => get_debug_type($redis),
                    'key'  => $this->key,
                ]);
                return false;
            }

            return $redis->set($this->key, (string) time(), ['nx', 'ex' => $this->ttl]) !== false;


        } catch (Throwable $e) {
            $this->logger->error('RedisLockManager::acquire failed', [
                'key'   => $this->key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * 🔍 **Check if the lock currently exists.**
     *
     * Queries the underlying Redis store to determine whether the lock key is active.
     *
     * @return bool True if lock is currently held; false otherwise.
     */
    public function isLocked(): bool
    {
        try {
            $redis = $this->adapter->getConnection();

            /*if (! $redis instanceof \Redis && ! $redis instanceof \Predis\Client) {
                $this->logger->error('Invalid Redis connection instance in isLocked()', [
                    'type' => get_debug_type($redis),
                    'key'  => $this->key,
                ]);
                return false;
            }*/

            if (
                !is_object($redis)
                || !method_exists($redis, 'set')
                || !method_exists($redis, 'exists')
                || !method_exists($redis, 'del')
            ) {
                $this->logger->error('Invalid Redis-like connection instance in isLocked()', [
                    'type' => get_debug_type($redis),
                    'key'  => $this->key,
                ]);
                return false;
            }

            return $redis->exists($this->key) === 1;
        } catch (Throwable $e) {
            $this->logger->error('RedisLockManager::isLocked failed', [
                'key'   => $this->key,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * 🔓 **Release the distributed lock.**
     *
     * Deletes the associated lock key from the Redis store, freeing
     * the resource for other processes.
     *
     * @return void
     */
    public function release(): void
    {
        try {
            $redis = $this->adapter->getConnection();

            /*if (! $redis instanceof \Redis && ! $redis instanceof \Predis\Client) {
                $this->logger->error('Invalid Redis connection instance in release()', [
                    'type' => get_debug_type($redis),
                    'key'  => $this->key,
                ]);
                return;
            }*/

            if (
                !is_object($redis)
                || !method_exists($redis, 'set')
                || !method_exists($redis, 'exists')
                || !method_exists($redis, 'del')
            ) {
                $this->logger->error('Invalid Redis-like connection instance in release()', [
                    'type' => get_debug_type($redis),
                    'key'  => $this->key,
                ]);
                return;
            }

            $redis->del($this->key);

        } catch (Throwable $e) {
            $this->logger->error('RedisLockManager::release failed', [
                'key'   => $this->key,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
