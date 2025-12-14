<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-11 00:34
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Helpers;

use Maatify\Common\Helpers\TapHelper;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Test Class TapHelperTest**
 *
 * 🎯 **Purpose:**
 * Verifies the functionality of {@see TapHelper::tap()} including:
 * - Returning the same object instance.
 * - Executing the provided callback properly.
 * - Supporting mixed value types safely.
 */
final class TapHelperTest extends TestCase
{
    public function testReturnsSameInstance(): void
    {
        $object = new \stdClass();
        $result = TapHelper::tap($object, function ($value) {
            $value->tested = true;
        });

        $this->assertSame($object, $result, 'TapHelper::tap() should return the same instance.');
        $this->assertTrue($object->tested ?? false, 'Callback should modify the original object.');
    }

    public function testWorksWithScalarValues(): void
    {
        $value = 42;
        $captured = null;

        $result = TapHelper::tap($value, function ($v) use (&$captured) {
            $captured = $v;
        });

        $this->assertSame($value, $result, 'TapHelper::tap() should return the same scalar value.');
        $this->assertSame(42, $captured, 'Callback should receive the correct scalar value.');
    }

    public function testDoesNotAlterOriginalValueUnlessCallbackDoes(): void
    {
        $value = ['key' => 'old'];
        $result = TapHelper::tap($value, fn (&$v) => $v['key'] = 'new');

        $this->assertSame($result['key'], 'new', 'Callback modifications should persist.');
    }
}
