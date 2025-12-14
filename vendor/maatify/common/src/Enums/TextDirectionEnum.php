<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:09
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Enums;

/**
 * 🔠 **TextDirectionEnum**
 *
 * 🧩 Represents text layout direction for localization or UI rendering.
 * Commonly used in multilingual applications to adjust text alignment,
 * layout mirroring, or CSS styling.
 *
 * ✅ Available options:
 * - `LTR` → Left-To-Right (e.g., English, French)
 * - `RTL` → Right-To-Left (e.g., Arabic, Hebrew)
 *
 * @package Maatify\Common\Enums
 *
 * @example
 * ```php
 * use Maatify\Common\Enums\TextDirectionEnum;
 *
 * $dir = TextDirectionEnum::LTR;
 * echo $dir->value; // "ltr"
 *
 * if ($dir === TextDirectionEnum::RTL) {
 *     echo "Right-to-left language detected.";
 * }
 * ```
 */
enum TextDirectionEnum: string
{
    /** 🔹 Left-To-Right languages (e.g., English, French, German) */
    case LTR = 'ltr';

    /** 🔸 Right-To-Left languages (e.g., Arabic, Hebrew, Urdu) */
    case RTL = 'rtl';
}
