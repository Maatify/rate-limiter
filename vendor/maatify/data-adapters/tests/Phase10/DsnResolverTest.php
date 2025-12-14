<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-13 19:11
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Phase10;

use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * 🧪 **DsnResolverTest (Real Integration Version)**
 *
 * ✔ Uses REAL environment (.env.local or .env.testing)
 * ✔ Verifies DSN-first logic defined in Phase 10
 * ✔ Ensures correct route parsing (mysql.main → type=mysql, profile=main)
 * ✔ Tests that each adapter loads DSN exactly as set in real env
 */
final class DsnResolverTest extends TestCase
{
    private DatabaseResolver $resolver;

    protected function setUp(): void
    {
        /**
         * 🔥 IMPORTANT:
         * - DO NOT OVERRIDE $_ENV
         * - Let EnvironmentLoader (loaded in tests/bootstrap.php) handle env loading
         * - Use project root for EnvironmentConfig
         */
        $config = new EnvironmentConfig(dirname(__DIR__, 2));
        $this->resolver = new DatabaseResolver($config);
    }

    /**
     * 🧪 Validate that parseStringRoute correctly extracts type/profile
     */
    public function testParseStringRouteProfile(): void
    {
        $method = new ReflectionMethod(DatabaseResolver::class, 'parseStringRoute');
        $method->setAccessible(true);

        [$type, $profile] = $method->invoke($this->resolver, 'mysql.main');

        $this->assertSame('mysql', $type);
        $this->assertSame('main', $profile);
    }

    public function testParseStringRouteWithoutProfile(): void
    {
        $method = new ReflectionMethod(DatabaseResolver::class, 'parseStringRoute');
        $method->setAccessible(true);

        [$type, $profile] = $method->invoke($this->resolver, 'redis');

        $this->assertSame('redis', $type);
        $this->assertNull($profile);
    }

    /**
     * 🧪 Ensure DSN-first resolution works for MySQL
     */
    public function testMysqlMainLoadsRealDsn(): void
    {
        $adapter = $this->resolver->resolve('mysql.main');
        $cfg = $adapter->debugConfig();

        $this->assertNotEmpty($cfg->dsn, 'MySQL DSN must not be empty');
        $this->assertStringStartsWith('mysql:', $cfg->dsn);
    }

    public function testMongoLogsLoadsRealDsn(): void
    {
        $adapter = $this->resolver->resolve('mongo.logs');
        $cfg = $adapter->debugConfig();

        $this->assertNotEmpty($cfg->dsn, 'Mongo DSN must not be empty');
        $this->assertStringStartsWith('mongodb', $cfg->dsn);
    }

    public function testRedisCacheLoadsRealDsn(): void
    {
        $adapter = $this->resolver->resolve('redis.cache');
        $cfg = $adapter->debugConfig();

        $this->assertNotEmpty($cfg->dsn, 'Redis DSN must not be empty');
        $this->assertStringStartsWith('redis://', $cfg->dsn);
    }
}
