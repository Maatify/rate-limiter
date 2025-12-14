<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:35
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Enums;

/**
 * 🎯 Enum AdapterTypeEnum
 *
 * 🧩 Purpose:
 * Defines the supported adapter types within the Maatify Data Adapters ecosystem.
 * Each case represents a specific backend technology used for data connections.
 *
 * ✅ Usage Scenarios:
 * - Used for type-safe adapter resolution (e.g., Redis, MongoDB, MySQL).
 * - Enhances readability and reduces hardcoded string dependencies.
 *
 * ⚙️ Example Usage:
 * ```php
 * use Maatify\DataAdapters\Enums\AdapterTypeEnum;
 *
 * $type = AdapterTypeEnum::Redis;
 * echo $type->value; // Outputs: 'redis'
 * ```
 *
 * @package Maatify\DataAdapters\Enums
 */
enum AdapterTypeEnum: string
{
    /** 🧱 Redis adapter using native PHP extension */
    case REDIS   = 'redis';

    /** ⚙️ Predis adapter (pure PHP fallback for environments without Redis extension) */
    case PREDIS  = 'predis';

    /** 🧩 MongoDB adapter for NoSQL document-based storage */
    case MONGO   = 'mongo';

    /** 🗄️ MySQL adapter for relational database connections */
    case MYSQL   = 'mysql';
}
