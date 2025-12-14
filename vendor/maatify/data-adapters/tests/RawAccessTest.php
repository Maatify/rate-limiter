<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim
 * @since       2025-11-16 17:54
 * @see         https://www.maatify.dev
 * @link        https://github.com/Maatify/data-adapters
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests;

use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 Raw Access Integration Test
 *
 * ✔ REAL tests only — no mocks
 * ✔ Uses SAME DSN as CI & local environment
 * ✔ Uses EnvironmentLoader (via bootstrap) for .env.testing / .env.local
 * ✔ Ensures raw() and getDriver() return real driver instances
 */
final class RawAccessTest extends TestCase
{
    private DatabaseResolver $resolver;

    protected function setUp(): void
    {
        /**
         * ❗ IMPORTANT:
         * We no longer load Fixtures here.
         * We load REAL environment from project root (parent of tests/).
         *
         * bootstrap.php already loads .env.local or .env.testing
         * so EnvironmentConfig only needs root path.
         */
        $config = new EnvironmentConfig(dirname(__DIR__, 1));
        $this->resolver = new DatabaseResolver($config);
    }

    public function testMysqlRawReturnsPdoInstance(): void
    {
        $mysql = $this->resolver->resolve('mysql.main', autoConnect: true);
        $driver = $mysql->getDriver();

        $this->assertInstanceOf(\PDO::class, $driver);
    }

    public function testMysqlRawReturnsDbalInstance(): void
    {
        $mysql = $this->resolver->resolve('mysql.logs', autoConnect: true);
        $driver = $mysql->getDriver();

        //        $this->assertInstanceOf(\PDO::class, $driver);
        $this->assertInstanceOf(\Doctrine\DBAL\Connection::class, $driver);
    }

    public function testMongoRawReturnsDatabaseInstance(): void
    {
        $mongo = $this->resolver->resolve('mongo.logs', autoConnect: true);
        $driver = $mongo->getDriver();

        $this->assertInstanceOf(\MongoDB\Database::class, $driver);
    }

    public function testRedisRawReturnsClientInstance(): void
    {
        $redis = $this->resolver->resolve('redis.cache', autoConnect: true);
        $driver = $redis->getDriver();

        $this->assertTrue(
            $driver instanceof \Redis
            || $driver instanceof \Predis\Client,
            'Driver must be phpredis or predis client instance'
        );
    }

    public function testRawAccessRespectsProfiles(): void
    {
        $logs = $this->resolver->resolve('mongo.logs', autoConnect: true)->getDriver();
        $activity = $this->resolver->resolve('mongo.activity', autoConnect: true)->getDriver();

        // Should return different DB instances based on profile
        $this->assertNotSame($logs, $activity);
    }
}
