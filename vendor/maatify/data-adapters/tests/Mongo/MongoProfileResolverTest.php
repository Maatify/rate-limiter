<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-15 00:11
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Mongo;

use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * 🧪 MongoProfileResolverTest (Real Integration Version)
 *
 * 🎯 Confirms that Mongo profiles (main, logs) resolve correctly using:
 * - Real DSN values loaded via EnvironmentLoader
 * - MongoConfigBuilder + Route-based resolution
 *
 * ✔ No mocking of $_ENV
 * ✔ No overriding environment variables
 * ✔ Fully compatible with .env.testing & CI
 */
final class MongoProfileResolverTest extends TestCase
{
    private DatabaseResolver $resolver;

    protected function setUp(): void
    {
        // EnvironmentLoader loads DSN from .env.testing automatically
        $this->resolver = new DatabaseResolver(
            new EnvironmentConfig(dirname(__DIR__, 2))
        );
    }

    /**
     * 🔍 Helper: call protected resolveConfig() inside adapter
     */
    private function callResolveConfig(object $adapter)
    {
        $ref = new ReflectionMethod($adapter, 'resolveConfig');
        $ref->setAccessible(true);

        return $ref->invoke(
            $adapter,
            \Maatify\Common\Enums\ConnectionTypeEnum::MONGO
        );
    }

    /**
     * 🧪 Profile: main
     * Requires in .env.testing:
     *   MONGO_MAIN_DSN=mongodb://127.0.0.1:27017/main
     */
    public function testMainProfileResolution(): void
    {
        $adapter = $this->resolver->resolve('mongo.main');
        $cfg = $this->callResolveConfig($adapter);

        // ensure DSN exists
        $this->assertNotEmpty($cfg->dsn, 'MONGO_MAIN_DSN must be set');

        // ensure DB is parsed correctly
        $this->assertSame('maatify', $cfg->database);
    }

    /**
     * 🧪 Profile: logs
     * Requires in .env.testing:
     *   MONGO_LOGS_DSN=mongodb://127.0.0.1:27017/logs
     */
    public function testLogsProfileResolution(): void
    {
        $adapter = $this->resolver->resolve('mongo.logs');
        $cfg = $this->callResolveConfig($adapter);

        $this->assertNotEmpty($cfg->dsn, 'MONGO_LOGS_DSN must be set');
        $this->assertSame('logs', $cfg->database);
    }
}
