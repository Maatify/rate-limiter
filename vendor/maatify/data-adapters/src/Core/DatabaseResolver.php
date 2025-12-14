<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim
 * @since       2025-11-08 20:32
 * @link        https://github.com/Maatify/data-adapters
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core;

use Maatify\Common\Contracts\Adapter\AdapterInterface;
use Maatify\DataAdapters\Adapters\MongoAdapter;
use Maatify\DataAdapters\Contracts\ResolverInterface;
use Maatify\DataAdapters\Core\Exceptions\ConnectionException;

/**
 * 🧩 **DatabaseResolver**
 *
 * 🎯 Central routing component responsible for creating the correct adapter
 * instance (MySQL, Mongo, Redis) based on a string route such as:
 *
 * - `"mysql"` → default `main` profile
 * - `"mysql.logs"` → MySQL adapter for `logs` profile
 * - `"mongo.analytics"`
 * - `"redis.cache"`
 *
 * Supports:
 * - Auto-detection of Redis driver (phpredis → Predis fallback)
 * - MongoDB adapter caching per profile (for performance)
 * - DSN-first config resolution (via underlying adapters)
 * - Optional auto-connect mode
 *
 * ---
 * ### Example Usage
 * ```php
 * $resolver = new DatabaseResolver($env);
 *
 * $mysql = $resolver->resolve('mysql.main', autoConnect: true);
 * $mongo = $resolver->resolve('mongo.logs');
 * $redis = $resolver->resolve('redis.cache', true);
 * ```
 * ---
 */
final class DatabaseResolver implements ResolverInterface
{
    /**
     * Cached Mongo adapters per profile to avoid re-instantiating the client.
     *
     * @var array<string,MongoAdapter>
     */
    private array $mongoCache = [];

    /**
     * @param EnvironmentConfig $config Shared environment configuration loader.
     */
    public function __construct(
        private readonly EnvironmentConfig $config
    ) {
    }

    /**
     * 🧠 **Resolve a database route**
     *
     * Accepts formats:
     * - `"mysql"`
     * - `"mysql.main"`
     * - `"mongo.logs"`
     * - `"redis.queue"`
     *
     * Steps:
     * 1️⃣ Parse route → `(type, profile)`
     * 2️⃣ Default profile = `"main"`
     * 3️⃣ Instantiate appropriate adapter
     * 4️⃣ Optionally call `connect()`
     *
     * @param string $route Route string like `"mysql.logs"`
     * @param bool   $autoConnect If true, adapter->connect() is invoked automatically
     *
     * @return AdapterInterface
     *
     * @throws ConnectionException On unsupported type
     */
    public function resolve(string $route, bool $autoConnect = false): AdapterInterface
    {
        [$type, $profile] = $this->parseStringRoute($route);

        // 🔥 Always ensure a non-null profile (Phase 13 rule)
        $profile = $profile ?: 'main';

        $adapter = match ($type) {
            'redis' => $this->makeRedis($profile),
            'mongo' => $this->makeMongo($profile),
            'mysql' => $this->makeMySQL($profile),
            default => throw new ConnectionException("Unsupported adapter: {$type}")
        };

        if ($autoConnect) {
            $adapter->connect();
        }

        return $adapter;
    }

    /**
     * 🧩 **Parse route string into (type, profile)**
     *
     * Examples:
     * - `"mysql.main"` → `['mysql', 'main']`
     * - `"mongo"` → `['mongo', null]`
     *
     * @param string $value Route string.
     *
     * @return array{string,string|null}
     */
    private function parseStringRoute(string $value): array
    {
        $value = strtolower(trim($value));

        if (str_contains($value, '.')) {
            [$type, $profile] = explode('.', $value, 2);
            return [$type, $profile];
        }

        return [$value, null];
    }

    /**
     * 🧠 **Create Redis adapter**
     *
     * - Tries to use phpredis (`\Redis`) first
     * - Falls back to Predis if extension is missing
     *
     * @param string $profile
     * @return AdapterInterface
     */
    private function makeRedis(string $profile): AdapterInterface
    {
        $class = class_exists('\\Redis')
            ? '\\Maatify\\DataAdapters\\Adapters\\RedisAdapter'
            : '\\Maatify\\DataAdapters\\Adapters\\PredisAdapter';

        return new $class($this->config, $profile);
    }

    /**
     * 🧠 **Create MongoDB adapter (cached per profile)**
     *
     * Mongo connections are expensive to instantiate repeatedly, so
     * we cache them by profile.
     *
     * @param string $profile
     * @return AdapterInterface
     */
    private function makeMongo(string $profile): AdapterInterface
    {
        return $this->mongoCache[$profile]
            ??= new MongoAdapter($this->config, $profile);
    }

    /**
     * 🧠 **Create MySQL adapter**
     *
     * Selects driver based on:
     * ```
     * MYSQL_{PROFILE}_DRIVER=pdo
     * MYSQL_{PROFILE}_DRIVER=dbal
     * ```
     *
     * - `pdo` → MySQLAdapter
     * - `dbal` → MySQLDbalAdapter
     *
     * @param string $profile
     *
     * @return AdapterInterface
     */
    private function makeMySQL(string $profile): AdapterInterface
    {
        $driverKey = strtoupper("MYSQL_{$profile}_DRIVER");

        // Default driver = "pdo"
        $rawDriver = $this->config->get($driverKey, 'pdo');
        $driver    = strtolower((string) $rawDriver);

        $class = $driver === 'dbal'
            ? '\\Maatify\\DataAdapters\\Adapters\\MySQLDbalAdapter'
            : '\\Maatify\\DataAdapters\\Adapters\\MySQLAdapter';

        return new $class($this->config, $profile);
    }
}
