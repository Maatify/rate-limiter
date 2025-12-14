<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 23:03
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Validation;

/**
 * 🧠 **Validator**
 *
 * 🧩 A robust and versatile validation utility that provides universal
 * validation methods for primitive types, formatted strings, and common data patterns.
 *
 * ✅ Supports validation for:
 * - **Primitives**: integers, floats, numeric ranges
 * - **Common data**: email, URL, IP, UUID, slug, slug-path, phone numbers
 * - **Auto detection**: smart type recognition (`detectType()`)
 *
 * @package Maatify\Common\Validation
 *
 * @example
 * ```php
 * use Maatify\Common\Validation\Validator;
 *
 * Validator::integer('42');                     // true
 * Validator::float('3.14');                     // true
 * Validator::email('info@maatify.dev');         // true
 * Validator::uuid('550e8400-e29b-41d4-a716-446655440000'); // true
 * Validator::slug('maatify-common');            // true
 * Validator::slugPath('maatify/help/email');    // true
 * Validator::phone('+201001234567');            // true
 * Validator::detectType('https://maatify.dev'); // "url"
 * ```
 */
final class Validator
{
    // ============================================================
    // 🧩 Primitive Validators
    // ============================================================

    /**
     * 🔢 **Validates whether a value is an integer.**
     *
     * Accepts both integer and numeric string representations.
     *
     * @param int|string $value  Value to validate.
     * @return bool              `true` if valid integer, otherwise `false`.
     *
     * @example
     * ```php
     * Validator::integer('123');   // true
     * Validator::integer('12.3');  // false
     * ```
     */
    public static function integer(int|string $value): bool
    {
        return (bool)preg_match('/^-?\d+$/', (string)$value);
    }

    /**
     * 💧 **Validates whether a value is a float (decimal number).**
     *
     * Accepts both float and numeric string representations with optional decimals.
     *
     * @param float|string $value  Value to validate.
     * @return bool                `true` if valid float, otherwise `false`.
     *
     * @example
     * ```php
     * Validator::float('3.14');  // true
     * Validator::float('abc');   // false
     * ```
     */
    public static function float(float|string $value): bool
    {
        return (bool)preg_match('/^-?\d+(\.\d+)?$/', (string)$value);
    }

    /**
     * 🎚️ **Checks whether a numeric value lies within a specified range.**
     *
     * @param int|float $value  Value to test.
     * @param int|float $min    Minimum acceptable value.
     * @param int|float $max    Maximum acceptable value.
     *
     * @return bool             `true` if value is between `$min` and `$max`, inclusive.
     *
     * @example
     * ```php
     * Validator::between(10, 5, 15); // true
     * Validator::between(2, 5, 15);  // false
     * ```
     */
    public static function between(int|float $value, int|float $min, int|float $max): bool
    {
        return $value >= $min && $value <= $max;
    }

    // ============================================================
    // 🔗 Common Data Validators
    // ============================================================

    /**
     * 📧 **Validates email address format.**
     *
     * @param string $value  The email string to validate.
     * @return bool          `true` if valid, otherwise `false`.
     */
    public static function email(string $value): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * 🌍 **Validates URL format.**
     *
     * Supports `http`, `https`, and `ftp` schemes.
     *
     * @param string $value  The URL string to validate.
     * @return bool          `true` if valid, otherwise `false`.
     */
    public static function url(string $value): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_URL);
    }

    /**
     * 🌐 **Validates IPv4 or IPv6 address.**
     *
     * @param string $value  The IP string to validate.
     * @return bool          `true` if valid, otherwise `false`.
     */
    public static function ip(string $value): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_IP);
    }

    /**
     * 🆔 **Validates UUID (versions 1–5).**
     *
     * @param string $value  UUID string to validate.
     * @return bool          `true` if valid UUID, otherwise `false`.
     */
    public static function uuid(string $value): bool
    {
        return (bool)preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $value
        );
    }

    /**
     * 🏷️ **Validates slug format.**
     *
     * Accepts lowercase alphanumeric strings with optional dashes.
     * Must contain at least one letter.
     *
     * @param string $value  Slug string to validate.
     * @return bool          `true` if valid, otherwise `false`.
     *
     * @example
     * ```php
     * Validator::slug('maatify-common'); // true
     * Validator::slug('12345');          // false
     * ```
     */
    public static function slug(string $value): bool
    {
        return (bool)preg_match('/^(?=.*[a-z])[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
    }

    /**
     * 🗂️ **Validates slug path format (e.g., "maatify/help/email").**
     *
     * Requires at least one `/` separator and valid slug segments.
     *
     * @param string $value  Path-like slug string to validate.
     * @return bool          `true` if valid slug path, otherwise `false`.
     *
     * @example
     * ```php
     * Validator::slugPath('maatify/help/email'); // true
     * Validator::slugPath('maatify/');          // false
     * ```
     */
    public static function slugPath(string $value): bool
    {
        return str_contains($value, '/') &&
               (bool)preg_match(
                   '/^(?=.*[a-z])([a-z0-9]+(?:-[a-z0-9]+)*)(?:\/[a-z0-9]+(?:-[a-z0-9]+)*)+$/',
                   $value
               );
    }

    /**
     * 📱 **Validates international phone number format.**
     *
     * Accepts numbers with optional `+` prefix and between 7–15 digits.
     *
     * @param string $value  Phone number to validate.
     * @return bool          `true` if valid, otherwise `false`.
     *
     * @example
     * ```php
     * Validator::phone('+201001234567'); // true
     * Validator::phone('01001234567');   // true
     * ```
     */
    public static function phone(string $value): bool
    {
        return (bool)preg_match('/^\+?[0-9]{7,15}$/', $value);
    }

    // ============================================================
    // 🧠 Composite Helpers
    // ============================================================

    /**
     * 🧭 **Smart type detector.**
     *
     * Tries to infer the data type of a given string
     * and returns a descriptive label like `"email"`, `"url"`, `"uuid"`, `"slug"`, etc.
     *
     * @param string $value  Value to analyze.
     * @return string|null   Type label if recognized, otherwise `null`.
     *
     * @example
     * ```php
     * Validator::detectType('https://maatify.dev'); // "url"
     * Validator::detectType('maatify/help/email');  // "slug_path"
     * Validator::detectType('maatify-core');        // "slug"
     * ```
     */
    public static function detectType(string $value): ?string
    {
        return match (true) {
            self::email($value) => 'email',
            self::uuid($value) => 'uuid',
            self::url($value) => 'url',
            self::ip($value) => 'ip',
            self::integer($value) => 'integer',
            self::float($value) => 'float',
            self::slugPath($value) => 'slug_path',
            self::slug($value) => 'slug',
            default => null,
        };
    }
}
