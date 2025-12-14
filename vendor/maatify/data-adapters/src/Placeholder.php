<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm)
 * @since       2025-11-08 20:09
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters;

/**
 * 🧩 **Class Placeholder**
 *
 * 🎯 **Purpose:**
 * Serves as a temporary file to validate autoloading and project structure
 * for the `maatify/data-adapters` library prior to implementing the full
 * adapter ecosystem (Core Interfaces, Adapters, and Base Services).
 *
 * 🧠 **Development Phase Context:**
 * This placeholder ensures Composer’s PSR-4 autoloader and namespace resolution
 * are functioning correctly in early project stages.
 *
 * ⚙️ **Will be replaced in Phase 2:**
 * - Core Interfaces (`AdapterInterface`, `ConnectionInterface`)
 * - Base Structure (`AbstractAdapter`, `ConnectionFactory`)
 * - Full Adapter Implementations (Redis, Mongo, MySQL)
 *
 * ✅ **Example Usage:**
 * ```php
 * use Maatify\DataAdapters\Placeholder;
 *
 * echo Placeholder::hello();
 * // Output: "Maatify DataAdapters initialized."
 * ```
 */
final class Placeholder
{
    /**
     * 🧾 **Simple Hello Message**
     *
     * Returns a short confirmation message used to verify that
     * the Maatify DataAdapters package is successfully autoloaded.
     *
     * @return string Confirmation message.
     */
    public static function hello(): string
    {
        return 'Maatify DataAdapters initialized.';
    }
}
