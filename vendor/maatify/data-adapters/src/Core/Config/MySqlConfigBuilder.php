<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim
 * @since       2025-11-15
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Core\Config;

use InvalidArgumentException;
use JsonException;
use Maatify\Common\DTO\ConnectionConfigDTO;
use Maatify\DataAdapters\Core\EnvironmentConfig;
use Maatify\DataAdapters\Core\Parser\MysqlDsnParser;

/**
 * 🧩 **MySqlConfigBuilder (Phase 13 — Final)**
 *
 * 🎯 Responsible for producing a **fully resolved MySQL configuration**
 * using the standardized priority chain:
 *
 * ### 🔥 Resolution Priority
 * 1️⃣ **Registry overrides** (`.maatify.registry.json`)
 * 2️⃣ **DSN mode** (PDO or Doctrine-style DSN)
 * 3️⃣ **Legacy environment variables** (`MYSQL_*_HOST`, etc.)
 *
 * ✔ Always returns a **complete** `ConnectionConfigDTO`, including:
 * - host
 * - port
 * - database
 * - user
 * - pass
 * - options
 * - driver
 * - profile
 *
 * 📌 Fully compatible with:
 * - Dynamic profiles
 * - Legacy-only setups
 * - DSN-only setups
 * - Unknown/empty profiles
 *
 * ---
 * ### Example
 * ```php
 * $builder = new MySqlConfigBuilder($envConfig);
 * $config  = $builder->build('main');
 *
 * echo $config->dsn;       // mysql://...
 * echo $config->host;      // 127.0.0.1
 * echo $config->database;  // mydb
 * ```
 * ---
 */
