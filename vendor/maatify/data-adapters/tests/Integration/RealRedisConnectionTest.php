<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-11 13:50
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Integration;

use Exception;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;
use Redis;

final class RealRedisConnectionTest extends TestCase
{
    protected function setUp(): void
    {
        // Force testing mode to block .env loading
        $_ENV['APP_ENV'] = 'testing';

        // Provide minimum Redis legacy config for profile "main"
        $_ENV['REDIS_MAIN_HOST'] = '127.0.0.1';
        $_ENV['REDIS_MAIN_PORT'] = '6379';
        $_ENV['REDIS_MAIN_PASS'] = '';
        $_ENV['REDIS_MAIN_DB']   = '0';
    }

    /**
     * @throws Exception
     */
    public function testRedisRealConnection(): void
    {
        // Arrange
        $config   = new EnvironmentConfig(dirname(__DIR__, 2));
        $resolver = new DatabaseResolver($config);
        $adapter  = $resolver->resolve('redis.main');  // IMPORTANT after Phase 13

        // Act
        $adapter->connect();
        $connection = $adapter->getConnection();

        // Assert: Must be a phpredis connection
        $this->assertInstanceOf(
            Redis::class,
            $connection,
            'Expected active Redis connection instance.'
        );

        // Health check must pass
        $this->assertTrue(
            $adapter->healthCheck(),
            'RedisAdapter health check must return true.'
        );

        // PING test
        if (method_exists($connection, 'ping')) {
            $pong = $connection->ping();

            $this->assertContains(
                $pong,
                ['PONG', '+PONG', true],
                sprintf('Unexpected Redis PING response: %s', var_export($pong, true))
            );
        }
    }
}
