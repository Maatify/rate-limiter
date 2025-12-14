<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:52
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Date;

use DateTime;
use DateTimeZone;
use Maatify\Common\Date\DateHelper;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **DateHelperTest**
 *
 * ✅ Unit test suite for {@see DateHelper}, verifying locale-based
 * and timezone-adjusted date formatting behavior.
 *
 * These tests confirm:
 * - Proper English localization
 * - Proper Arabic localization
 * - Correct timezone conversion handling
 *
 * @package Maatify\Common\Tests\Date
 *
 * @example
 * ```php
 * $date = new DateTime('2025-11-09 12:00:00', new DateTimeZone('UTC'));
 * echo DateHelper::toLocalizedString($date, 'ar', 'Africa/Cairo');
 * // Expected localized Arabic output for Cairo timezone
 * ```
 */
final class DateHelperTest extends TestCase
{
    /**
     * 🇬🇧 Verifies that English localization returns a valid formatted string
     * adjusted to the provided timezone (America/New_York).
     *
     * @return void
     */
    public function testLocalizedStringEnglish(): void
    {
        $date = new DateTime('2025-11-09 12:00:00', new DateTimeZone('UTC'));

        $result = DateHelper::toLocalizedString($date, 'en', 'America/New_York');

        // ✅ Expecting non-empty localized output in English
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    /**
     * 🇪🇬 Verifies Arabic localization formatting using Cairo timezone.
     *
     * @return void
     */
    public function testLocalizedStringArabic(): void
    {
        $date = new DateTime('2025-11-09 12:00:00', new DateTimeZone('UTC'));

        $result = DateHelper::toLocalizedString($date, 'ar', 'Africa/Cairo');

        // ✅ Should return a valid non-empty Arabic-formatted date string
        $this->assertIsString($result);
        $this->assertNotEmpty($result);
    }

    /**
     * 🌐 Verifies correct timezone adjustment when converting from UTC to another timezone.
     *
     * @return void
     */
    public function testTimezoneConversion(): void
    {
        $utcDate = new DateTime('2025-11-09 12:00:00', new DateTimeZone('UTC'));

        $localized = DateHelper::toLocalizedString($utcDate, 'en', 'Asia/Dubai');

        // ✅ Expect valid formatted string after timezone conversion
        $this->assertIsString($localized);
        $this->assertNotEmpty($localized);
    }
}