final readonly class MySqlConfigBuilder
{
    /**
     * @param EnvironmentConfig $config The unified environment configuration loader.
     */
    public function __construct(
        private EnvironmentConfig $config
    ) {
    }

    /**
     * 🧠 **Build a fully resolved MySQL profile configuration**
     *
     * Produces a complete and normalized configuration DTO by merging:
     *
     * - DSN values (if provided)
     * - Parsed DSN fields (host, port, user, pass, db)
     * - Legacy values (MYSQL_MAIN_HOST, etc.)
     * - Registry overrides (highest priority)
     *
     * @param string|null $profile The MySQL profile name (e.g., `main`, `logs`).
     *
     * @return ConnectionConfigDTO
     * @throws JsonException When options JSON is malformed.
     */
    /**
     * Build a resolved MySQL configuration for the given profile.
     *
     * @throws InvalidArgumentException  When Doctrine DSN is invalid.
     */
    public function build(?string $profile): ConnectionConfigDTO
    {
        if ($profile === null) {
            return new ConnectionConfigDTO();
        }

        $upper = strtoupper($profile);

        // ---------------------------------------------------------
        // (1) Load DSN (PDO or Doctrine URL)
        // ---------------------------------------------------------
        $dsnKey = "MYSQL_{$upper}_DSN";
        $dsn    = $this->config->get($dsnKey);
        $dsnData = $dsn ? MysqlDsnParser::parse($dsn) : [];

        // ---------------------------------------------------------
        // (1.1) STRICT VALIDATION for Doctrine URL
        // ---------------------------------------------------------
        if ($dsn && str_starts_with($dsn, 'mysql://')) {
            $this->validateDoctrineDsn($dsn, $profile);

            // Doctrine DSN MUST override everything
            return new ConnectionConfigDTO(
                dsn      : $dsn,
                host     : $dsnData['host'] ?? null,
                port     : isset($dsnData['port']) ? (string)$dsnData['port'] : null,
                user     : $dsnData['user'] ?? null,
                pass     : $dsnData['pass'] ?? null,
                database : $dsnData['database'] ?? null,
                options  : isset($dsnData['options']) && is_array($dsnData['options'])
                    ? $dsnData['options']
                    : [],
                driver   : 'dbal',
                profile  : $profile
            );
        }

        // ---------------------------------------------------------
        // (2) Legacy fallback environment values
        // ---------------------------------------------------------
        /** @var array{
         *     dsn: string|null,
         *     host: string|null,
         *     port: string|null,
         *     user: string|null,
         *     pass: string|null,
         *     database: string|null,
         *     options: mixed
         * } $legacy
         */
        $legacy = [
            'dsn'      => null,
            'host'     => $this->config->get("MYSQL_{$upper}_HOST"),
            'port'     => $this->config->get("MYSQL_{$upper}_PORT"),
            'user'     => $this->config->get("MYSQL_{$upper}_USER"),
            'pass'     => $this->config->get("MYSQL_{$upper}_PASS"),
            'database' => $this->config->get("MYSQL_{$upper}_DB"),
        ];

        // Parse OPTIONS JSON (optional)
        $optionsJson = $this->config->get("MYSQL_{$upper}_OPTIONS");
        $legacy['options'] = is_string($optionsJson)
            ? (json_decode($optionsJson, true) ?: [])
            : [];

        // ---------------------------------------------------------
        // (3) Registry → DSN → Legacy priority merge
        // ---------------------------------------------------------
        /** @var array{
         *     dsn: string|null,
         *     host: string|null,
         *     port: string|null|int,
         *     user: string|null,
         *     pass: string|null,
         *     database: string|null,
         *     options: mixed,
         *     driver?: string|null
         * } $merged
         */
        $merged = $this->config->mergeWithRegistry(
            type    : 'mysql',
            profile : $profile,
            dsn     : array_merge(['dsn' => $dsn], $dsnData),
            legacy  : $legacy
        );

        // ---------------------------------------------------------
        // (4) Produce final normalized DTO
        // ---------------------------------------------------------
        /*return new ConnectionConfigDTO(
            dsn      : $merged['dsn']      ?? $dsn,
            host     : $merged['host']     ?? $legacy['host'],
            port     : isset($merged['port']) ? (string)$merged['port'] : $legacy['port'],
            user     : $merged['user']     ?? $legacy['user'],
            pass     : $merged['pass']     ?? $legacy['pass'],
            database : $merged['database'] ?? $legacy['database'],
            options  : $merged['options']  ?? $legacy['options'],
            driver   : $merged['driver']   ?? 'pdo',
            profile  : $profile
        );*/
        $port = $merged['port'] ?? $legacy['port'];
        $port = is_scalar($port) ? (string)$port : null;

        return new ConnectionConfigDTO(
            dsn      : is_string($merged['dsn']) ? $merged['dsn'] : $dsn,
            host     : is_string($merged['host']) ? $merged['host'] : $legacy['host'],
            port     : $port,
            user     : is_string($merged['user']) ? $merged['user'] : $legacy['user'],
            pass     : is_string($merged['pass']) ? $merged['pass'] : $legacy['pass'],
            database : is_string($merged['database']) ? $merged['database'] : $legacy['database'],
            options  : is_array($merged['options'])
                ? $merged['options']
                : (is_array($legacy['options']) ? $legacy['options'] : []),
            driver   : is_string($merged['driver'] ?? null) ? $merged['driver'] : 'pdo',
            profile  : $profile
        );

    }

    /**
     * 💂‍♂️ Strict validation for Doctrine-style DSN (mysql://user:pass@host:port/db)
     *
     * @throws InvalidArgumentException
     */
    private function validateDoctrineDsn(string $dsn, string $profile): void
    {
        $parts = MysqlDsnParser::parse($dsn);

        if (!is_array($parts)) {
            throw new \InvalidArgumentException(
                "Invalid Doctrine MySQL DSN for profile '{$profile}'. Failed to parse DSN: {$dsn}"
            );
        }

        $required = ['host', 'port', 'user', 'pass', 'database'];

        foreach ($required as $key) {
            if (empty($parts[$key])) {
                throw new \InvalidArgumentException(
                    "Invalid Doctrine DSN for profile '{$profile}': missing '{$key}' in DSN: {$dsn}"
                );
            }
        }
    }
}
