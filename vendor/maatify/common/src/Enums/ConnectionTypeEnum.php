<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-13 16:18
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Enums;

/**
 * 🧩 **Enum ConnectionTypeEnum**
 *
 * 🎯 **Purpose:**
 * Defines the supported connection types across the Maatify ecosystem.
 * Used to standardize adapter selection and environment configuration references.
 *
 * 🧠 **Key Features:**
 * - Ensures strict typing for connection type values.
 * - Prevents magic strings in adapter logic.
 * - Facilitates polymorphic adapter resolution and dependency injection.
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\Common\Enums\ConnectionTypeEnum;
 *
 * $type = ConnectionTypeEnum::REDIS;
 *
 * if ($type === ConnectionTypeEnum::MYSQL) {
 *     echo "Connecting to MySQL...";
 * }
 * ```
 */
enum ConnectionTypeEnum: string
{
    /** 🔹 MySQL relational database connection type. */
    case MYSQL = 'mysql';

    /** 🔹 MongoDB NoSQL database connection type. */
    case MONGO = 'mongo';

    /** 🔹 Redis in-memory data store connection type. */
    case REDIS = 'redis';

    /**
     * 🧩 Returns the uppercase ENV prefix for this connection type.
     *
     * Example:
     *   ConnectionTypeEnum::MYSQL->envPrefix() → "MYSQL"
     *   ConnectionTypeEnum::MONGO->envPrefix() → "MONGO"
     */
    public function envPrefix(): string
    {
        return strtoupper($this->value);
    }
}
