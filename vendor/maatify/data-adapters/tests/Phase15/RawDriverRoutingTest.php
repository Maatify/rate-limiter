<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-17 11:25
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Phase15;

use Maatify\DataAdapters\Adapters\MySQLAdapter;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

final class RawDriverRoutingTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testDbalRoutePicking(): void
    {
        $_ENV['APP_ENV'] = 'testing';
        $_ENV['MYSQL_REPORTING_DSN'] = 'mysql://root:pass@127.0.0.1:3306/reportdb';
        $_ENV['MYSQL_REPORTING_DRIVER'] = 'dbal';

        $resolver = new DatabaseResolver(new EnvironmentConfig(dirname(__DIR__, 2)));
        $adapter = $resolver->resolve('mysql.reporting');

        //        $this->assertInstanceOf(\Doctrine\DBAL\Connection::class, $adapter->getDriver());

        $this->assertInstanceOf(
            \Maatify\DataAdapters\Adapters\MySQLDbalAdapter::class,
            $adapter
        );
    }

    /**
     * @runInSeparateProcess
     */
    public function testPdoRoutePicking(): void
    {
        $_ENV['APP_ENV'] = 'testing';

        $_ENV['MYSQL_BILLING_DSN']  = 'mysql:host=127.0.0.1;dbname=billing';
        $_ENV['MYSQL_BILLING_USER'] = 'root';
        $_ENV['MYSQL_BILLING_PASS'] = 'root';

        $resolver = new DatabaseResolver(new EnvironmentConfig(dirname(__DIR__, 2)));
        $adapter = $resolver->resolve('mysql.billing');

        $this->assertInstanceOf(MySQLAdapter::class, $adapter);
        //        $this->assertInstanceOf(\PDO::class, $adapter->getDriver());
    }
}
