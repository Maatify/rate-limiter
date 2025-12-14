<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-15 20:22
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core\Config;

use JsonException;
use Maatify\Common\DTO\ConnectionConfigDTO;
use Maatify\DataAdapters\Core\EnvironmentConfig;

/**
 * 🧩 **RedisConfigBuilder (Phase 13 Unified)**
 *
 * 🎯 Produces a complete and normalized Redis configuration using:
 *
 * ### 🔥 Priority Chain (Highest → Lowest)
 * 1️⃣ **Registry overrides**
 * 2️⃣ **DSN mode** (`redis://password@host:port/db`)
 * 3️⃣ **Legacy environment variables** (`REDIS_MAIN_HOST`, `REDIS_MAIN_PORT`, etc.)
 *
 * Ensures consistent behavior across all adapter builders (MySQL / Mongo / Redis).
 *
 * ✔ Returns a **full** ConnectionConfigDTO
 * ✔ Supports redis:// parsing
 * ✔ Backward compatible with old legacy env setups
 * ✔ No username support (by design)
 *
 * ---
 * ### Example
 * ```php
 * $builder = new RedisConfigBuilder($env);
 * $cfg = $builder->build('cache');
 *
 * echo $cfg->host;      // 127.0.0.1
 * echo $cfg->database;  // 2
 * echo $cfg->dsn;       // redis://pass@127.0.0.1:6379/2
 * ```
 * ---
 */
final readonly class RedisConfigBuilder
{
    /**
     * @param EnvironmentConfig $config Unified environment loader.
     */
    public function __construct(
        private EnvironmentConfig $config
    ) {
    }

    /**
     * 🧠 **Build Redis configuration for a specific profile**
     *
     * The method:
     * - Extracts DSN (if present)
     * - Parses redis://
     * - Loads legacy values
     * - Applies registry overrides
     * - Returns a complete `ConnectionConfigDTO`
     *
     * @param string $profile Redis profile (e.g., `main`, `cache`, `queue`)
     *
     * @return ConnectionConfigDTO Fully resolved configuration
     * @throws JsonException When invalid JSON is encountered in registry or env
     */
    public function build(string $profile): ConnectionConfigDTO
    {
        $upper  = strtoupper($profile);
        $prefix = "REDIS_{$upper}_";

        // ---------------------------------------------------------------------
        // (1) Legacy fallback (typed strictly)
        // ---------------------------------------------------------------------

        $legacyHost = $this->config->get($prefix . 'HOST');
        $legacyPort = $this->config->get($prefix . 'PORT');
        $legacyPass = $this->config->get($prefix . 'PASS');
        $legacyDb   = $this->config->get($prefix . 'DB');

        $legacy = [
            'dsn'      => null,
            'host'     => is_string($legacyHost) ? $legacyHost : null,
            'port'     => is_scalar($legacyPort) ? (string)$legacyPort : null,
            'pass'     => is_string($legacyPass) ? $legacyPass : null,
            'database' => is_scalar($legacyDb) ? (string)$legacyDb : null,
            'options'  => [],
        ];
        // ---------------------------------------------------------
        // (2) DSN resolution (middle priority)
        // ---------------------------------------------------------
        $dsnKey = $prefix . 'DSN';
        $dsnVal = $this->config->get($dsnKey);

        $dsn = [];
        if (!empty($dsnVal)) {
            $parsed = $this->parseRedisDsn($dsnVal);

            $dsn = [
                'dsn'      => $dsnVal,
                'host'     => $parsed['host'],
                'port'     => $parsed['port'],
                'pass'     => $parsed['pass'],
                'database' => $parsed['db'],
            ];
        }

        // ---------------------------------------------------------
        // (3) Registry (highest priority)
        // ---------------------------------------------------------
        $merged = $this->config->mergeWithRegistry(
            type    : 'redis',
            profile : $profile,
            dsn     : $dsn,
            legacy  : $legacy
        );

        // ---------------------------------------------------------------------
        // (4) Final strict normalization
        // ---------------------------------------------------------------------
        $finalDsn      = isset($merged['dsn']) && is_string($merged['dsn']) ? $merged['dsn'] : $dsnVal;
        $finalHost     = isset($merged['host']) && is_string($merged['host']) ? $merged['host'] : $legacy['host'];
        $finalPort     = isset($merged['port']) && is_scalar($merged['port']) ? (string)$merged['port'] : $legacy['port'];
        $finalPass     = isset($merged['pass']) && is_string($merged['pass']) ? $merged['pass'] : $legacy['pass'];
        $finalDatabase = isset($merged['database']) && is_scalar($merged['database']) ? (string)$merged['database'] : $legacy['database'];

        $options = $merged['options'] ?? $legacy['options'];
        $finalOptions = is_array($options) ? $options : [];

        // ---------------------------------------------------------------------
        // (5) Return DTO exactly matching signature
        // ---------------------------------------------------------------------
        return new ConnectionConfigDTO(
            dsn      : is_string($finalDsn) ? $finalDsn : null,
            host     : $finalHost,
            port     : $finalPort,
            user     : null,
            pass     : $finalPass,
            database : $finalDatabase,
            options  : $finalOptions,
            driver   : 'redis',
            profile  : $profile
        );


    }

    /**
     * 🧩 **Parse `redis://` DSN format**
     *
     * Supports formats like:
     * ```
     * redis://pass@127.0.0.1:6379/2
     * redis://password@host:port
     * redis://127.0.0.1:6379
     * ```
     *
     * Extracts:
     * - host
     * - port
     * - password
     * - database index
     *
     * @param string $dsn Redis DSN string
     *
     *  Parse redis:// DSN safely.
     *
     * @return array{
     *      host: string|null,
     *      port: string|null,
     *      pass: string|null,
     *      db:   string|null
     *  }
     */
    private function parseRedisDsn(string $dsn): array
    {
        $url = parse_url($dsn);

        return [
            'host' => is_string($url['host'] ?? null) ? $url['host'] : null,
            'port' => isset($url['port']) ? (string)$url['port'] : null,
            'pass' => is_string($url['pass'] ?? null) ? $url['pass'] : null,
            'db'   => is_string($url['path'] ?? null) ? ltrim($url['path'], '/') : null,
        ];
    }
}
