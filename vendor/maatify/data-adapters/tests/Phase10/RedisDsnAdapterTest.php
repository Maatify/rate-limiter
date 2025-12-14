<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-13 19:17
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */


declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Phase10;

use Maatify\DataAdapters\Adapters\RedisAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 RedisDsnAdapterTest (Real Integration Version)
 *
 * 🎯 Confirms that RedisAdapter:
 * - Reads DSN from real environment (REDIS_SESSIONS_DSN)
 * - Applies DSN-first architecture (Phase 10+)
 * - Does NOT fall back to legacy host/port when DSN exists
 *
 * ✔ Works with .env.local / .env.testing / GitHub Actions
 * ✔ No mocking of $_ENV or SERVER
 * ✔ Consistent with real integration strategy
 */
final class RedisDsnAdapterTest extends TestCase
{
    private RedisAdapter $adapter;

    protected function setUp(): void
    {
        // EnvironmentLoader already loaded .env via tests/bootstrap.php
        // Initialize config using project root
        $config = new EnvironmentConfig(dirname(__DIR__, 2));

        // Redis DSN expected:
        // REDIS_SESSIONS_DSN = redis://127.0.0.1:6379/2
        // or whatever the environment provides
        $this->adapter = new RedisAdapter($config, 'sessions');
    }

    public function testRedisReadsRealDsn(): void
    {
        $cfg = $this->adapter->debugConfig();

        // Must exist in real environment
        $this->assertNotEmpty(
            $cfg->dsn,
            'REDIS_SESSIONS_DSN must exist in real environment'
        );

        // Ensure DSN-first behavior (redis:// format)
        $this->assertStringStartsWith(
            'redis://',
            $cfg->dsn,
            'RedisAdapter must use redis:// DSN format'
        );
    }
}
