<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-15 19:58
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core\Config;

use Exception;
use JsonException;

/**
 * 🧩 **RegistryConfig**
 *
 * 🎯 Lightweight handler for the `.maatify.registry.json` database registry.
 * It provides:
 *
 * - Safe path validation
 * - Lazy-loading of registry contents
 * - Automatic JSON decoding with exception handling
 * - In-memory caching with optional `reload()`
 *
 * This class is used by the unified builders (MySQL, Mongo, Redis) to apply
 * **registry overrides** as the highest-priority source of configuration.
 *
 * ---
 * ### Example — Loading Registry
 * ```php
 * $registry = new RegistryConfig();
 * $registry->setPath('/project/.maatify.registry.json');
 *
 * $json = $registry->load();
 * print_r($json['databases']);
 * ```
 * ---
 *
 * ### Expected JSON format
 * ```json
 * {
 *   "databases": {
 *     "mysql": { ... },
 *     "mongo": { ... },
 *     "redis": { ... }
 *   }
 * }
 * ```
 */
final class RegistryConfig
{
    /**
     * Cached registry contents.
     *
     * @var array<string,mixed>|null
     */
    private ?array $registry = null;

    /**
     * Absolute file system path to registry file.
     *
     * @var string|null
     */
    private ?string $path = null;

    /**
     * 🗂️ **Set registry file path**
     *
     * Validates:
     * - File must exist
     * - File must be readable
     *
     * @param string $path Raw file path (relative or absolute)
     *
     * @throws Exception If file does not exist or is unreadable
     */
    public function setPath(string $path): void
    {
        $real = realpath($path);

        if (! $real || ! is_readable($real)) {
            throw new Exception("Registry path is invalid or unreadable: {$path}");
        }

        $this->path = $real;

        // 🧹 Force lazy reload on next `load()`
        $this->registry = null;
    }

    /**
     * 🔍 **Get full path to registry file**
     *
     * @return string|null Absolute path or null if no path set
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * 📥 **Load registry JSON into memory**
     *
     * Provides:
     * - Lazy load (first call only)
     * - JSON decoding with error handling
     * - Structural validation (must contain `"databases"`)
     *
     * @return array<string,mixed> Full decoded registry structure
     *
     * @throws JsonException If JSON is invalid
     * @throws Exception If file unreadable or missing required keys
     */
    public function load(): array
    {
        // Return cached version if already loaded
        if ($this->registry !== null) {
            return $this->registry;
        }

        // No registry configured → empty array (always array<string,mixed>)
        if (! $this->path) {
            return $this->registry = [];
        }

        $json = file_get_contents($this->path);
        if ($json === false) {
            throw new Exception("Unable to read registry file: {$this->path}");
        }

        /** @var array<string,mixed>|null $data */
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        // Guarantee array type (avoid mixed/null propagation)
        if (! is_array($data)) {
            throw new Exception('Invalid registry: root JSON structure must be an object');
        }

        if (! array_key_exists('databases', $data)) {
            throw new Exception("Invalid registry format: missing 'databases' root node");
        }

        // Safe assignment: always array<string,mixed>
        $this->registry = $data;

        return $this->registry;
    }

    /**
     * 🔄 **Force registry reload**
     *
     * Clears cached registry contents so the next call to `load()` reads the file again.
     */
    public function reload(): void
    {
        $this->registry = null;
    }
}
