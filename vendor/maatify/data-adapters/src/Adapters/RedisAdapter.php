<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:41
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Adapters;

use Maatify\Common\Enums\ConnectionTypeEnum;
use Maatify\DataAdapters\Core\BaseAdapter;
use Maatify\DataAdapters\Core\Exceptions\ConnectionException;
use Redis;
use Throwable;

/**
 * 🧩 **Class RedisAdapter**
 *
 * 🎯 Provides a Redis connection using the native **phpredis** extension.
 * This adapter is designed for high-performance workloads, queue handlers,
 * cache layers, and any service that benefits from persistent TCP-level Redis connections.
 *
 * ✅ Supports:
 * - Legacy host/port/password connection
 * - AUTH authentication
 * - Connection validation via `PING`
 * - Seamless reconnection
 *
 * ---
 * ### Example Usage
 * ```php
 * use Maatify\DataAdapters\Core\DatabaseResolver;
 *
 * $resolver = new DatabaseResolver($config);
 * $redis = $resolver->resolve('redis.session');
 *
 * $redis->connection()->set('token', '123');
 * echo $redis->connection()->get('token');
 * ```
 * ---
 */
final class RedisAdapter extends BaseAdapter
{
    /** @var Redis $connection*/
    protected mixed $connection = null;
    /**
     * 🧠 **Connect using the phpredis extension**
     *
     * Resolves configuration via DSN-first strategy in BaseAdapter, but phpredis
     * itself does not support DSN directly — therefore this adapter only uses the
     * resolved **host**, **port**, and **password**.
     *
     * Steps:
     * 1️⃣ Create a new Redis client
     * 2️⃣ Connect to host/port
     * 3️⃣ Authenticate (optional)
     * 4️⃣ Validate connection using `PING`
     *
     * @throws ConnectionException When connection or authentication fails.
     */
    public function connect(): void
    {
        $cfg = $this->resolveConfig(ConnectionTypeEnum::REDIS);

        try {
            // 🧩 Instantiate native Redis client
            $client = new Redis();

            // -----------------------------------------
            // 🔌 Connect (tcp-only for phpredis)
            // -----------------------------------------
            $client->connect(
                $cfg->host ?? '127.0.0.1',
                (int)($cfg->port ?? 6379)
            );

            // -----------------------------------------
            // 🔐 AUTH if needed
            // -----------------------------------------
            if (! empty($cfg->pass)) {
                $client->auth($cfg->pass);
            }

            // -----------------------------------------
            // 🏁 Validate connection with PING
            // -----------------------------------------
            $pong = $client->ping();
            if ($pong === false) {
                throw new ConnectionException('Redis did not respond to PING.');
            }

            $this->connection = $client;
            $this->connected  = true;

        } catch (Throwable $e) {
            throw new ConnectionException(
                'Redis connection failed: ' . $e->getMessage()
            );
        }
    }

    /**
     * 🧪 **Health Check**
     *
     * Uses Redis's `PING` to verify active connectivity.
     *
     * @return bool `true` if Redis responds correctly, else `false`.
     *
     * ---
     * ### Example:
     * ```php
     * if (!$redis->healthCheck()) {
     *     echo "Redis offline";
     * }
     * ```
     */
    public function healthCheck(): bool
    {
        try {
            return (bool)$this->connection->ping();
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * 🔄 **Reconnect**
     *
     * Safely disconnects and re-establishes the Redis connection.
     *
     * @return bool Whether reconnection succeeded.
     *
     * ---
     * ### Example:
     * ```php
     * if (!$redis->reconnect()) {
     *     throw new RuntimeException("Failed to reconnect Redis");
     * }
     * ```
     */
    public function reconnect(): bool
    {
        $this->disconnect();
        $this->connect();

        return $this->connected;
    }

    public function getDriver(): \Redis
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        return $this->connection;
    }
}
