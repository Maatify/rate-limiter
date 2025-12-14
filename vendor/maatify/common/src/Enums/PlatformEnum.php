<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:13
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Enums;

/**
 * 💻 **PlatformEnum**
 *
 * 🧩 Defines the runtime or client platform where an operation is executed.
 * Commonly used for logging, analytics, authentication context,
 * and platform-specific behavior control.
 *
 * ✅ Supported platforms:
 * - `WEB` → Standard web applications (browsers, dashboards)
 * - `API` → Backend-to-backend or REST API calls
 * - `MOBILE` → Native or hybrid mobile apps
 * - `CLI` → Command-line tools or background jobs
 *
 * @package Maatify\Common\Enums
 *
 * @example
 * ```php
 * use Maatify\Common\Enums\PlatformEnum;
 *
 * $platform = PlatformEnum::MOBILE;
 *
 * switch ($platform) {
 *     case PlatformEnum::WEB:
 *         echo "Request from web interface";
 *         break;
 *     case PlatformEnum::CLI:
 *         echo "Running in command-line mode";
 *         break;
 * }
 * ```
 */
enum PlatformEnum: string
{
    /** 🌐 Web interface — browser-based clients or admin panels */
    case WEB = 'web';

    /** 🔗 API consumer — backend or external integration calls */
    case API = 'api';

    /** 📱 Mobile platform — native Android/iOS or hybrid apps */
    case MOBILE = 'mobile';

    /** ⚙️ Command-Line Interface — scripts, daemons, or cron jobs */
    case CLI = 'cli';
}
