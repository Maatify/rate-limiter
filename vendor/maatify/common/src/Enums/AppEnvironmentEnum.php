<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:14
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Enums;

/**
 * 🌍 **AppEnvironmentEnum**
 *
 * 🧩 Defines the application environment mode used for configuration,
 * debugging, and deployment context awareness.
 *
 * Commonly referenced in `.env` files or configuration loaders
 * to control behaviors such as error reporting, caching, and logging verbosity.
 *
 * ✅ Supported environments:
 * - `LOCAL` → Developer machine or test sandbox
 * - `STAGING` → Pre-production testing environment
 * - `PRODUCTION` → Live production deployment
 *
 * @package Maatify\Common\Enums
 *
 * @example
 * ```php
 * use Maatify\Common\Enums\AppEnvironmentEnum;
 *
 * $env = AppEnvironmentEnum::PRODUCTION;
 *
 * if ($env === AppEnvironmentEnum::LOCAL) {
 *     ini_set('display_errors', '1');
 * } else {
 *     ini_set('display_errors', '0');
 * }
 * ```
 */
enum AppEnvironmentEnum: string
{
    /** 💻 Local development environment (debugging enabled, sandbox testing) */
    case LOCAL = 'local';

    /** 🧪 Staging environment (mirrors production setup, used for QA testing) */
    case STAGING = 'staging';

    /** 🚀 Production environment (live deployment, optimized performance) */
    case PRODUCTION = 'production';
}
