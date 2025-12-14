<?php

/**
 * Created by Maatify.dev
 * User: Mohamed Abdulalim (megyptm)
 * Date: 2025-11-06
 * Project: maatify:common
 * @Maatify   Common :: InputSanitizer
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Security;

use HTMLPurifier;
use HTMLPurifier_Config;
use Normalizer;

/**
 * 🧼 Universal input sanitization utility
 *
 * Provides safe sanitization for user and system inputs:
 * - Prevents XSS and hidden Unicode attacks
 * - Supports HTML whitelist and URI blocking
 * - Displays raw HTML safely for debugging
 */
final class InputSanitizer
{
    /**
     * 🧼 Sanitize input for database storage (text only)
     * Removes dangerous HTML tags and invisible characters.
     */
    public static function sanitizeForDB(string $input): string
    {
        // 🔹 Normalize input to a canonical Unicode form to avoid homoglyph attacks
        $input = (string) Normalizer::normalize($input, Normalizer::FORM_C);

        // 🔹 Remove hidden Unicode and control characters
        $input = self::removeInvisibleChars($input);

        // 🚨 Log if input contains suspicious HTML or JS patterns
        if (self::detectSuspiciousInput($input)) {
            error_log('[Security] Suspicious input detected in sanitizeForDB: ' . substr($input, 0, 200));
        }

        // 🔹 Strip all HTML tags and trim whitespace before saving to DB
        return trim(strip_tags($input));
    }

    /**
     * 🧠 Sanitize for safe HTML output (XSS protection)
     * Escapes HTML characters but preserves quotes, emojis, and structure.
     */
    public static function sanitizeForOutput(string $input): string
    {
        // 🔹 Normalize input encoding for consistent sanitization
        $input = (string) Normalizer::normalize($input, Normalizer::FORM_C);

        // 🔹 Escape special characters to prevent XSS in rendered output
        return htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * ⚙️ Sanitize with whitelist of HTML tags (e.g., <b>, <i>, <br>)
     *
     * Supports optional control over external and internal resource URIs.
     *
     * @param   array<int, string>  $allowedTags
     */
    public static function sanitizeWithWhitelist(
        string $input,
        array $allowedTags = ['b', 'i', 'u', 'a[href]', 'br', 'p', 'ul', 'ol', 'li'],
        bool $disableExternalResources = true,
        bool $disableAllResources = false
    ): string {
        // 🔹 Normalize input before filtering
        $input = (string) Normalizer::normalize($input, Normalizer::FORM_C);

        // 🔹 Remove invisible and zero-width characters
        $input = (string) self::removeInvisibleChars($input);

        // ⚙️ Configure HTMLPurifier for strict whitelist filtering
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', implode(',', $allowedTags));

        // Use system temp directory instead of project path
        $config->set('Cache.SerializerPath', sys_get_temp_dir() . '/htmlpurifier');
        //        $config->set('Cache.SerializerPath', __DIR__ . '/../../../storage/purifier_cache');
        $config->set('Cache.DefinitionImpl', null);

        // 🚫 Control external/internal resource URIs
        $config->set('URI.DisableExternalResources', $disableExternalResources);
        $config->set('URI.DisableResources', $disableAllResources);

        // 🧼 Initialize purifier and clean the input
        $purifier = new HTMLPurifier($config);
        $clean = $purifier->purify(trim($input));

        // 🚨 Detect any suspicious or escaped JavaScript remnants
        if (self::detectSuspiciousInput($clean)) {
            error_log('[Security] Suspicious HTML detected in sanitizeWithWhitelist: ' . substr($clean, 0, 200));
        }

        return $clean;
    }

    /**
     * 🧭 Automatically choose the best sanitization method.
     * - If contains HTML → use whitelist sanitizer
     * - Else → plain text sanitizer
     */
    public static function autoSanitize(string $input): string
    {
        // 🔹 Detect HTML tags pattern — if found, clean as HTML
        return preg_match('/<[^>]+>/', $input)
            ? self::sanitizeWithWhitelist($input)
            : self::sanitizeForDB($input);
    }

    /**
     * 💬 Display text as HTML code block (escaped)
     * Ideal for showing raw HTML safely inside <pre><code>.
     */
    public static function displayAsCode(string $input): string
    {
        // 🔹 Escape all HTML tags and entities
        $escaped = htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        // 🔹 Wrap result inside <pre><code> for developer-friendly viewing
        return '<pre><code>' . $escaped . '</code></pre>';
    }

    /**
     * 🧩 Unified sanitization API
     * mode options: 'text' | 'html' | 'code' | 'output'
     */
    public static function sanitize(string $input, string $mode = 'text'): string
    {
        // 🔹 Unified access point — automatically select the right sanitization flow
        return match ($mode) {
            'text' => self::sanitizeForDB($input),
            'html' => self::sanitizeWithWhitelist($input),
            'code' => self::displayAsCode($input),
            'output' => self::sanitizeForOutput($input),
            default => self::sanitizeForDB($input),
        };
    }

    // =======================================================
    // 🔒 Internal helpers
    // =======================================================

    /**
     * 🚫 Remove invisible Unicode & control characters
     * Protects against hidden payloads (e.g. zero-width joiners, byte-order marks)
     */
    private static function removeInvisibleChars(string $input): string
    {
        return (string) preg_replace(
            '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\xAD\x{200B}-\x{200F}\x{202A}-\x{202E}\x{2060}-\x{206F}\x{FEFF}]/u',
            '',
            $input
        );
    }

    /**
     * ⚠️ Detect suspicious HTML / JS patterns
     * Scans for risky elements such as <script>, <iframe>, <object>, or JS event handlers.
     */
    private static function detectSuspiciousInput(string $input): bool
    {
        return (bool)preg_match('/<(script|iframe|embed|object|svg|on\w+)=?/i', $input);
    }
}
