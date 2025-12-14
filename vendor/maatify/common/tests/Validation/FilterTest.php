<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 23:10
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Validation;

use Maatify\Common\Validation\Filter;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **FilterTest**
 *
 * ✅ Unit test suite for {@see Filter} class.
 * Ensures reliable behavior for array sanitization and filtering helpers.
 *
 * Covers:
 * - Removing empty or null values
 * - Trimming whitespace from strings
 * - Escaping and sanitizing string content
 *
 * @package Maatify\Common\Tests\Validation
 *
 * @example
 * ```php
 * $cleaned = Filter::sanitizeArray(['bio' => '<script>x</script>']);
 * $this->assertSame('&lt;script&gt;x&lt;/script&gt;', $cleaned['bio']);
 * ```
 */
final class FilterTest extends TestCase
{
    /**
     * 🧽 Tests {@see Filter::removeEmptyValues()}.
     *
     * Ensures that null, empty strings, and empty arrays
     * are removed from the result while valid values remain.
     *
     * @return void
     */
    public function testRemoveEmptyValues(): void
    {
        $input = ['a' => 'x', 'b' => '', 'c' => null, 'd' => []];
        $filtered = Filter::removeEmptyValues($input);

        // ✅ Only non-empty values should remain
        $this->assertSame(['a' => 'x'], $filtered);
    }

    /**
     * ✂️ Tests {@see Filter::trimArray()}.
     *
     * Ensures that leading and trailing whitespace
     * is removed from all string values while preserving non-string types.
     *
     * @return void
     */
    public function testTrimArray(): void
    {
        $input = ['a' => '  x ', 'b' => ' y', 'c' => 42];
        $trimmed = Filter::trimArray($input);

        // ✅ Strings trimmed, non-strings untouched
        $this->assertSame(['a' => 'x', 'b' => 'y', 'c' => 42], $trimmed);
    }

    /**
     * 🧼 Tests {@see Filter::sanitizeArray()}.
     *
     * Verifies that all string values are safely HTML-escaped
     * while numeric and non-string types remain unmodified.
     *
     * @return void
     */
    public function testSanitizeArray(): void
    {
        $input = ['a' => '<b>Hi</b>', 'b' => '"quoted"', 'c' => 5];
        $clean = Filter::sanitizeArray($input);

        // ✅ HTML tags encoded, quotes escaped, integers preserved
        $this->assertSame('&lt;b&gt;Hi&lt;/b&gt;', $clean['a']);
        $this->assertSame('&quot;quoted&quot;', $clean['b']);
        $this->assertSame(5, $clean['c']);
    }
}
