<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:28
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Enums;

use Maatify\Common\Enums\ErrorCodeEnum;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **ErrorCodeEnumTest**
 *
 * ✅ Ensures `ErrorCodeEnum` defines distinct, non-duplicated error code values.
 *
 * @covers \Maatify\Common\Enums\ErrorCodeEnum
 */
final class ErrorCodeEnumTest extends TestCase
{
    /**
     * 🔍 Verifies that all enum case values are unique.
     *
     * @return void
     */
    public function testEnumValuesAreUnique(): void
    {
        $values = array_map(static fn ($case) => $case->value, ErrorCodeEnum::cases());
        $this->assertCount(
            count(array_unique($values)),
            $values,
            'ErrorCodeEnum values must be unique and non-repeating.'
        );
    }
}
