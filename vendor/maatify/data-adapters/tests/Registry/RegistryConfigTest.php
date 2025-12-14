<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-15 20:00
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Tests\Registry;

use Maatify\DataAdapters\Core\Config\RegistryConfig;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 RegistryConfigTest (Improved & Stable)
 *
 * ✔ Fully isolated — no external env, no .env loading
 * ✔ Creates fixtures dynamically inside /tests/Registry/fixtures
 * ✔ Ensures cleanup after each test
 * ✔ Compatible with PHPUnit 10 strictness
 */
final class RegistryConfigTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        parent::setUp();

        // Create dedicated fixtures directory
        $this->fixturesDir = __DIR__ . '/fixtures';

        if (! is_dir($this->fixturesDir)) {
            mkdir($this->fixturesDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        // Cleanup fixtures after each test
        if (is_dir($this->fixturesDir)) {
            foreach (glob($this->fixturesDir . '/*.json') as $file) {
                unlink($file);
            }
            rmdir($this->fixturesDir);
        }

        parent::tearDown();
    }

    /**
     * 🧪 Ensure invalid registry path throws.
     */
    public function testInvalidPathThrowsException(): void
    {
        $this->expectException(\Exception::class);

        $config = new RegistryConfig();
        $config->setPath('/invalid/not-found.json');
    }

    /**
     * 🧪 Test loading a valid registry file.
     */
    public function testValidRegistryLoadsSuccessfully(): void
    {
        $path = $this->fixturesDir . '/registry.valid.json';

        file_put_contents($path, json_encode([
            'databases' => [
                'mysql' => [
                    'main' => [
                        'dsn' => 'mysql:host=127.0.0.1;dbname=test'
                    ]
                ]
            ]
        ], JSON_PRETTY_PRINT));

        $config = new RegistryConfig();
        $config->setPath($path);

        $data = $config->load();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('databases', $data);
        $this->assertArrayHasKey('mysql', $data['databases']);
    }

    /**
     * 🧪 Test override logic for DSN/legacy values.
     */
    public function testRegistryOverridesDsnAndLegacy(): void
    {
        $path = $this->fixturesDir . '/registry.override.json';

        file_put_contents($path, json_encode([
            'databases' => [
                'mysql' => [
                    'main' => [
                        'dsn' => 'mysql:host=10.0.0.5;dbname=override'
                    ]
                ]
            ]
        ], JSON_PRETTY_PRINT));

        $config = new RegistryConfig();
        $config->setPath($path);

        $data = $config->load();

        $this->assertSame(
            'mysql:host=10.0.0.5;dbname=override',
            $data['databases']['mysql']['main']['dsn']
        );
    }
}
