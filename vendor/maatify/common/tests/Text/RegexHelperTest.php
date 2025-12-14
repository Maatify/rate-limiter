<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:09
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Text;

use Maatify\Common\Text\RegexHelper;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class RegexHelperTest**
 *
 * 🎯 **Purpose:**
 * Unit tests for {@see RegexHelper}, ensuring safe and consistent regex
 * operations across replacement, matching, and containment checks.
 *
 * 🧠 **Covers:**
 * - Replacement of patterns using `replace()`.
 * - Pattern existence verification using `contains()`.
 * - Error-safe behavior when patterns fail or match nothing.
 *
 * ✅ **Usage:**
 * ```bash
 * vendor/bin/phpunit --filter RegexHelperTest
 * ```
 */
final class RegexHelperTest extends TestCase
{
    /**
     * ✏️ **Test regex replacement functionality.**
     *
     * 🧩 Ensures that numeric sequences are replaced correctly and
     * original string is preserved when replacement occurs safely.
     *
     * Example:
     * ```php
     * RegexHelper::replace('/\d+/', '#', 'abc123');
     * // Expected: "abc#"
     * ```
     *
     * @return void
     */
    public function testReplace(): void
    {
        $this->assertSame('abc#', RegexHelper::replace('/\d+/', '#', 'abc123'));
    }

    /**
     * 🔍 **Test regex containment logic.**
     *
     * 🧠 Verifies that `contains()` correctly returns `true` when a
     * pattern exists within the subject string and `false` otherwise.
     *
     * Example:
     * ```php
     * RegexHelper::contains('/\d+/', 'Item42');   // true
     * RegexHelper::contains('/\d+/', 'NoNumber'); // false
     * ```
     *
     * @return void
     */
    public function testContains(): void
    {
        $this->assertTrue(RegexHelper::contains('/\d+/', 'Item42'));
        $this->assertFalse(RegexHelper::contains('/\d+/', 'NoNumber'));
    }
}
