<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 23:11
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Validation;

use Maatify\Common\Validation\ArrayHelper;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **ArrayHelperTest**
 *
 * ✅ Unit test suite for {@see ArrayHelper}.
 * Ensures correctness of array manipulation utilities, including:
 * - Flattening nested arrays
 * - Filtering keys with `except()` and `only()`
 * - Accessing nested values with `dot()` notation
 *
 * @package Maatify\Common\Tests\Validation
 *
 * @example
 * ```php
 * $data = ['user' => ['name' => 'Mohamed']];
 * $flat = ArrayHelper::flatten($data);
 * // ➜ ['user.name' => 'Mohamed']
 *
 * $email = ArrayHelper::dot($data, 'user.email', 'unknown@example.com');
 * // ➜ 'unknown@example.com'
 * ```
 */
final class ArrayHelperTest extends TestCase
{
    /**
     * 🧱 Tests {@see ArrayHelper::flatten()}.
     *
     * Ensures nested arrays are converted into single-level dot notation arrays.
     *
     * @return void
     */
    public function testFlatten(): void
    {
        $data = ['user' => ['name' => 'M', 'email' => 'm@maatify.dev']];
        $result = ArrayHelper::flatten($data);

        // ✅ Expect dot-notated keys
        $this->assertSame(['user.name' => 'M', 'user.email' => 'm@maatify.dev'], $result);
    }

    /**
     * 🚫 Tests {@see ArrayHelper::except()}.
     *
     * Ensures that specified keys are removed from the array.
     *
     * @return void
     */
    public function testExcept(): void
    {
        $data = ['a' => 1, 'b' => 2, 'c' => 3];
        $result = ArrayHelper::except($data, ['b']);

        // ✅ Key 'b' should be excluded
        $this->assertSame(['a' => 1, 'c' => 3], $result);
    }

    /**
     * 🎯 Tests {@see ArrayHelper::only()}.
     *
     * Ensures only the specified keys are retained from the array.
     *
     * @return void
     */
    public function testOnly(): void
    {
        $data = ['a' => 1, 'b' => 2, 'c' => 3];
        $result = ArrayHelper::only($data, ['a', 'b']);

        // ✅ Only 'a' and 'b' should remain
        $this->assertSame(['a' => 1, 'b' => 2], $result);
    }

    /**
     * 🔍 Tests {@see ArrayHelper::dot()}.
     *
     * Ensures that nested values can be retrieved using dot notation paths.
     * Verifies fallback to default value when the path does not exist.
     *
     * @return void
     */
    public function testDot(): void
    {
        $data = ['user' => ['profile' => ['email' => 'm@maatify.dev']]];

        // ✅ Existing path should return correct value
        $this->assertSame('m@maatify.dev', ArrayHelper::dot($data, 'user.profile.email'));

        // 🚫 Missing path should return null
        $this->assertNull(ArrayHelper::dot($data, 'user.invalid.key'));
    }
}
