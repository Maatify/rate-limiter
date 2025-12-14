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

use InvalidArgumentException;

/**
 * 🧩 **AdapterProfile**
 *
 * A small immutable value object representing a validated and normalized
 * adapter profile used across the **maatify/data-adapters** ecosystem.
 *
 * 🎯 **Purpose**
 * - Validate profile names (allowed: `a-z`, `0-9`, `.`, `_`, `-`)
 * - Normalize consistently across all adapters
 * - Prevent invalid or unsafe profile names
 * - Provide strict typing + immutability
 *
 * ---
 * ### ✔ Examples
 *
 * ```php
 * $p = AdapterProfile::from("logs");
 * echo $p->value();     // "LOGS"
 * ```
 *
 * ```php
 * $p = AdapterProfile::from("mongo.reporting");
 * echo (string)$p;      // "MONGO.REPORTING"
 * ```
 *
 * ❌ Invalid:
 * ```php
 * AdapterProfile::from("bad profile"); // throws InvalidArgumentException
 * ```
 * ---
 */
final readonly class AdapterProfile implements ProfileInterface
{
    /**
     * Internal profile name (always uppercase).
     *
     * @var string
     */
    private string $profile;

    /**
     * @param string $profile Validated and uppercase-normalized profile name
     */
    private function __construct(string $profile)
    {
        $this->profile = $profile;
    }

    /**
     * 🎯 **Create an AdapterProfile from raw input**
     *
     * Processing steps:
     * 1. Trim whitespace
     * 2. Convert to lowercase
     * 3. Validate: allowed characters → `/^[a-z0-9._-]+$/`
     * 4. Convert to uppercase for internal storage
     *
     * @param string $profile Raw profile name (`main`, `cache`, `mysql.logs`, etc.)
     *
     * @return self
     *
     * @throws InvalidArgumentException When invalid characters or empty value is provided
     */
    public static function from(string $profile): self
    {
        $profile = strtolower(trim($profile));

        if ($profile === '') {
            throw new InvalidArgumentException('Profile name cannot be empty.');
        }

        if (! preg_match('/^[a-z0-9._-]+$/', $profile)) {
            throw new InvalidArgumentException(
                "Invalid profile '{$profile}'. Allowed characters: a-z, 0-9, ., _, -"
            );
        }

        return new self(strtoupper($profile));
    }

    /**
     * 🧱 **Return normalized profile string (UPPERCASE).**
     *
     * @return string
     */
    public function value(): string
    {
        return $this->profile;
    }

    /**
     * 🔄 String cast: returns normalized string value.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->profile;
    }
}
