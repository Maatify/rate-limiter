<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-13 16:17
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\DTO;

/**
 * 🧩 **Class ConnectionConfigDTO**
 *
 * 🎯 **Purpose:**
 * Defines a unified, immutable Data Transfer Object (DTO) representing
 * connection parameters across all supported data adapters (MySQL, MongoDB, Redis, etc.).
 *
 * 🧠 **Key Features:**
 * - Combines traditional DSN and discrete parameter forms (`host`, `port`, `database`, etc.).
 * - Enables flexible connection initialization for adapters and factories.
 * - Immutable (`readonly`) for configuration safety and predictability.
 * - Supports additional options (e.g., PDO attributes, timeouts, SSL settings).
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\Common\DTO\ConnectionConfigDTO;
 *
 * $config = new ConnectionConfigDTO(
 *     dsn: 'mysql:host=127.0.0.1;dbname=maatify',
 *     host: '127.0.0.1',
 *     port: '3306',
 *     user: 'root',
 *     pass: 'secret',
 *     database: 'maatify',
 *     options: [
 *         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
 *     ],
 *     driver: 'pdo',
 *     profile: 'production'
 * );
 * ```
 *
 * 🧱 **Use Cases:**
 * - Passed into adapter constructors for dynamic connection setup.
 * - Used by `DatabaseResolver` or dependency injection containers to build adapter instances.
 * - Supports multi-environment profiles (e.g., local, staging, production).
 */
final class ConnectionConfigDTO
{
    /**
     * 🧠 **Constructor**
     *
     * Defines complete connection configuration properties, supporting both
     * DSN-based and parameter-based connection schemes.
     *
     * @param   string|null               $dsn       Full connection string (e.g., `"mysql:host=127.0.0.1;dbname=maatify"`).
     * @param   string|null               $host      Hostname or IP of the data source.
     * @param   string|null               $port      Port number (string to maintain type consistency).
     * @param   string|null               $user      Username credential for authentication.
     * @param   string|null               $pass      Password credential for authentication.
     * @param   string|null               $database  Target database name (if applicable).
     * @param   array<int|string, mixed>  $options   Adapter-specific or driver options (e.g., PDO attributes).
     * @param   string|null               $driver    Driver type (e.g., `"pdo"`, `"dbal"`, `"mysqli"`, `"mongo"`, `"redis"`).
     * @param   string|null               $profile   Optional configuration profile (e.g., `"local"`, `"production"`).
     */
    public function __construct(
        public ?string $dsn = null,
        public ?string $host = null,
        public ?string $port = null,
        public ?string $user = null,
        public ?string $pass = null,
        public ?string $database = null,
        public array $options = [],
        public ?string $driver = null,
        public ?string $profile = null,
    ) {
    }
}
