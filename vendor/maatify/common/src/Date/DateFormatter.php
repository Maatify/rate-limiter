<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:49
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Date;

use DateTimeInterface;

/**
 * 🕒 **DateFormatter**
 *
 * 🧩 Provides human-readable and localized time difference strings
 * (e.g., "5 minutes ago", "in 3 days", "yesterday", or in Arabic "منذ 5 دقائق").
 *
 * This helper focuses on simple humanization of {@see DateTimeInterface} differences.
 * Supports multiple languages (currently English 🇬🇧 and Arabic 🇪🇬).
 *
 * @example
 * ```php
 * use Maatify\Common\Date\DateFormatter;
 *
 * $from = new DateTimeImmutable('2025-11-09 10:00:00');
 * $to   = new DateTimeImmutable('2025-11-09 10:05:00');
 *
 * echo DateFormatter::humanizeDifference($from, $to); // "in 5 minute(s)"
 * echo DateFormatter::humanizeDifference($to, $from); // "5 minute(s) ago"
 * echo DateFormatter::humanizeDifference($from, $to, 'ar'); // "بعد 5 دقيقة"
 * ```
 */
final class DateFormatter
{
    /**
     * 🧠 Converts the time difference between two {@see DateTimeInterface} objects
     * into a human-friendly and optionally localized string.
     *
     * ⚙️ The function checks the first non-zero unit (year → month → day → hour → minute → second)
     * and formats it according to direction (`past` or `future`) and language.
     *
     * @param DateTimeInterface $from  The starting datetime (reference point).
     * @param DateTimeInterface $to    The target datetime to compare against.
     * @param string            $lang  Output language (`en` or `ar`). Defaults to English.
     *
     * @return string Humanized representation of the time difference.
     *
     * @example
     * ```php
     * $from = new DateTimeImmutable('2025-11-09 10:00:00');
     * $to   = new DateTimeImmutable('2025-11-09 12:00:00');
     * echo DateFormatter::humanizeDifference($from, $to); // "in 2 hour(s)"
     * ```
     */
    public static function humanizeDifference(DateTimeInterface $from, DateTimeInterface $to, string $lang = 'en'): string
    {
        // 🧩 Calculate the interval difference between the two datetimes
        $diff = $from->diff($to);

        // ✅ Determine if the target time is in the past or future relative to the reference
        $isPast = $to < $from;

        // 🌐 Unit names supported in English and Arabic
        $units = [
            'y' => ['en' => 'year',   'ar' => 'سنة'],
            'm' => ['en' => 'month',  'ar' => 'شهر'],
            'd' => ['en' => 'day',    'ar' => 'يوم'],
            'h' => ['en' => 'hour',   'ar' => 'ساعة'],
            'i' => ['en' => 'minute', 'ar' => 'دقيقة'],
            's' => ['en' => 'second', 'ar' => 'ثانية'],
        ];

        // 🔁 Iterate through each unit to find the first non-zero value
        foreach ($units as $key => $names) {
            if ($diff->$key > 0) {
                $count = $diff->$key;
                $name  = $names[$lang] ?? $names['en'];

                // 🧠 Build localized output phrase
                $phrase = match ($lang) {
                    'ar' => $isPast
                        ? "منذ $count $name"   // e.g. "منذ 5 دقائق"
                        : "بعد $count $name",  // e.g. "بعد 3 ساعات"
                    default => $isPast
                        ? "$count $name(s) ago" // e.g. "3 day(s) ago"
                        : "in $count $name(s)", // e.g. "in 2 hour(s)"
                };

                return $phrase;
            }
        }

        // ⏳ If no measurable difference (less than 1 second)
        return $lang === 'ar' ? 'الآن' : 'just now';
    }
}
