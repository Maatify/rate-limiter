<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:06
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Text;

/**
 * 🛡️ **Class SecureCompare**
 *
 * 🎯 **Purpose:**
 * Provides a constant-time string comparison method to mitigate **timing attacks**
 * commonly used in cryptographic operations and secure authentication flows.
 *
 * 🧠 **How It Works:**
 * - Ensures that comparison time does not vary with string differences.
 * - Prevents attackers from inferring sensitive information through execution timing.
 * - Uses PHP’s built-in `hash_equals()` when available, otherwise falls back to
 *   a manual XOR-based constant-time implementation.
 *
 * ✅ **Features:**
 * - Safe for comparing tokens, API keys, hashes, and passwords.
 * - Compatible with all PHP versions supporting `function_exists()`.
 * - Returns `false` if string lengths differ (prevents early exits).
 *
 * ⚙️ **Example:**
 * ```php
 * use Maatify\Common\Text\SecureCompare;
 *
 * $expected = 'abc123securetoken';
 * $input = $_POST['token'] ?? '';
 *
 * if (SecureCompare::equals($expected, $input)) {
 *     echo '✅ Tokens match.';
 * } else {
 *     echo '🚫 Invalid token.';
 * }
 * ```
 */
final class SecureCompare
{
    /**
     * 🧩 **Constant-time equality check.**
     *
     * Compares two strings without revealing timing differences.
     * Automatically delegates to `hash_equals()` if available.
     *
     * @param string $a The first string.
     * @param string $b The second string.
     *
     * @return bool True if both strings are identical; otherwise false.
     */
    public static function equals(string $a, string $b): bool
    {
        // ✅ Prefer built-in secure comparison when available
        if (function_exists('hash_equals')) {
            return hash_equals($a, $b);
        }

        // 🧮 Manual constant-time comparison fallback
        $lenA = strlen($a);
        $lenB = strlen($b);
        $len  = max($lenA, $lenB);
        $res  = 0;

        // 🔄 Iterate through both strings to equalize timing
        for ($i = 0; $i < $len; $i++) {
            $charA = $a[$i % $lenA] ?? "\0";
            $charB = $b[$i % $lenB] ?? "\0";
            $res  |= ord($charA) ^ ord($charB);
        }

        // ✅ Equal only if all characters match and lengths are identical
        return $res === 0 && $lenA === $lenB;
    }
}
