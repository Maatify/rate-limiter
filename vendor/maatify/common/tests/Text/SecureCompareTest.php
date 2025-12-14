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

use Maatify\Common\Text\SecureCompare;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class SecureCompareTest**
 *
 * 🎯 **Purpose:**
 * Unit tests for {@see SecureCompare}, verifying correctness and reliability
 * of constant-time string comparison to prevent timing attacks.
 *
 * 🧠 **Test Objectives:**
 * - Ensure identical strings return `true`.
 * - Ensure differing strings return `false`.
 * - Validate consistent behavior independent of input complexity or timing.
 *
 * ✅ **Usage:**
 * ```bash
 * vendor/bin/phpunit --filter SecureCompareTest
 * ```
 */
final class SecureCompareTest extends TestCase
{
    /**
     * ✅ **Test equality for identical strings.**
     *
     * 🧩 Ensures that `equals()` returns `true` when both strings
     * contain exactly the same sequence of characters.
     *
     * Example:
     * ```php
     * SecureCompare::equals('abc123', 'abc123'); // true
     * ```
     *
     * @return void
     */
    public function testEqualsReturnsTrueForSameStrings(): void
    {
        $this->assertTrue(SecureCompare::equals('abc123', 'abc123'));
    }

    /**
     * 🚫 **Test inequality for differing strings.**
     *
     * 🧩 Ensures that `equals()` correctly identifies non-matching strings.
     * Useful to verify safe, predictable comparison behavior.
     *
     * Example:
     * ```php
     * SecureCompare::equals('abc123', 'abc124'); // false
     * ```
     *
     * @return void
     */
    public function testEqualsReturnsFalseForDifferentStrings(): void
    {
        $this->assertFalse(SecureCompare::equals('abc123', 'abc124'));
    }

    /**
     * 🛡️ **Test constant-time behavior equivalence.**
     *
     * 🧠 This test doesn’t measure actual time differences but ensures
     * deterministic results when comparing identical strings,
     * verifying that timing-based discrepancies don’t affect correctness.
     *
     * Example:
     * ```php
     * $a = 'token_value';
     * $b = 'token_value';
     * SecureCompare::equals($a, $b); // true
     * ```
     *
     * @return void
     */
    public function testConstantTimeLikeBehavior(): void
    {
        $a = 'token_value';
        $b = 'token_value';

        $this->assertTrue(SecureCompare::equals($a, $b));
    }
}
