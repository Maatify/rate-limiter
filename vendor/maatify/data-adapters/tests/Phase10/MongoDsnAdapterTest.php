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

use Maatify\DataAdapters\Adapters\MongoAdapter;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **MongoDsnAdapterTest (Real Integration Version)**
 *
 * ✔ No mocking of $_ENV
 * ✔ Uses real DSN from .env.local or .env.testing
 * ✔ Ensures MongoAdapter resolves DSN via Phase10 DSN-first logic
 * ✔ Compatible with GitHub Actions service (mongo container)
 */
final class MongoDsnAdapterTest extends TestCase
{
    private MongoAdapter $adapter;

    protected function setUp(): void
    {
        // Load real environment via EnvironmentLoader (already done in bootstrap)
        $cfg = new EnvironmentConfig(dirname(__DIR__, 2));

        // Use the "activity" profile (should exist in env)
        $this->adapter = new MongoAdapter($cfg, 'activity');
    }

    public function testMongoResolvesRealDsn(): void
    {
        $cfg = $this->adapter->debugConfig();

        $this->assertNotEmpty(
            $cfg->dsn,
            'Mongo DSN must not be empty (ensure MONGO_ACTIVITY_DSN exists)'
        );

        $this->assertStringStartsWith(
            'mongodb',
            $cfg->dsn,
            'Mongo DSN must start with mongodb:// or mongodb+srv://'
        );
    }
}
