<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:08
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Text;

use Maatify\Common\Text\TextFormatter;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class TextFormatterTest**
 *
 * 🎯 **Purpose:**
 * Tests the functionality of {@see TextFormatter}, ensuring correct normalization,
 * slug generation, and case conversion across various text formats and locales.
 *
 * 🧠 **Covers:**
 * - Slug generation (special characters, whitespace, punctuation).
 * - Title casing with UTF-8 safety.
 * - Normalization of diacritics and special characters.
 *
 * ✅ **Usage:**
 * Run via PHPUnit:
 * ```bash
 * vendor/bin/phpunit --filter TextFormatterTest
 * ```
 */
final class TextFormatterTest extends TestCase
{
    /**
     * 🧱 **Test slug generation.**
     *
     * 🧩 Verifies that multiple spaces and special characters are properly replaced
     * with hyphens (`-`) and output is lowercase and trimmed.
     *
     * Example:
     * ```php
     * TextFormatter::slugify('Hello   World!!');
     * // Expected: "hello-world"
     * ```
     *
     * @return void
     */
    public function testSlugify(): void
    {
        $this->assertSame('hello-world', TextFormatter::slugify('Hello   World!!'));
    }

    /**
     * 🔠 **Test title case conversion.**
     *
     * 🧠 Ensures that each word’s first character is capitalized while the rest
     * remain lowercase — consistent with `MB_CASE_TITLE` handling.
     *
     * Example:
     * ```php
     * TextFormatter::titleCase('maatify framework');
     * // Expected: "Maatify Framework"
     * ```
     *
     * @return void
     */
    public function testTitleCase(): void
    {
        $this->assertSame('Maatify Framework', TextFormatter::titleCase('maatify framework'));
    }

    /**
     * 🔤 **Test Unicode normalization.**
     *
     * 🧩 Validates that diacritics (like ä, ö, ü, ß) are properly converted
     * into their ASCII equivalents and spacing is normalized.
     *
     * Example:
     * ```php
     * TextFormatter::normalize('ÄÖÜß Test');
     * // Expected: "aeoeuess-test"
     * ```
     *
     * @return void
     */
    public function testNormalize(): void
    {
        $this->assertSame('aeoeuess-test', TextFormatter::normalize('ÄÖÜß Test'));
    }
}
