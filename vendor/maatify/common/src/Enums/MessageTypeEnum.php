<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:10
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Enums;

/**
 * 💬 **MessageTypeEnum**
 *
 * 🧩 Represents standardized message types for user feedback, logging,
 * or UI notifications (e.g., banners, alerts, toasts).
 *
 * ✅ Typical use cases:
 * - Flash or session messages in web applications
 * - Logging levels for CLI or console output
 * - API response type labeling
 *
 * @package Maatify\Common\Enums
 *
 * @example
 * ```php
 * use Maatify\Common\Enums\MessageTypeEnum;
 *
 * $type = MessageTypeEnum::SUCCESS;
 * echo $type->value; // "success"
 *
 * switch ($type) {
 *     case MessageTypeEnum::ERROR:
 *         echo "An error occurred.";
 *         break;
 *     case MessageTypeEnum::INFO:
 *         echo "Informational message.";
 *         break;
 * }
 * ```
 */
enum MessageTypeEnum: string
{
    /** ℹ️ Informational message (neutral tone) */
    case INFO = 'info';

    /** ✅ Operation completed successfully */
    case SUCCESS = 'success';

    /** ⚠️ Warning or non-critical issue */
    case WARNING = 'warning';

    /** 🚫 Error or critical failure */
    case ERROR = 'error';
}
