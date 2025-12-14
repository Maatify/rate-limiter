<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:03
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Text;

/**
 * 🧩 **Class PlaceholderRenderer**
 *
 * 🎯 **Purpose:**
 * Safely replace `{{placeholders}}` within template strings using values from
 * associative arrays — including nested key access like `{{user.name}}` or `{{order.total}}`.
 *
 * 🧠 **Usage Context:**
 * Commonly used in email templates, localization systems, and
 * dynamic content rendering within the Maatify ecosystem.
 *
 * ✅ **Features:**
 * - Supports multi-level keys using dot notation (`{{user.address.city}}`).
 * - Gracefully ignores missing keys (replaces with empty string).
 * - Automatically JSON-encodes non-scalar values (arrays or objects).
 * - Preserves Unicode without escaping characters.
 *
 * ⚙️ **Example:**
 * ```php
 * use Maatify\Common\Text\PlaceholderRenderer;
 *
 * $template = 'Hello {{user.name}}, your order total is {{order.total}} USD.';
 * $data = [
 *     'user' => ['name' => 'Mohamed'],
 *     'order' => ['total' => 99.5]
 * ];
 *
 * echo PlaceholderRenderer::render($template, $data);
 * // Output: Hello Mohamed, your order total is 99.5 USD.
 * ```
 */
final class PlaceholderRenderer
{
    /**
     * 🎨 Render placeholders within a given template.
     *
     * Iterates through all occurrences of `{{key}}` or `{{nested.key}}` inside
     * the provided `$template`, substituting them with corresponding values
     * from the `$data` array.
     *
     * 🧩 Behavior:
     * - If a placeholder path doesn’t exist, it is replaced with an empty string.
     * - Nested paths are resolved recursively (e.g., `user.profile.email`).
     * - Non-scalar values are converted to JSON for readability.
     *
     * @param string              $template The input text containing placeholders.
     * @param array<string,mixed> $data     The data source used for replacement.
     *
     * @return string The rendered string with placeholders replaced.
     */
    public static function render(string $template, array $data): string
    {
        $result = preg_replace_callback(
            '/{{\s*([\w\.]+)\s*}}/',
            static function (array $m) use ($data): string {
                $path  = explode('.', $m[1]);
                $value = $data;

                foreach ($path as $k) {
                    if (!is_array($value) || !array_key_exists($k, $value)) {
                        return '';
                    }
                    $value = $value[$k];
                }

                if (is_scalar($value)) {
                    return (string) $value;
                }

                $json = json_encode($value, JSON_UNESCAPED_UNICODE);

                // json_encode can return false → ensure string every time
                return $json !== false ? $json : '';
            },
            $template
        );

        // preg_replace_callback can return string|null
        return $result ?? $template;
    }
}
