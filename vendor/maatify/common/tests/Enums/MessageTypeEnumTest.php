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

use Maatify\Common\Enums\MessageTypeEnum;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **MessageTypeEnumTest**
 *
 * ✅ Verifies that all expected message type cases exist and return correct string values.
 *
 * @covers \Maatify\Common\Enums\MessageTypeEnum
 */
final class MessageTypeEnumTest extends TestCase
{
    /**
     * 🧩 Ensures the enum defines all expected message types.
     *
     * @return void
     */
    public function testContainsExpectedCases(): void
    {
        $expected = ['info', 'success', 'warning', 'error'];
        $values = array_map(static fn ($case) => $case->value, MessageTypeEnum::cases());

        $this->assertEqualsCanonicalizing(
            $expected,
            $values,
            'MessageTypeEnum should contain info, success, warning, and error cases.'
        );
    }
}
