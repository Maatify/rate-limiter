<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:48
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Adapters;

use Maatify\Common\Enums\ConnectionTypeEnum;
use Maatify\DataAdapters\Core\BaseAdapter;
use Maatify\DataAdapters\Core\Exceptions\ConnectionException;
use PDO;
use PDOException;

/**
 * 🧩 **Class MySQLAdapter**
 *
 * 🎯 Provides a unified, safe, and DSN-aware MySQL connection layer for the Maatify ecosystem.
 *
 * This adapter:
 * - Prioritizes **DSN-based configuration** (Phase 10+)
 * - Falls back gracefully to **legacy host/port/db configuration**
 * - Automatically applies strict PDO settings
 * - Integrates cleanly with `DatabaseResolver` routing (`mysql`, `mysql.main`, etc.)
 *
 * ---
 * ### ✅ Example usage
 * ```php
 * use Maatify\DataAdapters\Core\DatabaseResolver;
 *
 * $resolver = new DatabaseResolver($config);
 * $mysql = $resolver->resolve('mysql.main');
 *
 * if ($mysql->healthCheck()) {
 *     echo "MySQL OK";
 * }
 * ```
 * ---
 */
final class MySQLAdapter extends BaseAdapter
{
    /**
     * @var \PDO
     * @phpstan-var \PDO
     */
    protected mixed $connection;

    /**
     * 🧩 **Establish MySQL Connection**
     *
     * 🧠 Priority:
     *  1️⃣ **DSN mode** (recommended for all new projects)
     *  2️⃣ **Legacy host/port/db** mode (fallback for older deployments)
     *
     * Applies strict PDO settings for security & consistency:
     * - Exceptions enabled
     * - Associative fetch mode
     * - Native prepared statements (no emulation)
     *
     * @throws ConnectionException When connection creation fails.
     */
    public function connect(): void
    {
        $cfg = $this->resolveConfig(ConnectionTypeEnum::MYSQL);

        try {

            // ----------------------------------------------------------
            // 1️⃣ DSN MODE — Preferred modern strategy
            // ----------------------------------------------------------
            if (!empty($cfg->dsn)) {
                $dsn = $cfg->dsn;
            }

            // ----------------------------------------------------------
            // 2️⃣ LEGACY MODE — Build DSN manually
            // ----------------------------------------------------------
            else {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                    $cfg->host ?? '127.0.0.1',
                    $cfg->port ?? '3306',
                    $cfg->database ?? ''
                );
            }

            // ----------------------------------------------------------
            // 🔐 PDO OPTIONS — Merge user-defined with secure defaults
            // ----------------------------------------------------------
            $options = $cfg->options + [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,    // Prefer native prepares
                ];

            // ----------------------------------------------------------
            // 🧩 Create PDO Instance
            // ----------------------------------------------------------
            $this->connection = new PDO(
                $dsn,
                $cfg->user,
                $cfg->pass,
                $options
            );

            $this->connected = true;

        } catch (PDOException $e) {
            throw new ConnectionException(
                'MySQL connection failed: ' . $e->getMessage()
            );
        }
    }

    /**
     * 🧪 **Health Check**
     *
     * Performs a lightweight `SELECT 1` query to verify the connection.
     *
     * @return bool `true` if the connection is alive, otherwise `false`
     *
     * ---
     * ### 🔹 Example
     * ```php
     * if (!$mysql->healthCheck()) {
     *     echo "MySQL not responding";
     * }
     * ```
     */
    public function healthCheck(): bool
    {
        try {
            $stmt = $this->connection->query('SELECT 1');
            return $stmt !== false && (bool) $stmt->fetchColumn();
        } catch (PDOException) {
            return false;
        }
    }

    /**
     * 🔄 **Reconnect**
     *
     * Fully rebuilds the connection by closing the existing instance and
     * re-executing the `connect()` method.
     *
     * @return bool `true` if reconnection succeeds
     *
     * ---
     * ### 🔹 Example
     * ```php
     * if (!$mysql->reconnect()) {
     *     throw new RuntimeException("Failed to reconnect");
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
     * @return \PDO
     * @phpstan-return \PDO
     */
    public function getDriver(): \PDO
    {
        if (!$this->isConnected()) {
            $this->connect();
        }

        return $this->connection;
    }

    /**
     * @return \PDO
     * @phpstan-return \PDO
     */
    public function raw(): \PDO
    {
        if (! $this->isConnected()) {
            $this->connect();
        }
        return $this->connection;
    }
}
