<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-11 17:35
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Integration;

use Maatify\DataAdapters\Adapters\PredisAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;
use Predis\Client;

final class RealPredisConnectionTest extends TestCase
{
    protected function setUp(): void
    {
        // Force testing environment (Phase 13 requirement)
        $_ENV['APP_ENV'] = 'testing';

        // Provide minimal legacy Redis env for profile "main"
        $_ENV['REDIS_MAIN_HOST'] = '127.0.0.1';
        $_ENV['REDIS_MAIN_PORT'] = '6379';
        $_ENV['REDIS_MAIN_PASS'] = '';
        $_ENV['REDIS_MAIN_DB']   = '0';
    }

    public function testPredisRealConnection(): void
    {
        // Arrange
        $config = new EnvironmentConfig(dirname(__DIR__, 2));
        $adapter = new PredisAdapter($config, 'main');

        // Act
        $adapter->connect();
        $connection = $adapter->getConnection();

        // Assert: Must be a Predis\Client instance
        $this->assertInstanceOf(
            Client::class,
            $connection,
            '❌ Expected Predis\Client instance for Redis connection.'
        );

        // Health Check
        $this->assertTrue(
            $adapter->healthCheck(),
            '❌ PredisAdapter health check must return true.'
        );

        // Ping must return "PONG"
        $this->assertSame(
            'PONG',
            (string)$connection->ping(),
            '❌ Predis PING response mismatch'
        );

        // SET / GET round-trip
        $connection->set('maatify:test', 'connected');
        $this->assertSame(
            'connected',
            $connection->get('maatify:test'),
            '❌ Redis SET/GET round-trip failed.'
        );

        // Cleanup
        $connection->del(['maatify:test']);
    }
}
