<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:05
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Text;

use Transliterator;

/**
 * 🎯 **Class TextFormatter**
 *
 * 🧩 **Purpose:**
 * Provides robust and locale-safe text formatting utilities
 * including trimming, case conversion, normalization, and slug generation.
 *
 * 🧠 **Common Use Cases:**
 * - Cleaning and normalizing user input.
 * - Creating SEO-friendly slugs.
 * - Converting mixed-language text into Latin-compatible strings.
 *
 * ✅ **Features:**
 * - Collapses excessive whitespace.
 * - Supports Unicode-safe title casing.
 * - Converts any string into a URL-safe slug.
 * - Normalizes diacritics and non-Latin characters (via ICU Transliterator).
 *
 * ⚙️ **Example:**
 * ```php
 * use Maatify\Common\Text\TextFormatter;
 *
 * echo TextFormatter::trim("  Hello   World  "); // "Hello World"
 * echo TextFormatter::titleCase("hello world");   // "Hello World"
 * echo TextFormatter::slugify("Mātïfy Dév Tools"); // "maatify-dev-tools"
 * ```
 */
final class TextFormatter
{
    /**
     * ✂️ **Trim and collapse whitespace.**
     *
     * Removes redundant spaces, tabs, and line breaks from a string,
     * replacing them with a single space and trimming edges.
     *
     * @param string $value The input string.
     *
     * @return string The cleaned and trimmed string.
     */
    public static function trim(string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', $value) ?? '');
    }

    /**
     * 🔠 **Convert a string to Title Case.**
     *
     * Converts each word’s first character to uppercase while lowercasing the rest.
     * Handles UTF-8 safely for multilingual inputs.
     *
     * @param string $value The input string.
     *
     * @return string The title-cased version of the input.
     */
    public static function titleCase(string $value): string
    {
        return mb_convert_case(self::trim($value), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * 🧱 **Generate a URL-safe slug from a string.**
     *
     * Converts a string into a clean, lowercase, hyphen-separated identifier suitable
     * for URLs or file names. Automatically normalizes special characters.
     *
     * 🧩 **Behavior:**
     * - Converts non-alphanumeric characters into `-`.
     * - Removes consecutive or leading/trailing hyphens.
     * - Converts all letters to lowercase.
     *
     * @param string $value The raw input text.
     *
     * @return string A normalized, URL-safe slug (e.g., `"maatify-dev-tools"`).
     */
    public static function slugify(string $value): string
    {
        // 🧹 Normalize first to ensure ASCII-safe transformation
        $value = self::normalize($value);
        // preg_replace always returns string|null → cast to string
        $value = (string) preg_replace('/[^a-z0-9]+/i', '-', $value);

        return trim(strtolower($value), '-');
    }

    public static function normalize(string $value): string
    {
        // Manual pre-replacements for German letters & special chars
        $map = [
            'Ä' => 'Ae', 'ä' => 'ae',
            'Ö' => 'Oe', 'ö' => 'oe',
            'Ü' => 'Ue', 'ü' => 'ue',
            'ß' => 'ss',
        ];
        $value = strtr($value, $map);

        // Transliteration (Latin ASCII)
        if (class_exists(Transliterator::class)) {
            $t = Transliterator::create('Any-Latin; Latin-ASCII; Lower()');
            if ($t !== null) {
                $value = (string) $t->transliterate($value);
            }
        }

        // Replace spaces and punctuation with hyphens for uniformity
        $value = (string) preg_replace('/[^a-z0-9]+/i', '-', $value);

        // Normalize case and trim
        return trim(strtolower($value), '-');
    }
}
