<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-06
 * Time: 01:14
 * Project: maatify-common
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\Common\Traits;

use Maatify\Common\Security\InputSanitizer;

/**
 * 🧩 Trait SanitizesInputTrait
 *
 * Provides a unified and convenient shortcut for safely sanitizing string inputs
 * across any class that uses this trait.
 *
 * Integrates directly with {@see InputSanitizer}, allowing you to clean
 * user-provided or dynamic data with a single line of code.
 *
 * Example usage:
 * ```php
 * class ProductController {
 *     use SanitizesInputTrait;
 *
 *     public function store(array $input): void {
 *         $name = $this->clean($input['name']);         // Text sanitization
 *         $desc = $this->clean($input['description'], 'html'); // Whitelisted HTML
 *     }
 * }
 * ```
 */
trait SanitizesInputTrait
{
    /**
     * 🔹 Quickly sanitize any string using the InputSanitizer helper.
     *
     * @param string $value The input string to sanitize.
     * @param string $mode  Sanitization mode:
     *                      - 'text'   → remove tags, plain text (default)
     *                      - 'html'   → keep safe HTML tags only
     *                      - 'code'   → display safely as <pre><code>
     *                      - 'output' → escape HTML entities for output
     *
     * @return string Clean sanitized string based on selected mode.
     */
    protected function clean(string $value, string $mode = 'text'): string
    {
        return InputSanitizer::sanitize($value, $mode);
    }
}
