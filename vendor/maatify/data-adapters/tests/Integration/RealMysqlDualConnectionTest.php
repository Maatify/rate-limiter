<?php

declare(strict_types=1);
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-11 14:54
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

namespace Maatify\DataAdapters\Tests\Integration;

use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🔥 Real MySQL Dual Connection Test
 *
 * ✔ يعمل في CI + Local بدون أي شروط
 * ✔ يجرب كل من:
 *   - MYSQL_DSN  → PDO driver
 *   - MYSQL_MAIN_DSN → DBAL driver
 * ✔ يستخدم القيم الحقيقية من .env للمشروع
 */
final class RealMysqlDualConnectionTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('provideDrivers')]
    public function testMysqlConnection(string $driver, string $dsnEnvVar): void
    {
        // -----------------------------
        // 1) Load .env real values
        // -----------------------------
        $configLoader = new EnvironmentConfig(dirname(__DIR__, 2));

        $host = $configLoader->get('MYSQL_HOST');
        $port = $configLoader->get('MYSQL_PORT');
        $db   = $configLoader->get('MYSQL_DB');
        $user = $configLoader->get('MYSQL_USER');
        $pass = $configLoader->get('MYSQL_PASS');

        $this->assertNotEmpty($host, 'Missing MYSQL_HOST in .env');
        $this->assertNotEmpty($db, 'Missing MYSQL_DB in .env');

        // -----------------------------
        // 2) Clean old DSNs
        // -----------------------------
        putenv('MYSQL_DSN');
        putenv('MYSQL_MAIN_DSN');
        putenv('MYSQL_DEFAULT_DSN');

        // -----------------------------
        // 3) Set DSN for the tested driver
        // -----------------------------
        $pdoDsn = "mysql:host={$host};port={$port};dbname={$db}";
        putenv("{$dsnEnvVar}={$pdoDsn}");

        // Ensure username/password available
        putenv("MYSQL_USER={$user}");
        putenv("MYSQL_PASS={$pass}");

        // -----------------------------
        // 4) Reload config after overrides
        // -----------------------------
        $config   = new EnvironmentConfig(dirname(__DIR__, 2));
        $resolver = new DatabaseResolver($config);

        // -----------------------------
        // 5) Resolve and connect
        // -----------------------------
        $adapter = $resolver->resolve('mysql');
        $adapter->connect();

        // -----------------------------
        // 6) Validate actual connectivity
        // -----------------------------
        $this->assertTrue(
            $adapter->healthCheck(),
            "❌ MySQL {$driver} health check failed."
        );
    }

    /**
     * 🧪 Driver matrix:
     * - MYSQL_DSN       → PDO
     * - MYSQL_MAIN_DSN  → DBAL
     */
    public static function provideDrivers(): array
    {
        return [
            ['pdo',  'MYSQL_DSN'],
            ['dbal', 'MYSQL_MAIN_DSN'],
        ];
    }
}
