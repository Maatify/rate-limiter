<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-09 00:16
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Integration;

use Exception;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class RealMongoActivityIntegrationTest**
 *
 * 🎯 **Purpose:**
 * Validates the real-world integration between the MongoDB adapter
 * and the overall Maatify Data Adapters ecosystem.
 *
 * 🧠 **Core Verifications:**
 * - Confirms that the {@see DatabaseResolver} successfully resolves the Mongo adapter.
 * - Ensures the adapter establishes a live MongoDB connection.
 * - Verifies that the connection exposes MongoDB client functionality (e.g., `selectDatabase()`).
 *
 * 🧩 **When to Use:**
 * Run this test during integration or pre-deployment phases to confirm
 * MongoDB connectivity and configuration correctness in the target environment.
 *
 * ✅ **Example Execution:**
 * ```bash
 * APP_ENV=testing vendor/bin/phpunit --filter RealMongoActivityIntegrationTest
 * ```
 */
final class RealMongoActivityIntegrationTest extends TestCase
{
    /**
     * 🧩 **Test Mongo Adapter Integration**
     *
     * Ensures that the Mongo adapter:
     *  - Is correctly resolved through {@see DatabaseResolver}.
     *  - Can establish an actual connection to a MongoDB instance.
     *  - Returns a valid MongoDB client with the expected methods.
     *
     * @return void
     * @throws Exception
     */
    public function testMongoIntegrationWithActivity(): void
    {
        // ⚙️ Initialize environment configuration and resolver
        $config = new EnvironmentConfig(__DIR__ . '/../../');
        $resolver = new DatabaseResolver($config);

        // 🧩 Resolve the Mongo adapter
        $mongo = $resolver->resolve('mongo.main');

        // ✅ Establish actual connection
        $mongo->connect();

        // 🧠 Ensure the adapter object is not null
        $this->assertNotNull(
            $mongo,
            '❌ DatabaseResolver returned null for Mongo adapter.'
        );

        // 🔍 Retrieve the underlying MongoDB client
        $client = $mongo->getConnection();

        // ✅ Verify client instance exists
        $this->assertNotNull(
            $client,
            '❌ Mongo adapter returned null connection.'
        );

        // 🧠 Confirm MongoDB client supports essential API methods
        $this->assertTrue(
            method_exists($client, 'selectDatabase'),
            '❌ MongoDB client does not expose selectDatabase() method.'
        );
    }
}
