<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim
 * @since       2025-11-08 20:47
 * @see         https://www.maatify.dev
 * @link        https://github.com/Maatify/data-adapters
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Adapters;

use Maatify\Common\Enums\ConnectionTypeEnum;
use Maatify\DataAdapters\Core\BaseAdapter;
use Maatify\DataAdapters\Core\Exceptions\ConnectionException;
use MongoDB\Client;
use MongoDB\Database;
use Throwable;

/**
 * 🧩 **Class MongoAdapter**
 *
 * 🎯 Provides a unified connection handler for MongoDB connections within the Maatify
 * Data Adapters ecosystem.
 * Supports both **DSN-first resolution** and **legacy host/port** fallback to ensure
 * maximum compatibility across environments and older configurations.
 *
 * ⚙️ This adapter extends `BaseAdapter` and fully respects:
 * - Multi-profile routing
 * - DSN parsing and priority logic
 * - EnvironmentConfig builder resolution
 *
 * ---
 * ### ✅ Usage Example
 * ```php
 * use Maatify\DataAdapters\Core\DatabaseResolver;
 *
 * $resolver = new DatabaseResolver($config);
 * $mongo = $resolver->resolve('mongo.logs');
 *
 * if ($mongo->healthCheck()) {
 *     echo "Mongo is healthy!";
 * }
 * ```
 *
 * ---
 */
final class MongoAdapter extends BaseAdapter
{
    /** @var Client $connection*/
    protected mixed $connection = null;
    private ?Database $cachedDb = null;

    /**
     * 🧩 **Establish MongoDB Connection**
     *
     * 🧠 Order of priority:
     *  1️⃣ **DSN mode** (modern, preferred — Phase 10+)
     *  2️⃣ **Legacy host/port/database** fallback mode
     *
     * ⚠️ No mutation of configuration occurs here — all values come from the resolved
     * ConnectionConfigDTO produced by the BaseAdapter + Builder chain.
     *
     * @throws ConnectionException When connection initialization fails.
     */
    public function connect(): void
    {
        $cfg = $this->resolveConfig(ConnectionTypeEnum::MONGO);

        try {
            // ----------------------------------------------------------
            // 1️⃣ DSN MODE — Preferred option for all new integrations
            // ----------------------------------------------------------
            if (!empty($cfg->dsn)) {
                $this->connection = new Client(
                    $cfg->dsn,
                    $this->buildAuthOptions($cfg->user, $cfg->pass)
                );

                $this->connected = true;
                return;
            }

            // ----------------------------------------------------------
            // 2️⃣ LEGACY MODE — Safe fallback for older deployments
            // ----------------------------------------------------------
            $dsn = sprintf(
                'mongodb://%s:%s/%s',
                $cfg->host ?? '127.0.0.1',
                $cfg->port ?? '27017',
                $cfg->database ?? 'admin'
            );

            $this->connection = new Client(
                $dsn,
                $this->buildAuthOptions($cfg->user, $cfg->pass)
            );

            $this->connected = true;
        } catch (Throwable $e) {
            throw new ConnectionException(
                'Mongo connection failed: ' . $e->getMessage()
            );
        }
    }

    /**
     * 🔐 **Build MongoDB Authentication Options**
     *
     * 🧩 Produces a driver-compatible options array containing username/password
     * fields only when provided.
     * This keeps the connection logic clean and prevents passing empty credentials.
     *
     * @param string|null $user  Username for authentication
     * @param string|null $pass  Password for authentication
     *
     * @return array<string, string> Authentication options for MongoDB\Client
     *
     * ---
     * ### 🔹 Example
     * ```php
     * $opts = $this->buildAuthOptions('admin', 'secret');
     * // ['username' => 'admin', 'password' => 'secret']
     * ```
     */
    private function buildAuthOptions(?string $user, ?string $pass): array
    {
        $options = [];

        if (!empty($user)) {
            $options['username'] = $user;
        }

        if (!empty($pass)) {
            $options['password'] = $pass;
        }

        return $options;
    }

    /**
     * 🧪 **MongoDB Health Check**
     *
     * Performs a lightweight `ping` command on the selected database.
     *
     * @return bool `true` if the server responds successfully, otherwise `false`.
     *
     * ---
     * ### 🔹 Example
     * ```php
     * if (!$mongo->healthCheck()) {
     *     throw new Exception("MongoDB not reachable");
     * }
     * ```
     */
    public function healthCheck(): bool
    {
        try {
            $db = $this->connection->selectDatabase($this->profile ?? 'admin');
            $db->command(['ping' => 1]);
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * 🔄 **Reconnect**
     *
     * Closes the current connection and attempts to rebuild it using the existing
     * profile configuration. This is typically used when the driver throws a
     * connection-level exception or when long-lived workers need refresh cycles.
     *
     * @return bool Whether the new connection succeeded.
     *
     * ---
     * ### 🔹 Example
     * ```php
     * if (!$mongo->reconnect()) {
     *     echo "Reconnect failed.";
     * }
     * ```
     */
    public function reconnect(): bool
    {
        $this->disconnect();
        $this->connect();
        return $this->connected;
    }

    /**
     * 📌 Return a MongoDB\Database instance based on resolved profile or cfg->database
     */

    public function getDatabase(): Database
    {
        if ($this->cachedDb instanceof Database) {
            return $this->cachedDb;
        }

        if (! $this->connected) {
            $this->connect();
        }

        $cfg = $this->resolveConfig(ConnectionTypeEnum::MONGO);

        $dbName = $cfg->database ?: 'admin';
        return $this->cachedDb = $this->connection->selectDatabase($dbName);
    }

    /**
     * 📌 Optional: return underlying MongoDB\Client
     */
    public function getClient(): Client
    {
        if (! $this->connected) {
            $this->connect();
        }

        return $this->connection;
    }

    public function getDriver(): Database
    {
        return $this->getDatabase();
    }

}
