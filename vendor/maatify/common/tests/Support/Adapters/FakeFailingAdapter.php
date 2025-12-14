<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:13
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Support\Adapters;

use Maatify\Common\Contracts\Adapter\AdapterInterface;

/**
 * 🧩 **Class FakeFailingAdapter**
 *
 * 🎯 **Purpose**
 * This class provides a stub (mock) implementation of {@see AdapterInterface} that
 * **always fails**, simulating an unhealthy or unreachable adapter (e.g., Redis).
 * It is specifically designed for use in test suites that validate fallback mechanisms
 * and failure handling.
 *
 * 🧠 **Usage Context**
 * - Testing failover systems.
 * - Simulating adapters that cannot establish a connection.
 * - Ensuring components such as {@see \Maatify\Common\Lock\HybridLockManager} handle
 *   unhealthy adapters gracefully.
 *
 * 🔍 **Behavior Summary**
 * - `connect()` → no-op
 * - `isConnected()` → `false`
 * - `getConnection()` → `null`
 * - `healthCheck()` → `false`
 * - `disconnect()` → no-op
 * - `getDriver()` → `'fake'`
 *
 * ⚙️ **Example Usage**
 * ```php
 * $adapter = new FakeFailingAdapter();
 * assert($adapter->isConnected() === false);
 *
 * $lock = new HybridLockManager($adapter, $fallbackAdapter);
 * // Ensures fallback adapter will be used
 * ```
 */
final class FakeFailingAdapter implements AdapterInterface
{
    /**
     * 🚫 Simulates an attempt to connect, but intentionally does nothing.
     *
     * @return void
     */
    public function connect(): void
    {
        // No connection is performed in this fake adapter.
    }

    /**
     * ❌ Always indicates that the adapter is not connected.
     *
     * @return bool False, indicating a disconnected state.
     */
    public function isConnected(): bool
    {
        return false;
    }

    /**
     * 🚫 Returns `null` to represent the absence of an underlying driver/connection object.
     *
     * @return object|null Always null.
     */
    public function getConnection(): ?object
    {
        return null;
    }

    /**
     * ❌ Simulates a failed health check.
     *
     * @return bool Always false to indicate the adapter is unhealthy.
     */
    public function healthCheck(): bool
    {
        return false;
    }

    /**
     * 🧹 Simulates disconnection; no real cleanup is required for this fake adapter.
     *
     * @return void
     */
    public function disconnect(): void
    {
        // No disconnection logic is needed here.
    }

    /**
     * 🏷️ Returns a fake driver name to satisfy the AdapterInterface contract.
     *
     * @return string The static driver identifier "fake".
     */
    public function getDriver(): string
    {
        return 'fake';
    }
}
