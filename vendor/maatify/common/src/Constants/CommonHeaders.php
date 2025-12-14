<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:23
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Constants;

/**
 * 📬 **CommonHeaders**
 *
 * 🧩 Defines standardized custom HTTP header names used across
 * all Maatify services and APIs for improved consistency,
 * traceability, and versioning.
 *
 * ✅ Common use cases:
 * - Correlation of requests across microservices
 * - API version management
 * - Logging and tracing
 *
 * @package Maatify\Common\Constants
 *
 * @example
 * ```php
 * use Maatify\Common\Constants\CommonHeaders;
 *
 * header(CommonHeaders::X_REQUEST_ID . ': ' . uniqid());
 * header(CommonHeaders::X_API_VERSION . ': v1.2.0');
 * ```
 */
final class CommonHeaders
{
    /** 🪪 Unique identifier used for tracking and correlating requests */
    public const string X_REQUEST_ID = 'X-Request-Id';

    /** 🧭 Indicates the API version for client-server compatibility */
    public const string X_API_VERSION = 'X-Api-Version';
}
