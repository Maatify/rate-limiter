<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:31
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  View project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Enums;

use Maatify\Common\Enums\ErrorCodeEnum;
use Maatify\Common\Enums\MessageTypeEnum;
use Maatify\Common\Enums\TextDirectionEnum;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **EnumConsistencyTest**
 *
 * ✅ Verifies structural consistency across all common Enums.
 * Ensures all enums:
 * - Use string values
 * - Have unique names
 *
 * @covers \Maatify\Common\Enums\TextDirectionEnum
 * @covers \Maatify\Common\Enums\MessageTypeEnum
 * @covers \Maatify\Common\Enums\ErrorCodeEnum
 */
final class EnumConsistencyTest extends TestCase
{
    /**
     * 🧩 Ensures all enum case values are strings.
     *
     * @return void
     */
    public function testEnumCasesHaveStringValues(): void
    {
        $enumClasses = [
            TextDirectionEnum::class,
            MessageTypeEnum::class,
            ErrorCodeEnum::class,
        ];

        foreach ($enumClasses as $enum) {
            foreach ($enum::cases() as $case) {
                $this->assertIsString(
                    $case->value,
                    sprintf('Enum %s::%s should have a string value.', $enum, $case->name)
                );
            }
        }
    }

    /**
     * 🧠 Ensures all enum case names are unique within each enum.
     *
     * @return void
     */
    public function testEnumNamesAreUnique(): void
    {
        $enumClasses = [
            TextDirectionEnum::class,
            MessageTypeEnum::class,
            ErrorCodeEnum::class,
        ];

        foreach ($enumClasses as $enum) {
            $names = array_map(static fn ($c) => $c->name, $enum::cases());
            $this->assertCount(
                count(array_unique($names)),
                $names,
                sprintf('Enum %s has duplicate case names.', $enum)
            );
        }
    }
}
