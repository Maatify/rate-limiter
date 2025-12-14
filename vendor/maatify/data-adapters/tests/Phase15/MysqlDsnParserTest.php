<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-17 10:24
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Phase15;

use Maatify\DataAdapters\Core\Config\MySqlConfigBuilder;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use Maatify\DataAdapters\Core\Parser\MysqlDsnParser;
use PHPUnit\Framework\TestCase;

final class MysqlDsnParserTest extends TestCase
{
    private MySqlConfigBuilder $builder;

    protected function setUp(): void
    {
        $config = new EnvironmentConfig(dirname(__DIR__, 2));
        $this->builder = new MySqlConfigBuilder($config);
    }

    public function testDoctrineDsnParsing(): void
    {
        $dsn = 'mysql://user:P%40%3A%3B@10.0.0.5:3306/mydb';

        $parsed = MysqlDsnParser::parse($dsn);

        $this->assertSame('user', $parsed['user']);
        $this->assertSame('P%40%3A%3B', $parsed['pass']);
        $this->assertSame('10.0.0.5', $parsed['host']);
        $this->assertSame('3306', $parsed['port']);
        $this->assertSame('mydb', $parsed['database']);
    }

    /**
     * @runInSeparateProcess
     */
    public function testPdoDsnParsing(): void
    {
        // Force testing mode
        $_ENV['APP_ENV'] = 'testing';

        // Testing mode uses ONLY $_ENV — so write here
        $_ENV['MYSQL_MAIN_DSN']  = 'mysql:host=10.0.0.5;port=3307;dbname=demo';
        $_ENV['MYSQL_MAIN_USER'] = 'admin';
        $_ENV['MYSQL_MAIN_PASS'] = '123';

        $_ENV['MYSQL_MAIN_HOST'] = '10.0.0.5';
        $_ENV['MYSQL_MAIN_PORT'] = '3307';
        $_ENV['MYSQL_MAIN_DB']   = 'demo';

        $configLoader = new EnvironmentConfig(dirname(__DIR__, 3));
        $builder = new MySqlConfigBuilder($configLoader);

        $config = $builder->build('main');

        $this->assertSame('10.0.0.5', $config->host);
        $this->assertSame('3307', $config->port);
        $this->assertSame('demo', $config->database);
        $this->assertSame('admin', $config->user);
        $this->assertSame('123', $config->pass);
    }

}
