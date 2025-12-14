<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 21:07
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Text;

use Maatify\Common\Text\PlaceholderRenderer;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **Class PlaceholderRendererTest**
 *
 * 🎯 **Purpose:**
 * Unit tests for {@see PlaceholderRenderer}, ensuring correct placeholder substitution,
 * including nested key resolution and graceful handling of missing keys.
 *
 * 🧠 **Test Scope:**
 * - Validates multi-level placeholder replacement (e.g., `{{user.name}}`).
 * - Confirms that missing or undefined keys resolve to empty strings.
 * - Ensures consistent rendering behavior across edge cases.
 *
 * ✅ **Usage:**
 * Executed via PHPUnit as part of the `maatify/common` library’s test suite.
 *
 * ```bash
 * vendor/bin/phpunit --filter PlaceholderRendererTest
 * ```
 */
final class PlaceholderRendererTest extends TestCase
{
    /**
     * ✅ **Test Nested Placeholder Resolution**
     *
     * 🧩 Ensures that placeholders with dot-notation paths such as `{{user.name}}`
     * are correctly resolved to their nested values in the input data.
     *
     * Example:
     * ```php
     * $tpl = 'Hi {{user.name}}, email: {{user.email}}';
     * PlaceholderRenderer::render($tpl, [
     *     'user' => ['name' => 'Ali', 'email' => 'a@ex.com']
     * ]);
     * // Expected: "Hi Ali, email: a@ex.com"
     * ```
     *
     * @return void
     */
    public function testRenderNested(): void
    {
        $tpl = 'Hi {{user.name}}, email: {{user.email}}';
        $out = PlaceholderRenderer::render($tpl, [
            'user' => ['name' => 'Ali', 'email' => 'a@ex.com']
        ]);

        $this->assertSame('Hi Ali, email: a@ex.com', $out);
    }

    /**
     * ⚠️ **Test Handling of Missing Keys**
     *
     * 🧠 Validates that missing or undefined placeholders are replaced with empty strings
     * rather than throwing errors or leaving the raw placeholder in the output.
     *
     * Example:
     * ```php
     * $tpl = 'Hello {{user.age}}';
     * PlaceholderRenderer::render($tpl, ['user' => ['name' => 'Ali']]);
     * // Expected: "Hello "
     * ```
     *
     * @return void
     */
    public function testMissingKey(): void
    {
        $tpl = 'Hello {{user.age}}';
        $this->assertSame(
            'Hello ',
            PlaceholderRenderer::render($tpl, ['user' => ['name' => 'Ali']])
        );
    }
}
