<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 22:28
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Traits;

use Maatify\Common\Traits\SanitizesInputTrait;
use PHPUnit\Framework\TestCase;

/**
 * 🧠 **SanitizesInputTraitTest**
 *
 * ✅ Unit test suite for {@see SanitizesInputTrait}.
 * Ensures that the `clean()` method correctly sanitizes user input
 * across multiple modes (`text`, `html`, `code`, `output`).
 *
 * Each test checks if potentially unsafe content
 * is transformed as expected by the trait’s internal sanitizer logic.
 *
 * @package Maatify\Common\Tests\Traits
 *
 * @example
 * ```php
 * $tester = new TestSanitizer();
 * echo $tester->publicClean('<b>Hello</b>'); // ➜ "Hello"
 * echo $tester->publicClean('<b>Hello</b>', 'html'); // ➜ "<b>Hello</b>"
 * ```
 */
final class SanitizesInputTraitTest extends TestCase
{
    /**
     * 🧩 Instance of the helper class wrapping {@see SanitizesInputTrait}.
     *
     * @var TestSanitizer
     */
    private TestSanitizer $tester;

    /**
     * ⚙️ Prepare the test environment before each test.
     * Instantiates a fresh {@see TestSanitizer}.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->tester = new TestSanitizer();
    }

    /**
     * 🎯 Tests sanitization in "text" mode.
     * Verifies that script tags are removed but inner text remains intact.
     *
     * @return void
     */
    public function testSanitizeTextMode(): void
    {
        $dirty = "Hello<script>alert('x')</script>World";
        $clean = $this->tester->publicClean($dirty, 'text');

        // ✅ Script tags should be removed, keeping only text content
        $this->assertSame("Helloalert('x')World", $clean);
    }

    /**
     * 🎨 Tests sanitization in "html" mode.
     * Verifies that safe HTML tags (e.g., <b>, <i>) remain, while unsafe ones (e.g., <script>) are stripped.
     *
     * @return void
     */
    public function testSanitizeHtmlMode(): void
    {
        $dirty = "<b>Hello</b><script>alert('x')</script><i>World</i>";
        $clean = $this->tester->publicClean($dirty, 'html');

        // ✅ Safe tags remain, <script> is removed
        $this->assertStringContainsString('<b>Hello</b>', $clean);
        $this->assertStringNotContainsString('<script>', $clean);
    }

    /**
     * 💻 Tests sanitization in "code" mode.
     * Verifies that HTML is escaped and wrapped in <pre><code> blocks for display as code.
     *
     * @return void
     */
    public function testSanitizeCodeMode(): void
    {
        $dirty = '<div>Hello</div>';
        $clean = $this->tester->publicClean($dirty, 'code');

        // ✅ Output should be safely escaped and wrapped as code
        $this->assertStringContainsString('<pre><code>', $clean);
        $this->assertStringContainsString('&lt;div&gt;', $clean);
    }

    /**
     * 🔹 Tests sanitization in "output" mode.
     * Verifies that HTML is escaped (for safe display in plain output contexts).
     *
     * @return void
     */
    public function testSanitizeOutputMode(): void
    {
        $dirty = '<p>Hello & Welcome</p>';
        $clean = $this->tester->publicClean($dirty, 'output');

        // ✅ HTML should be fully escaped and safe for plain output
        $this->assertStringContainsString('&lt;p&gt;', $clean);
        $this->assertStringNotContainsString('<p>', $clean);
    }

    /**
     * ⚙️ Tests default sanitization mode behavior.
     * Ensures that if no mode is provided, "text" is used by default.
     *
     * @return void
     */
    public function testDefaultModeIsText(): void
    {
        $dirty = "Click <a href='x'>here</a>";
        $clean = $this->tester->publicClean($dirty);

        // ✅ Default should behave like "text" mode (links stripped)
        $this->assertSame('Click here', $clean);
    }
}

/**
 * 🧩 **TestSanitizer**
 *
 * A lightweight wrapper class to expose {@see SanitizesInputTrait::clean()}
 * publicly for unit testing.
 *
 * @internal Used only by {@see SanitizesInputTraitTest}.
 */
final class TestSanitizer
{
    use SanitizesInputTrait;

    /**
     * 🔍 Public bridge to test the internal clean() method of the trait.
     *
     * @param string $value  Raw input string.
     * @param string $mode   Sanitization mode (`text`, `html`, `code`, or `output`).
     *
     * @return string        Sanitized output.
     */
    public function publicClean(string $value, string $mode = 'text'): string
    {
        return $this->clean($value, $mode);
    }
}
