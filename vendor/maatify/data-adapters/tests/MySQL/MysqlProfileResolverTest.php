<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-14 20:47
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\MySQL;

use Maatify\DataAdapters\Adapters\MySQLAdapter;
use Maatify\DataAdapters\Adapters\MySQLDbalAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 MysqlProfileResolverTest — Real Integration Version
 *
 * ✔ No mocking of $_ENV
 * ✔ Reads actual DSN from `.env.testing` or GitHub Actions
 * ✔ Validates Real DSN-first + Builder merge logic
 * ✔ Works across all profiles existing in real CI env
 */
final class MysqlProfileResolverTest extends TestCase
{
    private EnvironmentConfig $env;

    protected function setUp(): void
    {
        parent::setUp();

        // EnvironmentLoader already loaded `.env.testing` in tests/bootstrap.php
        $this->env = new EnvironmentConfig(dirname(__DIR__, 2));
    }

    private function mysql(?string $profile = null): MySQLAdapter
    {
        return new MySQLAdapter($this->env, $profile);
    }

    private function mysqlDbal(?string $profile = null): MySQLDbalAdapter
    {
        return new MySQLDbalAdapter($this->env, $profile);
    }

    // -------------------------------------------------------------
    // 1) DSN Priority — must use DSN, not host/db
    // -------------------------------------------------------------
    public function testDsnPriority(): void
    {
        $adapter = $this->mysql('main');
        $cfg = $adapter->debugConfig();

        $this->assertNotEmpty($cfg->dsn, 'MYSQL_MAIN_DSN must exist in environment');

        $this->assertStringStartsWith(
            'mysql:',
            $cfg->dsn,
            'MySQL DSN must begin with mysql:'
        );
    }

    // -------------------------------------------------------------
    // 2) Dynamic Profile — any profile must resolve
    // -------------------------------------------------------------
    public function testDynamicProfileResolves(): void
    {
        $adapter = $this->mysql('logs');
        $cfg = $adapter->debugConfig();

        $this->assertNotEmpty($cfg->profile);
        $this->assertSame('logs', $cfg->profile);
    }

    // -------------------------------------------------------------
    // 3) Doctrine URL DSN support
    // -------------------------------------------------------------
    public function testDoctrineUrlIfPresent(): void
    {
        $adapter = $this->mysql('analytics');
        $cfg = $adapter->debugConfig();

        if ($cfg->dsn) {
            // If DSN exists, must support doctrine URL
            $this->assertTrue(
                str_starts_with($cfg->dsn, 'mysql:') ||
                str_starts_with($cfg->dsn, 'mysql://'),
                'MySQL DSN must be either PDO or Doctrine URL format'
            );
        }

        $this->assertSame('analytics', $cfg->profile);
    }

    // -------------------------------------------------------------
    // 4) Legacy fallback (only when DSN is not provided)
    // -------------------------------------------------------------
    public function testLegacyFallbackWorks(): void
    {
        $adapter = $this->mysql('dev');
        $cfg = $adapter->debugConfig();

        if ($cfg->dsn) {
            $this->assertStringStartsWith('mysql', $cfg->dsn);
            return;
        }

        // Legacy mode (rare, CI usually has DSNs)
        $this->assertNotEmpty($cfg->host);
        $this->assertNotEmpty($cfg->database);
    }

    // -------------------------------------------------------------
    // 5) DBAL Adapter uses same DSN builder logic
    // -------------------------------------------------------------
    public function testDbalAdapterUsesBuilder(): void
    {
        $adapter = $this->mysqlDbal('main');
        $cfg = $adapter->debugConfig();

        $this->assertSame('main', $cfg->profile);
        $this->assertNotEmpty($cfg->dsn);
    }

    // -------------------------------------------------------------
    // 6) Unknown Profile — must work dynamically
    // -------------------------------------------------------------
    public function testUnknownProfileStillResolves(): void
    {
        $adapter = $this->mysql('billing');
        $cfg = $adapter->debugConfig();

        // Adapter must not crash
        $this->assertSame('billing', $cfg->profile);

        // Either DSN or legacy config must exist
        $this->assertTrue(
            !empty($cfg->dsn) || !empty($cfg->host),
            'Unknown profiles must still resolve config'
        );
    }
}
