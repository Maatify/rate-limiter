<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:29
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Enums;

use Maatify\Common\Enums\EnumHelper;
use Maatify\Common\Enums\MessageTypeEnum;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **EnumHelperTest**
 *
 * ✅ Tests helper methods that interact with Enums — retrieving names, values,
 * validating existence, and converting to associative arrays.
 *
 * @covers \Maatify\Common\Enums\EnumHelper
 */
final class EnumHelperTest extends TestCase
{
    /**
     * 🔍 Ensures `names()` and `values()` return correct mappings.
     */
    public function testNamesAndValues(): void
    {
        $names = EnumHelper::names(MessageTypeEnum::class);
        $values = EnumHelper::values(MessageTypeEnum::class);

        $this->assertContains('INFO', $names, 'Enum names should include INFO.');
        $this->assertContains('info', $values, 'Enum values should include "info".');
    }

    /**
     * ✅ Verifies that `isValidValue()` correctly detects valid and invalid enum values.
     */
    public function testIsValidValue(): void
    {
        $this->assertTrue(
            EnumHelper::isValidValue(MessageTypeEnum::class, 'info'),
            'Expected "info" to be a valid enum value.'
        );
        $this->assertFalse(
            EnumHelper::isValidValue(MessageTypeEnum::class, 'invalid'),
            'Expected "invalid" to be an invalid enum value.'
        );
    }

    /**
     * 🧩 Ensures `toArray()` returns an associative array with correct name-value mapping.
     */
    public function testToArray(): void
    {
        $array = EnumHelper::toArray(MessageTypeEnum::class);

        $this->assertArrayHasKey('INFO', $array, 'Enum array should have key "INFO".');
        $this->assertEquals('info', $array['INFO'], 'Enum value for INFO should equal "info".');
    }
}
