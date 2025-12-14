<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:17
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core;

use JsonException;
use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\Common\DTO\ConnectionConfigDTO;
use Maatify\Common\Enums\ConnectionTypeEnum;
use Maatify\DataAdapters\Adapters\MongoAdapter;
use Maatify\DataAdapters\Adapters\MySQLAdapter;
use Maatify\DataAdapters\Adapters\MySQLDbalAdapter;
use Maatify\DataAdapters\Adapters\PredisAdapter;
use Maatify\DataAdapters\Adapters\RedisAdapter;
use Maatify\DataAdapters\Core\Config\MongoConfigBuilder;
use Maatify\DataAdapters\Core\Config\MySqlConfigBuilder;
use Maatify\DataAdapters\Core\Config\RedisConfigBuilder;
use Maatify\DataAdapters\Core\Exceptions\ConnectionException;

/**
 * 🧩 **BaseAdapter**
 *
 * Abstract parent for all database adapters in the Maatify Data Adapters ecosystem.
 *
 * 🎯 Responsibilities:
 * - Store connection state (`connected`, `connection`)
 * - Store active profile (`main`, `logs`, etc.)
 * - Resolve connection configuration using DSN → Registry → Legacy hierarchy
 * - Provide shared helpers for adapters (requireEnv, disconnect, etc.)
 * - Enforce standard adapter interface: `connect()`, `reconnect()`, `healthCheck()`
 *
 * Each concrete adapter:
 * - Implements connection logic for a specific driver (MySQL, Mongo, Redis)
 * - Uses unified configuration builders for consistency
 *
 * ---
 * ### Example: Instantiation via DatabaseResolver
 * ```php
 * $adapter = $resolver->resolve('mysql.main');
 * $adapter->connect();
 *
 * if ($adapter->healthCheck()) {
 *     echo "MySQL OK";
 * }
 * ```
 * ---
 */
abstract class BaseAdapter implements AdapterInterface
{
    /**
     * Indicates whether the adapter has a successfully established connection.
     *
     * @var bool
     */
    protected bool $connected = false;

    /**
     * Holds the raw client connection instance (PDO, Client, Redis, etc.).
     *
     * @var mixed
     */
    protected mixed $connection = null;

    /**
     * Active connection profile (e.g., `main`, `logs`, `analytics`).
     *
     * @var string|null
     */
    protected ?string $profile = null;

    /**
     * @param EnvironmentConfig $config  Environment config manager
     * @param string|null       $profile Profile name (defaults to `main`)
     */
    public function __construct(
        protected readonly EnvironmentConfig $config,
        ?string $profile = null
    ) {
        $this->profile = $profile ?? 'main';
    }

    /**
     * 🧠 **Resolve connection config using DSN-first priority**
     *
     * Delegates resolution to the appropriate builder based on connection type:
     * - `MySqlConfigBuilder`
     * - `MongoConfigBuilder`
     * - `RedisConfigBuilder`
     *
     * @param ConnectionTypeEnum $type
     *
     * @return ConnectionConfigDTO Fully resolved connection configuration
     *
     * @throws ConnectionException If an unsupported type is provided
     */
    protected function resolveConfig(ConnectionTypeEnum $type): ConnectionConfigDTO
    {
        $profile = $this->profile ?? 'default';
        return match ($type) {
            ConnectionTypeEnum::MYSQL => (new MySqlConfigBuilder($this->config))
                ->build($profile),

            ConnectionTypeEnum::MONGO => (new MongoConfigBuilder($this->config))
                ->build($profile),

            ConnectionTypeEnum::REDIS => (new RedisConfigBuilder($this->config))
                ->build($profile)
        };
    }

    /**
     * 🧱 **Required ENV getter**
     *
     * Ensures required environment variables exist.
     * If not found, a `ConnectionException` is thrown.
     *
     * @param string $key Environment variable key
     *
     * @return string The resolved environment value
     *
     * @throws ConnectionException When missing
     */
    protected function requireEnv(string $key): string
    {
        $value = $this->config->get($key);

        if ($value === null) {
            throw new ConnectionException("Missing required environment variable: {$key}");
        }

        return $value;
    }

    // -------------------------------------------------------------------------
    // Abstract methods required by AdapterInterface
    // -------------------------------------------------------------------------

    /**
     * Establish a new connection.
     */
    abstract public function connect(): void;

    /**
     * Reconnect using same profile/config.
     *
     * @return bool True on successful reconnection
     */
    abstract public function reconnect(): bool;

    /**
     * Perform a lightweight health check on the connection.
     *
     * @return bool True if connection is healthy
     */
    abstract public function healthCheck(): bool;

    // -------------------------------------------------------------------------
    // Shared utilities
    // -------------------------------------------------------------------------

    /**
     * Check if the adapter is currently connected.
     *
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * Return the underlying client connection (if available).
     *
     * @return mixed
     */
    public function getConnection(): mixed
    {
        return $this->connection;
    }

    /**
     * Disconnect safely.
     * Clears the state, but does not close physical connections explicitly (driver dependent).
     */
    public function disconnect(): void
    {
        $this->connection = null;
        $this->connected = false;
    }

    /**
     * 🧪 **Debug helper for PHPUnit**
     *
     * Returns the fully resolved configuration based on the adapter's type.
     *
     * @return ConnectionConfigDTO
     *
     * @throws JsonException|ConnectionException
     */
    public function debugConfig(): ConnectionConfigDTO
    {
        return $this->resolveConfig($this->getTypeEnum());
    }

    /**
     * 🧩 **Infer connection type from concrete adapter class**
     *
     * Used by:
     * - debugConfig()
     * - DatabaseResolver auto-detection
     *
     * @return ConnectionTypeEnum
     */
    protected function getTypeEnum(): ConnectionTypeEnum
    {
        return match (true) {
            $this instanceof MySQLAdapter,
            $this instanceof MySQLDbalAdapter =>
            ConnectionTypeEnum::MYSQL,

            $this instanceof MongoAdapter =>
            ConnectionTypeEnum::MONGO,

            $this instanceof RedisAdapter,
            $this instanceof PredisAdapter =>
            ConnectionTypeEnum::REDIS,

            default => throw new ConnectionException(
                'Unsupported adapter type: adapter: ' . get_class($this)
            ),
        };
    }
}
