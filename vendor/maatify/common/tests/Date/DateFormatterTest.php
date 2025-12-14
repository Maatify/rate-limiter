<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:51
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Date;

use DateTime;
use Maatify\Common\Date\DateFormatter;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **DateFormatterTest**
 *
 * ✅ Unit tests for {@see DateFormatter}, ensuring consistent behavior of
 * the `humanizeDifference()` method across languages and directions (past/future).
 *
 * The tests cover:
 * - English (past and future) output
 * - Arabic past tense output
 * - Zero-difference detection ("just now")
 *
 * @package Maatify\Common\Tests\Date
 *
 * @example
 * ```php
 * $from = new DateTime('2025-11-09 10:00:00');
 * $to   = new DateTime('2025-11-09 09:00:00');
 * echo DateFormatter::humanizeDifference($from, $to, 'en'); // "1 hour(s) ago"
 * ```
 */
final class DateFormatterTest extends TestCase
{
    /**
     * 🕒 Verifies that the English output correctly displays
     * a past tense phrase (e.g., "1 hour(s) ago").
     *
     * @return void
     */
    public function testHumanizeDifferenceEnglishPast(): void
    {
        $a = new DateTime('2025-11-09 10:00:00');
        $b = new DateTime('2025-11-09 09:00:00');

        $result = DateFormatter::humanizeDifference($a, $b, 'en');

        // ✅ Expecting output to mention "hour" and "ago"
        $this->assertStringContainsString('hour', $result);
        $this->assertStringContainsString('ago', $result);
    }

    /**
     * 🔮 Verifies that the English output correctly represents
     * a future time difference (e.g., "in 1 hour(s)").
     *
     * @return void
     */
    public function testHumanizeDifferenceEnglishFuture(): void
    {
        $a = new DateTime('2025-11-09 09:00:00');
        $b = new DateTime('2025-11-09 10:00:00');

        $result = DateFormatter::humanizeDifference($a, $b, 'en');

        // ✅ Output should contain "in" to indicate future tense
        $this->assertStringContainsString('in', $result);
    }

    /**
     * 🇪🇬 Verifies that Arabic localization correctly handles
     * past tense phrasing (e.g., "منذ ساعة").
     *
     * @return void
     */
    public function testHumanizeDifferenceArabicPast(): void
    {
        $a = new DateTime('2025-11-09 10:00:00');
        $b = new DateTime('2025-11-09 09:00:00');

        $result = DateFormatter::humanizeDifference($a, $b, 'ar');

        // ✅ Arabic output should contain "منذ"
        $this->assertStringContainsString('منذ', $result);
    }

    /**
     * ⏳ Ensures that when both timestamps are equal,
     * the output correctly returns "just now" (or localized equivalent).
     *
     * @return void
     */
    public function testHumanizeDifferenceJustNow(): void
    {
        $a = new DateTime('2025-11-09 10:00:00');
        $b = new DateTime('2025-11-09 10:00:00');

        $result = DateFormatter::humanizeDifference($a, $b, 'en');

        // ✅ No difference should result in "just now"
        $this->assertSame('just now', $result);
    }
}
