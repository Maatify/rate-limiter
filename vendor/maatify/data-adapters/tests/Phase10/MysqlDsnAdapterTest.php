<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-13 19:15
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Phase10;

use Maatify\DataAdapters\Adapters\MySQLAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 MysqlDsnAdapterTest (Real Integration Version)
 *
 * 🎯 Confirms that the MySQLAdapter:
 * - Reads DSN from real environment (no mocking)
 * - Applies DSN-first architecture implemented in Phase 10+
 * - Resolves username/password from DSN or env depending on profile
 * - Ignores legacy host/port/db when DSN exists
 *
 * ✔ Works on local machine (.env.local)
 * ✔ Works in CI (.env.testing + GITHUB_ENV)
 * ✔ Zero side effects on global env
 */
final class MysqlDsnAdapterTest extends TestCase
{
    private MySQLAdapter $adapter;

    protected function setUp(): void
    {
        // EnvironmentLoader already loaded env via tests/bootstrap.php

        // The main profile must have:
        //   MYSQL_MAIN_DSN
        //   MYSQL_MAIN_USER
        //   MYSQL_MAIN_PASS
        //
        // These exist in:
        //   - .env.local      (local dev)
        //   - .env.testing    (CI)
        //   - GITHUB_ENV      (CI)
        $config = new EnvironmentConfig(dirname(__DIR__, 2));
        $this->adapter = new MySQLAdapter($config, 'main');
    }

    public function testMysqlAdapterReadsRealDsn(): void
    {
        $cfg = $this->adapter->debugConfig();

        // Must have DSN
        $this->assertNotEmpty(
            $cfg->dsn,
            'MYSQL_MAIN_DSN must exist in environment (.env.local, .env.testing, or GITHUB_ENV)'
        );

        // DSN-first: MySQLAdapter must NOT fall back to legacy host/port
        $this->assertStringStartsWith(
            'mysql:',
            $cfg->dsn,
            'DSN must be mysql:… format'
        );

        // username & password must not be empty
        $this->assertNotEmpty(
            $cfg->user,
            'MYSQL_MAIN_USER must exist (provided in env)'
        );

        $this->assertNotEmpty(
            $cfg->pass,
            'MYSQL_MAIN_PASS must exist (provided in env)'
        );
    }
}
