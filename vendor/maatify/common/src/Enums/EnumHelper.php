<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:16
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Enums;

use ValueError;

/**
 * 🧩 **EnumHelper**
 *
 * 🧠 A utility class that provides helper methods for working with PHP 8.1+ Enums.
 * It offers convenient functions to extract enum names, values, validate values,
 * and convert enums into associative arrays.
 *
 * ✅ Ideal for:
 * - Serialization and reflection
 * - Validation and data mapping
 * - Admin panels or API responses that need readable enum data
 *
 * @package Maatify\Common\Enums
 *
 * @example
 * ```php
 * use Maatify\Common\Enums\EnumHelper;
 * use Maatify\Common\Enums\MessageTypeEnum;
 *
 * EnumHelper::names(MessageTypeEnum::class);
 * // ➜ ['INFO', 'SUCCESS', 'WARNING', 'ERROR']
 *
 * EnumHelper::values(MessageTypeEnum::class);
 * // ➜ ['info', 'success', 'warning', 'error']
 *
 * EnumHelper::isValidValue(MessageTypeEnum::class, 'success');
 * // ➜ true
 *
 * EnumHelper::toArray(MessageTypeEnum::class);
 * // ➜ ['INFO' => 'info', 'SUCCESS' => 'success', ...]
 * ```
 */
final class EnumHelper
{
    /**
     * 🧱 **Retrieves all case names** of a given enum class.
     *
     * @param string $enumClass  Fully-qualified enum class name.
     * @return string[]          Array of enum case names.
     *
     * @example
     * ```php
     * EnumHelper::names(TextDirectionEnum::class);
     * // ➜ ['LTR', 'RTL']
     * ```
     */
    public static function names(string $enumClass): array
    {
        return array_map(static fn ($case) => $case->name, $enumClass::cases());
    }

    /**
     * 🎯 **Retrieves all case values** of a given enum class.
     *
     * @param string $enumClass  Fully-qualified enum class name.
     * @return array<int, string|int>  List of enum values.
     *
     * @example
     * ```php
     * EnumHelper::values(MessageTypeEnum::class);
     * // ➜ ['info', 'success', 'warning', 'error']
     * ```
     */
    public static function values(string $enumClass): array
    {
        return array_map(static fn ($case) => $case->value, $enumClass::cases());
    }

    /**
     * ✅ **Checks whether a given value exists** in the specified enum.
     *
     * Safely determines if a provided value corresponds to any enum case.
     *
     * @param string        $enumClass  Enum class name.
     * @param string|int    $value      Value to check.
     *
     * @return bool         `true` if valid enum value, otherwise `false`.
     *
     * @example
     * ```php
     * EnumHelper::isValidValue(MessageTypeEnum::class, 'warning'); // true
     * EnumHelper::isValidValue(MessageTypeEnum::class, 'invalid'); // false
     * ```
     */
    public static function isValidValue(string $enumClass, string|int $value): bool
    {
        try {
            $enumClass::from($value);
            return true;
        } catch (ValueError) {
            return false;
        }
    }

    /**
     * 📦 **Converts an enum class to an associative array.**
     *
     * Returns an array mapping enum **case names** to their **values**.
     *
     * @param string $enumClass  Fully-qualified enum class name.
     * @return array<string, string|int>  Enum name-value mapping.
     *
     * @example
     * ```php
     * EnumHelper::toArray(MessageTypeEnum::class);
     * // ➜ ['INFO' => 'info', 'SUCCESS' => 'success', ...]
     * ```
     */
    public static function toArray(string $enumClass): array
    {
        $arr = [];

        foreach ($enumClass::cases() as $case) {
            $arr[$case->name] = $case->value;
        }

        return $arr;
    }
}
