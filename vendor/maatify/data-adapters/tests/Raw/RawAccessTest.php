<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-17 10:15
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Raw;

use Doctrine\DBAL\Connection;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use MongoDB\Database;
use PHPUnit\Framework\TestCase;

final class RawAccessTest extends TestCase
{
    private DatabaseResolver $resolver;

    protected function setUp(): void
    {
        /**
         * 🚨 IMPORTANT:
         * RawAccessTest must NEVER rely on local machine env.
         * CI injects full env via GitHub ENV (GITHUB_ENV),
         * so EnvironmentConfig must read directly from $_ENV.
         */
        $this->resolver = new DatabaseResolver(
            new EnvironmentConfig(dirname(__DIR__, 2))
        );
    }

    /** ---------------------------------------------
     *  🔵 RAW MYSQL – PDO
     * ---------------------------------------------- */
    public function testMysqlPdoRaw(): void
    {
        /**
         * We override only the needed keys for this test.
         * CI already has maatify_dev database created.
         */
        $_ENV['MYSQL_MAIN_DSN'] =
            'mysql:host=127.0.0.1;port=3306;dbname=maatify;charset=utf8mb4;';

        $_ENV['MYSQL_MAIN_USER'] = 'root';
        $_ENV['MYSQL_MAIN_DRIVER'] = 'pdo';
        $_ENV['MYSQL_MAIN_PASS'] = $_ENV['MYSQL_PASS'] ?? getenv('MYSQL_PASS');

        $mysql = $this->resolver->resolve('mysql.main');
        $raw   = $mysql->getDriver();

        $this->assertInstanceOf(\PDO::class, $raw);
    }

    /** ---------------------------------------------
     *  🔵 RAW MYSQL – DBAL
     * ---------------------------------------------- */
    public function testMysqlDbalRaw(): void
    {
        /**
         * Using maatify_logs which we ensure exists in CI.
         */

        $_ENV['MYSQL_LOGS_DSN'] =
            'mysql://root:' . ($_ENV['MYSQL_PASS'] ?? getenv('MYSQL_PASS')) . '@127.0.0.1:3306/maatify';
        $_ENV['MYSQL_LOGS_DRIVER'] = 'dbal';

        $mysql = $this->resolver->resolve('mysql.logs');
        $raw   = $mysql->getDriver();

        $this->assertInstanceOf(Connection::class, $raw);
    }

    /** ---------------------------------------------
     *  🟢 RAW MONGO
     * ---------------------------------------------- */
    public function testMongoRaw(): void
    {
        /**
         * Mongo test database always exists in CI
         */
        $_ENV['MONGO_MAIN_DSN'] = 'mongodb://127.0.0.1:27017/maatify';

        $mongo = $this->resolver->resolve('mongo.main');
        $raw   = $mongo->getDriver();

        $this->assertInstanceOf(Database::class, $raw);
    }

    /** ---------------------------------------------
     *  🔴 RAW REDIS
     * ---------------------------------------------- */
    public function testRedisRaw(): void
    {
        /**
         * Redis exists on 6379 in CI.
         */
        $urlPass = (!empty($_ENV['REDIS_PASS']) ? ':' . rawurlencode($_ENV['REDIS_PASS']) . '@' : '');
        $_ENV['REDIS_MAIN_DSN'] = 'redis://' . $urlPass . '127.0.0.1:6379';

        $redis = $this->resolver->resolve('redis.main');
        $raw   = $redis->getDriver();

        $this->assertTrue(
            $raw instanceof \Redis || $raw instanceof \Predis\Client
        );
    }
}
