<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:22
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Security;

use Maatify\Common\Security\InputSanitizer;
use PHPUnit\Framework\TestCase;

/**
 * 🧠 **InputSanitizerTest**
 *
 * ✅ Comprehensive unit test suite for {@see InputSanitizer}.
 * Ensures secure and consistent sanitization behavior across all supported modes:
 * - Database input cleaning (`sanitizeForDB`)
 * - Safe output escaping (`sanitizeForOutput`)
 * - HTML whitelisting (`sanitizeWithWhitelist`)
 * - Auto detection (`autoSanitize`)
 * - Code presentation (`displayAsCode`)
 *
 * @package Maatify\Common\Tests\Security
 *
 * @example
 * ```php
 * InputSanitizer::sanitize('<b>Hello</b>', 'html'); // ➜ "<b>Hello</b>"
 * InputSanitizer::sanitize('<script>x()</script>', 'text'); // ➜ "x()"
 * ```
 */
final class InputSanitizerTest extends TestCase
{
    /**
     * 🎯 Ensures `sanitizeForDB()` removes HTML tags, scripts, and invisible characters.
     *
     * @return void
     */
    public function testSanitizeForDBRemovesHtmlAndInvisibleChars(): void
    {
        $dirty = "Hello\x00<script>alert('x')</script>World";
        $clean = InputSanitizer::sanitizeForDB($dirty);

        // ✅ Expect no <script> tag and no null byte
        $this->assertStringNotContainsString('<script>', $clean);
        $this->assertSame('Helloalert(\'x\')World', $clean);
    }

    /**
     * 💡 Ensures `sanitizeForOutput()` properly escapes HTML entities
     * to prevent XSS in plain output contexts.
     *
     * @return void
     */
    public function testSanitizeForOutputEscapesHtmlEntities(): void
    {
        $dirty = '<b>Test</b><script>alert(1)</script>';
        $escaped = InputSanitizer::sanitizeForOutput($dirty);

        // ✅ HTML entities must be escaped
        $this->assertStringContainsString('&lt;b&gt;', $escaped);
        $this->assertStringNotContainsString('<script>', $escaped);
    }

    /**
     * 🧩 Ensures `sanitizeWithWhitelist()` keeps only allowed tags.
     *
     * @return void
     */
    public function testSanitizeWithWhitelistAllowsBasicTags(): void
    {
        $dirty = '<b>bold</b> <i>italic</i> <script>x()</script>';
        $safe = InputSanitizer::sanitizeWithWhitelist($dirty, ['b', 'i']);

        // ✅ Allowed tags are preserved, scripts are stripped
        $this->assertStringContainsString('<b>bold</b>', $safe);
        $this->assertStringContainsString('<i>italic</i>', $safe);
        $this->assertStringNotContainsString('<script>', $safe);
    }

    /**
     * 🛡️ Ensures `sanitizeWithWhitelist()` removes external resources (like <img src="http://...">)
     * even if they are within allowed tags.
     *
     * @return void
     */
    public function testSanitizeWithWhitelistDisablesExternalResources(): void
    {
        $dirty = '<img src="http://evil.com/x.png"><b>safe</b>';
        $clean = InputSanitizer::sanitizeWithWhitelist($dirty, ['b', 'img[src]']);

        // Allowed safe content should remain
        $this->assertStringContainsString('<b>safe</b>', $clean);

        // Malicious external resource should be completely removed
        $this->assertStringNotContainsString('evil.com', $clean);

        // Ensure sanitizer produced valid output (not empty, not NULL)
        $this->assertIsString($clean);
        $this->assertGreaterThan(0, strlen($clean));
    }

    /**
     * 🔍 Ensures `autoSanitize()` automatically detects HTML or plain text input
     * and sanitizes accordingly.
     *
     * @return void
     */
    public function testAutoSanitizeDetectsHtmlAutomatically(): void
    {
        $textOnly = 'Hello World';
        $htmlInput = '<b>Hello</b>';

        // ✅ Plain text should remain unchanged
        $this->assertSame('Hello World', InputSanitizer::autoSanitize($textOnly));

        // ✅ HTML input should be safely returned
        $this->assertStringContainsString('<b>Hello</b>', InputSanitizer::autoSanitize($htmlInput));
    }

    /**
     * 💻 Ensures `displayAsCode()` properly escapes HTML
     * and wraps output inside `<pre><code>` blocks.
     *
     * @return void
     */
    public function testDisplayAsCodeEscapesHtml(): void
    {
        $dirty = '<div>Hello</div>';
        $code = InputSanitizer::displayAsCode($dirty);

        // ✅ Output should be wrapped and safely encoded
        $this->assertStringContainsString('&lt;div&gt;Hello&lt;/div&gt;', $code);
        $this->assertStringContainsString('<pre><code>', $code);
    }

    /**
     * 🔹 Ensures that `sanitize()` correctly delegates to the appropriate
     * sanitization method based on the provided mode.
     *
     * @return void
     */
    public function testSanitizeModesMatchExpectedMethods(): void
    {
        $text = '<b>x</b>';

        // ✅ Each mode must correspond to the appropriate internal sanitizer
        $this->assertSame(InputSanitizer::sanitizeForDB($text), InputSanitizer::sanitize($text, 'text'));
        $this->assertSame(InputSanitizer::sanitizeWithWhitelist($text), InputSanitizer::sanitize($text, 'html'));
        $this->assertSame(InputSanitizer::displayAsCode($text), InputSanitizer::sanitize($text, 'code'));
        $this->assertSame(InputSanitizer::sanitizeForOutput($text), InputSanitizer::sanitize($text, 'output'));
    }

    /**
     * ⚙️ Ensures that sanitizing an empty string returns an empty string (no side effects).
     *
     * @return void
     */
    public function testSanitizeHandlesEmptyString(): void
    {
        $this->assertSame('', InputSanitizer::sanitize('', 'text'));
    }

    /**
     * 🧽 Ensures `sanitizeForDB()` removes zero-width characters (e.g., U+200B).
     *
     * @return void
     */
    public function testSanitizeRemovesZeroWidthCharacters(): void
    {
        $dirty = "Hello\u{200B}World";
        $clean = InputSanitizer::sanitizeForDB($dirty);

        // ✅ Invisible zero-width character should be stripped
        $this->assertSame('HelloWorld', $clean);
    }
}
