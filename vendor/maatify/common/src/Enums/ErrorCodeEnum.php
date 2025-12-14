<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:12
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Enums;

/**
 * 🚨 **ErrorCodeEnum**
 *
 * 🧩 Defines standardized application-wide error codes used in responses,
 * exception handling, and logging to ensure consistency across systems.
 *
 * ✅ Typical use cases:
 * - API error responses
 * - Exception mapping for business logic
 * - Logging and monitoring categorization
 *
 * @package Maatify\Common\Enums
 *
 * @example
 * ```php
 * use Maatify\Common\Enums\ErrorCodeEnum;
 *
 * throw new AppException('User not found', ErrorCodeEnum::NOT_FOUND);
 *
 * if ($error === ErrorCodeEnum::UNAUTHORIZED) {
 *     http_response_code(401);
 * }
 * ```
 */
enum ErrorCodeEnum: string
{
    /** ❌ Validation failed (e.g., invalid form data or missing fields) */
    case VALIDATION_FAILED = 'E_VALIDATION_FAILED';

    /** 🔍 Resource not found (e.g., missing record, invalid ID) */
    case NOT_FOUND = 'E_NOT_FOUND';

    /** 🔒 Unauthorized access attempt or invalid credentials */
    case UNAUTHORIZED = 'E_UNAUTHORIZED';

    /** 💥 Internal server or unexpected system error */
    case SERVER_ERROR = 'E_SERVER_ERROR';

    /** ⚙️ Invalid input or malformed request payload */
    case INVALID_INPUT = 'E_INVALID_INPUT';
}
