<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-15 00:02
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core\Config;

use Maatify\Common\DTO\ConnectionConfigDTO;
use Maatify\DataAdapters\Core\EnvironmentConfig;

/**
 * 🧩 **Class MongoConfigBuilder**
 *
 * 🎯 Responsible for generating a complete and normalized `ConnectionConfigDTO`
 * for MongoDB profiles.
 * This builder implements the **DSN → Registry → Legacy** prioritized resolution flow
 * used across the Maatify Data Adapter system.
 *
 * ---
 * ### 🔥 Resolution Priority
 * 1️⃣ **Profile-level DSN** (`MONGO_{PROFILE}_DSN`)
 * 2️⃣ **Registry overrides** (in `.maatify.registry.json`)
 * 3️⃣ **Legacy environment variables** (`MONGO_*_HOST`, `MONGO_*_PORT`, etc.)
 *
 * This ensures complete backward compatibility while enabling modern single-line DSN usage.
 *
 * ---
 * ### ✅ Example
 * ```php
 * $builder = new MongoConfigBuilder($envConfig);
 * $config  = $builder->build('logs');
 *
 * echo $config->dsn;        // mongodb://user:pass@127.0.0.1:27017/logs
 * echo $config->database;   // logs
 * ```
 * ---
 */
final readonly class MongoConfigBuilder
{
    /**
     * @param EnvironmentConfig $config  Environment loader instance.
     */
    public function __construct(
        private EnvironmentConfig $config
    ) {
    }

    /**
     * 🧩 **Build full MongoDB configuration for a given profile**
     *
     * Combines:
     * - DSN (primary)
     * - Parsed DSN fields
     * - Legacy fallback values
     * - Registry overrides
     *
     * and produces a final `ConnectionConfigDTO` identical in structure to MySQL builder output.
     *
     * @param string $profile MongoDB profile name (e.g., `main`, `logs`, `analytics`)
     *
     * @return ConnectionConfigDTO Fully normalized configuration DTO.
     */
    public function build(string $profile): ConnectionConfigDTO
    {
        $upper = strtoupper($profile);

        // ---------------------------------------------------------
        // (1) DSN (primary source)
        // ---------------------------------------------------------
        $dsnKey = "MONGO_{$upper}_DSN";
        $dsn    = $this->config->get($dsnKey);
        $dsnData = $dsn ? $this->parseMongoDsn($dsn) : [];

        // ---------------------------------------------------------
        // (2) Legacy fallback values — used when DSN missing
        // ---------------------------------------------------------
        $legacy = [
            'dsn'      => null,
            'host'     => $this->config->get("MONGO_{$upper}_HOST"),
            'port'     => (string) $this->config->get("MONGO_{$upper}_PORT"),
            'user'     => $this->config->get("MONGO_{$upper}_USER"),
            'pass'     => $this->config->get("MONGO_{$upper}_PASS"),
            'database' => $this->config->get("MONGO_{$upper}_DB"),
        ];

        // Options JSON
        $optionsJson = $this->config->get("MONGO_{$upper}_OPTIONS");

        /**
         * @var array{
         *     host: string,
         *     port: string|int,
         *     user: string,
         *     pass: string,
         *     database: string,
         *     options: array<int|string, mixed>
         * } $legacy
         */
        $legacy['options'] = $optionsJson
            ? (json_decode($optionsJson, true) ?: [])
            : [];

        // ---------------------------------------------------------
        // (3) Registry → DSN → Legacy merge
        // ---------------------------------------------------------
        /**
         * @var array{
         *     dsn?: string|null,
         *     host?: string|null,
         *     port?: int|string|null,
         *     user?: string|null,
         *     pass?: string|null,
         *     database?: string|null,
         *     options?: array<int|string, mixed>
         * } $merged
         */
        $merged = $this->config->mergeWithRegistry(
            type    : 'mongo',
            profile : $profile,
            dsn     : array_merge(['dsn' => $dsn], $dsnData),
            legacy  : $legacy
        );

        // ---------------------------------------------------------
        // (4) Build full DTO exactly like MySQLBuilder rules
        // ---------------------------------------------------------
        return new ConnectionConfigDTO(
            dsn      : $merged['dsn']      ?? $dsn,
            host     : $merged['host']     ?? $legacy['host'],
            port     : (string)($merged['port'] ?? $legacy['port']),
            user     : $merged['user']     ?? $legacy['user'],
            pass     : $merged['pass']     ?? $legacy['pass'],
            database : $merged['database'] ?? $legacy['database'],
            options  : $merged['options']  ?? $legacy['options'],
            driver   : 'mongo',
            profile  : $profile
        );
    }

    /**
     * 🧠 **Parse MongoDB DSN string**
     *
     * Extracts:
     * - host
     * - port
     * - username
     * - password
     * - database
     *
     * from DSN formats such as:
     * ```
     * mongodb://user:pass@127.0.0.1:27017/logs
     * mongodb://127.0.0.1:27017/admin
     * ```
     *
     * @param string $dsn MongoDB DSN string.
     *
     * @return array<string, string|null> Parsed DSN fields.
     */
    private function parseMongoDsn(string $dsn): array
    {
        $url = parse_url($dsn);

        return [
            'host'     => $url['host'] ?? null,
            'port'     => isset($url['port']) ? (string)$url['port'] : null,
            'user'     => $url['user'] ?? null,
            'pass'     => $url['pass'] ?? null,
            'database' => isset($url['path']) ? ltrim($url['path'], '/') : null,
        ];
    }
}
