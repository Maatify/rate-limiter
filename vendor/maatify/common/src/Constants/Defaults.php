<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-10 06:24
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Constants;

/**
 * ⚙️ **Defaults**
 *
 * 🧩 Provides centralized default configuration constants used across
 * Maatify projects. These defaults define system-wide fallback values
 * for localization, time, and character encoding.
 *
 * ✅ Commonly used for:
 * - Application bootstrapping
 * - Environment initialization
 * - Fallback configuration in case `.env` values are missing
 *
 * @package Maatify\Common\Constants
 *
 * @example
 * ```php
 * use Maatify\Common\Constants\Defaults;
 *
 * date_default_timezone_set(Defaults::DEFAULT_TIMEZONE);
 * setlocale(LC_ALL, Defaults::DEFAULT_LOCALE);
 * header('Content-Type: text/html; charset=' . Defaults::DEFAULT_CHARSET);
 * ```
 */
final class Defaults
{
    /** 🕒 Default timezone for all date/time operations (Cairo, Egypt) */
    public const string DEFAULT_TIMEZONE = 'Africa/Cairo';

    /** 🌐 Default language locale for text and translation handling */
    public const string DEFAULT_LOCALE = 'en';

    /** 🔤 Default character encoding for web responses and file handling */
    public const string DEFAULT_CHARSET = 'UTF-8';
}
