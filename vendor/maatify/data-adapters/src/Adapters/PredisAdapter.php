<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:44
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Adapters;

use Maatify\Common\Enums\ConnectionTypeEnum;
use Maatify\DataAdapters\Core\BaseAdapter;
use Maatify\DataAdapters\Core\Exceptions\ConnectionException;
use Predis\Client;
use Throwable;

/**
 * 🧩 **Class PredisAdapter**
 *
 * 🎯 Provides a lightweight Redis adapter using the **Predis** client.
 * Supports:
 * - DSN-based configuration
 * - Legacy host/port/password/database setup
 * - Manual AUTH handling
 * - Connection validation via `PING`
 *
 * Designed for microservices, CLI workers, and cache layers requiring a fast,
 * dependency-light Redis client instead of php-redis extension.
 *
 * ---
 * ### ✅ Example Usage
 * ```php
 * use Maatify\DataAdapters\Core\DatabaseResolver;
 *
 * $resolver = new DatabaseResolver($config);
 * $redis = $resolver->resolve('redis.cache');
 *
 * $redis->connection()->set('foo', 'bar');
 * echo $redis->connection()->get('foo');
 * ```
 * ---
 */
final class PredisAdapter extends BaseAdapter
{
    /** @var \Predis\Client $connection*/
    protected mixed $connection = null;

    /**
     * 🧠 **Connect using Predis client**
     *
     * Priority:
     * 1️⃣ **DSN mode** — Modern and preferred
     * 2️⃣ **Legacy mode** — Host/port/database fallback
     *
     * After creating the client instance:
     * - AUTH is applied manually (Predis does not auto-authenticate)
     * - A `PING` command is executed to guarantee connectivity
     *
     * @throws ConnectionException On connection/authentication errors.
     */
    public function connect(): void
    {
        $cfg = $this->resolveConfig(ConnectionTypeEnum::REDIS);

        try {
            // -----------------------------------------
            // 1️⃣ DSN MODE — Full custom Predis URL
            // -----------------------------------------
            if (!empty($cfg->dsn)) {
                $params = $cfg->dsn;
            }

            // -----------------------------------------
            // 2️⃣ Legacy fallback (tcp://host:port)
            // -----------------------------------------
            else {
                $params = [
                    'scheme'   => 'tcp',
                    'host'     => $cfg->host ?? '127.0.0.1',
                    'port'     => (int)($cfg->port ?? 6379),
                    'password' => null, // Manual AUTH below
                    'database' => $cfg->database ? (int)$cfg->database : null,
                ];
            }

            // 🧩 Create Predis client instance
            $client = new Client($params);

            // -----------------------------------------
            // 🔐 AUTH — Required when password exists
            // -----------------------------------------
            if (!empty($cfg->pass)) {
                try {
                    $client->auth($cfg->pass);
                } catch (Throwable $e) {
                    throw new ConnectionException(
                        'Predis authentication failed: ' . $e->getMessage()
                    );
                }
            }

            // -----------------------------------------
            // 🏁 Validate connection with PING
            // -----------------------------------------
            try {
                $client->ping();
            } catch (Throwable $e) {
                throw new ConnectionException(
                    'Predis connection failed: ' . $e->getMessage()
                );
            }

            $this->connection = $client;
            $this->connected  = true;

        } catch (Throwable $e) {
            throw new ConnectionException(
                'Predis connection failed: ' . $e->getMessage()
            );
        }
    }

    /**
     * 🧪 **Health Check**
     *
     * Sends a lightweight `PING` command to verify the connection.
     *
     * @return bool `true` if the Redis instance responds, otherwise `false`.
     *
     * ---
     * ### 🔹 Example:
     * ```php
     * if (!$redis->healthCheck()) {
     *     echo "Redis seems offline";
     * }
     * ```
     */
    public function healthCheck(): bool
    {
        try {
            $this->connection->ping();
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * 🔄 **Reconnect**
     *
     * Closes the current client and creates a new one using the same configuration.
     *
     * @return bool Whether reconnection was successful.
     *
     * ---
     * ### 🔹 Example:
     * ```php
     * if (!$redis->reconnect()) {
     *     throw new Exception("Could not reconnect to Redis");
     * }
     * ```
     */
    public function reconnect(): bool
    {
        $this->disconnect();
        $this->connect();
        return $this->connected;
    }

    public function getDriver(): \Predis\Client
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        return $this->connection;
    }
}
