<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/data-adapters
 * @Project     maatify:data-adapters
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-14 15:26
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/data-adapters  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Profiles;

/**
 * 🧩 **Interface ProfileInterface**
 *
 * 🎯 Represents a normalized connection profile identifier used across
 * the Maatify Data-Adapters system.
 *
 * A profile typically corresponds to a database/connection context such as:
 *
 * - `"MAIN"`
 * - `"LOGS"`
 * - `"BILLING"`
 * - `"CACHE"`
 *
 * Implementations (like `AdapterProfile`) ensure:
 * ✔ Validation
 * ✔ Normalization
 * ✔ Safe string value
 *
 * @example
 * ```php
 * $profile = AdapterProfile::from('logs');
 * echo $profile->value(); // "LOGS"
 * ```
 */
interface ProfileInterface
{
    /**
     * Retrieve the normalized profile value as a string.
     *
     * @return string
     */
    public function value(): string;
}
