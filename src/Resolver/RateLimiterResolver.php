<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 02:53
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Resolver;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Drivers\RedisRateLimiter;
use Maatify\RateLimiter\Drivers\MongoRateLimiter;
use Maatify\RateLimiter\Drivers\MySQLRateLimiter;
use Redis;
use MongoDB\Client as MongoClient;
use PDO;
use InvalidArgumentException;

/**
 * ⚙️ Class RateLimiterResolver
 *
 * 🧩 Purpose:
 * Dynamically resolves and instantiates the correct {@see RateLimiterInterface}
 * driver based on configuration parameters (Redis, MongoDB, or MySQL).
 *
 * ✅ Features:
 * - Centralized driver selection logic.
 * - Simplifies dependency injection for rate limiter drivers.
 * - Auto-connects to storage engines using lightweight configuration arrays.
 *
 * ⚙️ Example usage:
 * ```php
 * use Maatify\RateLimiter\Resolver\RateLimiterResolver;
 *
 * $resolver = new RateLimiterResolver([
 *     'driver' => 'redis',
 *     'redis_host' => '127.0.0.1',
 *     'redis_port' => 6379,
 *     'redis_password' => null
 * ]);
 *
 * $limiter = $resolver->resolve(); // Returns RedisRateLimiter instance
 * ```
 *
 * @package Maatify\RateLimiter\Resolver
 */
final class RateLimiterResolver
{
    /**
     * 🧠 Constructor
     *
     * @param array<string, mixed> $config Configuration array used to determine and connect
     *                      to the appropriate backend driver.
     *
     * Example config keys:
     * ```php
     * [
     *   'driver' => 'redis',
     *   'redis_host' => '127.0.0.1',
     *   'redis_port' => 6379,
     *   'redis_password' => null,
     *   'mongo_uri' => 'mongodb://localhost:27017',
     *   'mongo_db' => 'rate_limiter',
     *   'mongo_collection' => 'limits',
     *   'mysql_dsn' => 'mysql:host=127.0.0.1;dbname=rate_limiter',
     *   'mysql_user' => 'root',
     *   'mysql_pass' => ''
     * ]
     * ```
     */
    public function __construct(
        private readonly array $config
    ) {
    }

    /**
     * 🎯 Resolve the appropriate RateLimiter driver.
     *
     * Uses the configured driver name to instantiate the corresponding
     * rate limiter implementation. Supported values:
     * - `redis`
     * - `mongo` / `mongodb`
     * - `mysql`
     *
     * @return RateLimiterInterface Instance of the resolved rate limiter driver.
     *
     * @throws InvalidArgumentException When the driver type is unsupported.
     *
     * ✅ Example:
     * ```php
     * $limiter = $resolver->resolve(); // e.g., RedisRateLimiter instance
     * ```
     */
    public function resolve(): RateLimiterInterface
    {
        $driver = strtolower((string)($this->config['driver'] ?? 'redis'));

        return match ($driver) {
            'redis' => new RedisRateLimiter($this->redis()),
            'mongo', 'mongodb' => new MongoRateLimiter($this->mongo()),
            'mysql' => new MySQLRateLimiter($this->pdo()),
            default => throw new InvalidArgumentException("Unsupported rate limiter driver: {$driver}"),
        };
    }

    /**
     * 🔌 Connect and configure a Redis client.
     *
     * Establishes a Redis connection using the provided host, port, and password.
     *
     * @return Redis Connected Redis instance.
     *
     * ✅ Example:
     * ```php
     * $redis = $this->redis();
     * $redis->set('key', 'value');
     * ```
     */
    private function redis(): Redis
    {
        $redis = new Redis();
        $redis->connect(
            (string)($this->config['redis_host'] ?? '127.0.0.1'),
            (int)($this->config['redis_port'] ?? 6379)
        );

        if (!empty($this->config['redis_password'])) {
            $redis->auth((string)$this->config['redis_password']);
        }

        return $redis;
    }

    /**
     * 🧱 Initialize a MongoDB collection reference for rate limiting.
     *
     * Connects to the MongoDB server and selects the desired database and collection.
     *
     * @return \MongoDB\Collection Active MongoDB collection instance.
     *
     * ✅ Example:
     * ```php
     * $collection = $this->mongo();
     * $collection->insertOne(['key' => 'value']);
     * ```
     */
    private function mongo(): \MongoDB\Collection
    {
        $client = new MongoClient((string)($this->config['mongo_uri'] ?? 'mongodb://127.0.0.1:27017'));
        return $client->selectCollection(
            (string)($this->config['mongo_db'] ?? 'rate_limiter'),
            (string)($this->config['mongo_collection'] ?? 'limits')
        );
    }

    /**
     * 🧩 Create a PDO connection for MySQL rate-limiter backend.
     *
     * Uses DSN, username, and password from configuration to create a PDO instance.
     *
     * @return PDO Configured PDO connection.
     *
     * ✅ Example:
     * ```php
     * $pdo = $this->pdo();
     * $pdo->query("SELECT 1");
     * ```
     */
    private function pdo(): PDO
    {
        return new PDO(
            (string)($this->config['mysql_dsn'] ?? 'mysql:host=127.0.0.1;dbname=rate_limiter'),
            (string)($this->config['mysql_user'] ?? 'root'),
            (string)($this->config['mysql_pass'] ?? '')
        );
    }
}
