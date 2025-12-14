<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim
 * @since       2025-11-08
 * @link        https://github.com/Maatify/data-adapters
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core;

use Exception;
use Maatify\Bootstrap\Core\EnvironmentLoader;
use Maatify\Common\DTO\ConnectionConfigDTO;
use Maatify\DataAdapters\Core\Config\MySqlConfigBuilder;
use Maatify\DataAdapters\Core\Config\RegistryConfig;

/**
 * 🧩 **EnvironmentConfig**
 *
 * Central configuration manager for the entire **maatify/data-adapters** package.
 *
 * 🎯 Responsibilities:
 * - Load environment variables when not already loaded by Bootstrap.
 * - Support `APP_ENV=testing` behavior (no `.env` loading).
 * - Provide unified access to environment variables.
 * - Load & manage **database registry** (Phase 13 feature).
 * - Serve configuration builders (MySQL, Redis, Mongo).
 *
 * ---
 * ### 🔍 Environment Loading Rules
 *
 * | Mode                 | Behavior |
 * |----------------------|----------|
 * | `Bootstrap loaded`   | Skip `.env` loading |
 * | `APP_ENV=testing`    | Skip `.env` loading, use only `$_ENV` |
 * | No environment       | Use `EnvironmentLoader` to load `.env` |
 *
 * ---
 * ### 🧪 Example
 * ```php
 * $env = new EnvironmentConfig(__DIR__);
 *
 * $dbConfig = $env->getMySQLConfig('main');
 * echo $dbConfig->host;
 * ```
 * ---
 */
final readonly class EnvironmentConfig
{
    /**
     * Local registry configuration handler.
     *
     * @var RegistryConfig
     */
    private RegistryConfig $registry;

    /**
     * @param string $root Project root directory passed to EnvironmentLoader.
     *
     * @throws Exception
     */
    public function __construct(private string $root)
    {
        /**
         * 🔒 Smart ENV Loader Logic:
         *
         * - If Bootstrap already loaded → APP_ENV exists → skip loading
         * - If running tests → APP_ENV=testing → skip loading
         * - Otherwise → load .env using Bootstrap loader
         */

        $appEnv = $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: null;

        // 🧠 Bootstrap already loaded environment → do nothing
        if ($appEnv && $appEnv !== 'testing') {
            $this->registry = new RegistryConfig();
            $this->initializeRegistry();
            return;
        }

        // 🧪 Testing mode → NEVER load `.env`
        if ($appEnv === 'testing') {
            $this->registry = new RegistryConfig();
            $this->initializeRegistry();
            return;
        }

        // 🟢 No environment loaded yet → load now through Bootstrap loader
        $loader = new EnvironmentLoader($this->root);
        $loader->load();

        // Initialize registry after loading ENV
        $this->registry = new RegistryConfig();
        $this->initializeRegistry();
    }

    /**
     * 🔧 Phase 13 — Initialize registry path from ENV.
     *
     * Attempts to load:
     * ```
     * DB_REGISTRY_PATH=/path/to/databases.json
     * ```
     */
    private function initializeRegistry(): void
    {
        $envPath = $_ENV['DB_REGISTRY_PATH'] ?? getenv('DB_REGISTRY_PATH') ?: null;

        if ($envPath) {
            try {
                $this->registry->setPath($envPath);
            } catch (Exception) {
                // Silent fail — registry is optional by design
            }
        }
    }

    /**
     * 🧠 **Get environment variable with layered priority**
     *
     * Priority:
     * 1. `$_ENV` (in-memory)
     * 2. System environment (`getenv`)
     * 3. Default value
     *
     * Special rule for testing:
     * - If `APP_ENV=testing` → only `$_ENV` is trusted
     *
     * @param string      $key
     * @param string|null $default
     *
     * @return string|null
     */
    public function get(string $key, ?string $default = null): ?string
    {

        // Highest priority — _ENV
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        // System env
        $val = getenv($key);
        if ($val !== false) {
            return $val;
        }

        return $default;
    }

    /**
     * Check whether a key exists in either source.
     */
    public function has(string $key): bool
    {
        return isset($_ENV[$key]) || getenv($key) !== false;
    }

    /**
     * 📦 **Return all environment variables**
     *
     * @return array<string,string>
     */
    public function all(): array
    {
        return $_ENV;
    }

    /**
     * 🧩 Build MySQL Connection Configuration for a profile.
     *
     * @param string|null $profile
     *
     * @return ConnectionConfigDTO
     */
    public function getMySQLConfig(?string $profile): ConnectionConfigDTO
    {
        $profile = $profile ?: 'main'; // 🔥 enforce main-profile default
        $builder = new MySqlConfigBuilder($this);

        return $builder->build($profile);
    }

    /**
     * Set registry path manually at runtime.
     *
     * @param string $path
     * @throws Exception
     */
    public function setRegistryPath(string $path): void
    {
        $this->registry->setPath($path);
    }

    /**
     * Get full path of loaded registry file.
     *
     * @return string|null
     */
    public function getRegistryPath(): ?string
    {
        return $this->registry->getPath();
    }

    /**
     * Load registry JSON file from disk.
     *
     * @return array<string,mixed>
     */
    public function loadRegistry(): array
    {
        return $this->registry->load();
    }

    /**
     * Reload registry (clear cache & reload on next call).
     */
    public function reloadRegistry(): void
    {
        $this->registry->reload();
    }

    /**
     * 🧠 Registry merge logic
     *
     * Merge priority:
     * 1. Legacy (lowest)
     * 2. DSN overrides
     * 3. Registry file (highest)
     *
     * @param string                $type
     * @param string                $profile
     * @param array<string,mixed>   $dsn
     * @param array<string,mixed>   $legacy
     *
     * @return array<string,mixed>
     */
    public function mergeWithRegistry(
        string $type,
        string $profile,
        array $dsn,
        array $legacy
    ): array {
        $registry = $this->loadRegistry();

        // Ensure all offsets are safely typed arrays
        $databases = [];
        if (isset($registry['databases']) && is_array($registry['databases'])) {
            $databases = $registry['databases'];
        }

        $typeBlock = [];
        if (isset($databases[$type]) && is_array($databases[$type])) {
            $typeBlock = $databases[$type];
        }

        $profileBlock = [];
        if (isset($typeBlock[$profile]) && is_array($typeBlock[$profile])) {
            $profileBlock = $typeBlock[$profile];
        }

        /** @var array<string,mixed> $reg */
        $reg = $profileBlock;

        // All operands guaranteed to be arrays<string,mixed>
        return array_merge($legacy, $dsn, $reg);
    }

}
