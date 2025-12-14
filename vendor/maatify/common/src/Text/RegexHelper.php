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

/**
 * 🔍 **Class RegexHelper**
 *
 * 🎯 **Purpose:**
 * Provides safe, readable, and reliable wrappers around PHP's `preg_*` functions.
 * Ensures predictable behavior by returning defaults instead of `false` and avoiding errors.
 *
 * 🧠 **Typical Use Cases:**
 * - Pattern-based sanitization or substitution.
 * - Safe regex matching without runtime warnings.
 * - Readable utility methods for text analysis and validation.
 *
 * ✅ **Features:**
 * - Returns original string on failed replacement instead of `null`.
 * - Simplifies `preg_match` results (returns array or `null`).
 * - Provides `contains()` helper for quick boolean matching.
 *
 * ⚙️ **Example:**
 * ```php
 * use Maatify\Common\Text\RegexHelper;
 *
 * // Replace digits with '#'
 * echo RegexHelper::replace('/\d+/', '#', 'Order123'); // "Order#"
 *
 * // Match pattern
 * $matches = RegexHelper::match('/foo/', 'foobar'); // ['foo']
 *
 * // Quick check
 * if (RegexHelper::contains('/bar/', 'foobar')) {
 *     echo "Contains 'bar'";
 * }
 * ```
 */
final class RegexHelper
{
    /**
     * ✏️ **Perform a safe regex replacement.**
     *
     * Returns the subject unchanged if the regex fails or returns null,
     * ensuring no runtime warnings interrupt execution.
     *
     * @param string $pattern     The regular expression pattern.
     * @param string $replacement The replacement string.
     * @param string $subject     The input string to operate on.
     *
     * @return string The modified string, or the original if replacement fails.
     */
    public static function replace(string $pattern, string $replacement, string $subject): string
    {
        return preg_replace($pattern, $replacement, $subject) ?? $subject;
    }

    /**
     * 🧩 **Match a pattern and return captured groups.**
     *
     * Simplifies `preg_match()` by returning the `$matches` array directly,
     * or `null` if no match is found.
     *
     * @param string $pattern The regex pattern to match.
     * @param string $subject The input string.
     *
     * @return array<int|string, string>|null Array of matches, or null if none.
     */
    public static function match(string $pattern, string $subject): ?array
    {
        return preg_match($pattern, $subject, $matches) ? $matches : null;
    }

    /**
     * 🔎 **Check whether a string matches a given pattern.**
     *
     * Quickly tests for the existence of a match using a boolean result.
     *
     * @param string $pattern The regular expression to evaluate.
     * @param string $subject The string to test against.
     *
     * @return bool True if the pattern matches the subject; otherwise false.
     */
    public static function contains(string $pattern, string $subject): bool
    {
        return (bool)preg_match($pattern, $subject);
    }
}
