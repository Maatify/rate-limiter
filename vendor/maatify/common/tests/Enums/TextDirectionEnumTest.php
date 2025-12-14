<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:27
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Enums;

use Maatify\Common\Enums\TextDirectionEnum;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **TextDirectionEnumTest**
 *
 * ✅ Ensures that `TextDirectionEnum` constants return correct string values.
 *
 * @covers \Maatify\Common\Enums\TextDirectionEnum
 */
final class TextDirectionEnumTest extends TestCase
{
    /**
     * 🔍 Verifies that each enum case maps to its expected direction value.
     *
     * @return void
     */
    public function testValues(): void
    {
        $this->assertSame('ltr', TextDirectionEnum::LTR->value, 'LTR should equal "ltr"');
        $this->assertSame('rtl', TextDirectionEnum::RTL->value, 'RTL should equal "rtl"');
    }
}
