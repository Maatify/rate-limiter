<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-13 19:16
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Phase10;

use Maatify\DataAdapters\Adapters\MySQLDbalAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 MysqlDbalDsnAdapterTest (Real Integration Version)
 *
 * ✔ No $_ENV mocking
 * ✔ Uses real DSN from .env.testing or .env.local
 * ✔ Ensures DBAL adapter honors DSN-first behavior
 * ✔ Works on GitHub Actions + local environment
 */
final class MysqlDbalDsnAdapterTest extends TestCase
{
    private MySQLDbalAdapter $adapter;

    protected function setUp(): void
    {
        // Real bootstrap already loaded env (.env.local, .env.testing)
        $config = new EnvironmentConfig(dirname(__DIR__, 2));

        // profile "logs" must exist in DSN
        $this->adapter = new MySQLDbalAdapter($config, 'logs');
    }

    public function testDbalReadsRealDsn(): void
    {
        $cfg = $this->adapter->debugConfig();

        // Ensure DSN exists
        $this->assertNotEmpty(
            $cfg->dsn,
            'MYSQL_LOGS_DSN must exist in your environment'
        );

        // Ensure Doctrine URL DSN format
        $this->assertStringStartsWith(
            'mysql://',
            $cfg->dsn,
            'DBAL DSN must be Doctrine URL format'
        );
    }
}
