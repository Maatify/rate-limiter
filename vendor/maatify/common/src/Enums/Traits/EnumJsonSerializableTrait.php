<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:15
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Enums\Traits;

use JsonSerializable;

/**
 * 🧩 **EnumJsonSerializableTrait**
 *
 * 🧠 A simple trait that enables seamless JSON serialization for PHP `enum` types.
 * When applied to an enum, it ensures that the enum’s **value** (not its name)
 * is returned when encoded with `json_encode()`.
 *
 * ✅ Key benefits:
 * - Simplifies API responses and logging
 * - Prevents exposing enum internal names in JSON output
 * - Works automatically with `JsonSerializable`
 *
 * @package Maatify\Common\Enums\Traits
 *
 * @example
 * ```php
 * use Maatify\Common\Enums\Traits\EnumJsonSerializableTrait;
 *
 * enum StatusEnum: string implements JsonSerializable {
 *     use EnumJsonSerializableTrait;
 *
 *     case ACTIVE = 'active';
 *     case INACTIVE = 'inactive';
 * }
 *
 * echo json_encode(StatusEnum::ACTIVE);
 * // ➜ "active"
 * ```
 */
trait EnumJsonSerializableTrait
{
    /**
     * 🎯 Returns the enum's scalar value when serialized to JSON.
     *
     * @return string|int The scalar representation of the enum value.
     */
    public function jsonSerialize(): string|int
    {
        return $this->value;
    }
}
