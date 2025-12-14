<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:50
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Date;

use DateTime;
use DateTimeZone;
use IntlDateFormatter;

/**
 * 🌍 **DateHelper**
 *
 * 🧩 Utility class for **locale-aware** and **timezone-adjusted** date formatting.
 * It simplifies displaying `DateTime` objects in human-readable formats
 * according to language and regional preferences.
 *
 * 💡 Supports multilingual formatting (`en`, `ar`, `fr`) with automatic
 * locale mapping and timezone conversion.
 *
 * @example
 * ```php
 * use Maatify\Common\Date\DateHelper;
 *
 * $date = new DateTime('2025-11-09 22:30:00', new DateTimeZone('UTC'));
 *
 * echo DateHelper::toLocalizedString($date, 'en', 'Africa/Cairo');
 * // Output: "November 9, 2025 at 12:30 AM"
 *
 * echo DateHelper::toLocalizedString($date, 'ar', 'Africa/Cairo');
 * // Output: "٩ نوفمبر ٢٠٢٥ في ١٢:٣٠ ص"
 * ```
 */
final class DateHelper
{
    /**
     * 📅 **Converts a DateTime object into a localized string representation.**
     *
     * ✅ Automatically adjusts timezone and applies language-based locale.
     * Useful for UI presentation layers that require localized date formats.
     *
     * @param DateTime $date      The date to format.
     * @param string   $lang      The language code (`en`, `ar`, `fr`, etc.).
     * @param string   $timezone  Target timezone identifier (default: `'UTC'`).
     *
     * @return string Localized and human-readable formatted date string.
     *
     * @example
     * ```php
     * $date = new DateTime('2025-11-09 22:30:00', new DateTimeZone('UTC'));
     * echo DateHelper::toLocalizedString($date, 'fr', 'Europe/Paris');
     * // "9 novembre 2025 à 23:30"
     * ```
     */
    public static function toLocalizedString(DateTime $date, string $lang = 'en', string $timezone = 'UTC'): string
    {
        // 🕓 Adjust the DateTime object to the target timezone
        $tz = new DateTimeZone($timezone);
        $date->setTimezone($tz);

        // 🌐 Determine locale mapping based on language
        $locale = match ($lang) {
            'ar' => 'ar_EG',  // Arabic (Egypt)
            'fr' => 'fr_FR',  // French (France)
            default => 'en_US', // English (United States)
        };

        // 🧠 Initialize the international date formatter
        $fmt = new IntlDateFormatter(
            $locale,
            IntlDateFormatter::LONG,  // Use long date format (e.g., "November 9, 2025")
            IntlDateFormatter::SHORT, // Use short time format (e.g., "10:30 PM")
            $timezone
        );

        // ✅ Return the localized formatted string
        return (string) $fmt->format($date);
    }
}
