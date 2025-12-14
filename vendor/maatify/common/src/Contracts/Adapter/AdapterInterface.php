<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:49
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Contracts\Adapter;

/**
 * 🧩 Interface AdapterInterface
 *
 * 🎯 Purpose:
 * Defines the **unified contract** that all data adapters (e.g., Redis, MongoDB, MySQL)
 * must implement to ensure consistent connectivity and operational behavior
 * across the Maatify data access layer.
 *
 * 🧠 This interface allows Maatify systems to interact with any data source
 * using a common abstraction without worrying about implementation details.
 *
 * ✅ Required Responsibilities:
 * - Establish and manage a persistent connection.
 * - Provide connection health checks and state awareness.
 * - Expose raw connection objects (for low-level operations if needed).
 * - Support graceful disconnection.
 *
 * ⚙️ Example:
 * ```php
 * use Maatify\DataAdapters\Contracts\AdapterInterface;
 *
 * final class RedisAdapter implements AdapterInterface {
 *     public function connect(): void { ... }
 *     public function isConnected(): bool { ... }
 *     public function getConnection(): ?object { ... }
 *     public function healthCheck(): bool { ... }
 *     public function disconnect(): void { ... }
 * }
 * ```
 *
 * @package Maatify\DataAdapters\Contracts
 */
interface AdapterInterface
{
    /**
     * ⚙️ Establish connection to the target data source.
     *
     * 🧠 Must initialize the adapter and make the underlying connection object ready for use.
     *
     * @return void
     *
     * ✅ Example:
     * ```php
     * $adapter->connect();
     * ```
     */
    public function connect(): void;

    /**
     * 🔍 Check if the adapter is currently connected.
     *
     * @return bool True if the connection is active and usable, false otherwise.
     *
     * ✅ Example:
     * ```php
     * if (! $adapter->isConnected()) {
     *     $adapter->connect();
     * }
     * ```
     */
    public function isConnected(): bool;

    /**
     * 🧠 Retrieve the raw underlying connection object.
     *
     * Used when low-level access to the native driver is required
     * (e.g., direct Redis, PDO, or MongoDB client operations).
     *
     * @return object|null Returns the active connection object, or null if not connected.
     *
     * ✅ Example:
     * ```php
     * $client = $adapter->getConnection();
     * $client->ping();
     * ```
     *
     * @return \PDO|\Doctrine\DBAL\Connection|\MongoDB\Client|\Redis|\Predis\Client
     *
     * @phpstan-return \PDO|\Doctrine\DBAL\Connection|\MongoDB\Client|\Redis|\Predis\Client
     *  public function getConnection(): mixed;
     */
    public function getConnection(): mixed;

    /**
     * 🩺 Perform a health check on the current connection.
     *
     * Ensures the adapter’s connection is valid and responsive.
     * Implementations may perform a `PING`, `SELECT 1`, or similar operation.
     *
     * @return bool True if the connection is healthy, false otherwise.
     *
     * ✅ Example:
     * ```php
     * if (! $adapter->healthCheck()) {
     *     $adapter->reconnect();
     * }
     * ```
     */
    public function healthCheck(): bool;

    /**
     * ❌ Gracefully terminate the connection.
     *
     * Closes any open sessions or connections to the data source
     * to prevent resource leaks and ensure clean shutdown.
     *
     * @return void
     *
     * ✅ Example:
     * ```php
     * $adapter->disconnect();
     * ```
     */
    public function disconnect(): void;

    /**
     * 🏷️ **Retrieve the underlying driver instance** (native connector).
     *
     * This is often used for:
     * - Low-level operations
     * - Running raw queries
     * - Debugging
     * - Direct driver feature access
     *
     * 🧪 **Example**
     * ```php
     * $driver = $adapter->getDriver();
     * var_dump(get_class($driver));  // e.g., Redis, PDO, MongoDB\Database
     * ```
     *
     * @return \PDO|\Doctrine\DBAL\Connection|\MongoDB\Database|\Redis|\Predis\Client
     *
     * @phpstan-return \PDO|\Doctrine\DBAL\Connection|\MongoDB\Database|\Redis|\Predis\Client
     * public function getDriver(): mixed;
     */
    public function getDriver(): mixed;
}
