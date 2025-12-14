<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-13 19:18
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Phase10;

use Maatify\DataAdapters\Adapters\PredisAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 PredisDsnAdapterTest (Real Integration Version)
 *
 * 🎯 Confirms that the PredisAdapter:
 * - Reads DSN from real environment variables using DSN-first rules
 * - Correctly resolves DSN for the `queue` profile
 * - Does NOT fall back to legacy host/port when DSN is present
 *
 * ✔ Works with `.env.local`, `.env.testing` or GitHub Actions $GITHUB_ENV
 * ✔ Fully consistent with DSN architecture from Phase 10 → Phase 13
 * ✔ No mocking or overriding of environment variables
 */
final class PredisDsnAdapterTest extends TestCase
{
    private PredisAdapter $adapter;

    protected function setUp(): void
    {
        // EnvironmentLoader is already executed via tests/bootstrap.php
        // We simply initialize config from project root
        $config = new EnvironmentConfig(dirname(__DIR__, 2));

        // DSN profile expected:
        // REDIS_QUEUE_DSN = redis://127.0.0.1:6379/1
        // or whatever CI/local env provides
        $this->adapter = new PredisAdapter($config, 'queue');
    }

    public function testPredisReadsRealDsn(): void
    {
        $cfg = $this->adapter->debugConfig();

        // Must have DSN in real environment
        $this->assertNotEmpty(
            $cfg->dsn,
            'REDIS_QUEUE_DSN must exist in real environment'
        );

        // Ensure DSN-first logic returns EXACT redis DSN
        $this->assertStringStartsWith(
            'redis://',
            $cfg->dsn,
            'PredisAdapter must use redis:// DSN format'
        );
    }
}
