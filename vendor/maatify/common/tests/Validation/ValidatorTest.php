<?php

/**
 * @copyright   ©2025 Maatify.dev
 * @Liberary    maatify/common
 * @Project     maatify:common
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2025-11-09 23:10
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/common  view project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Common\Tests\Validation;

use Maatify\Common\Validation\Validator;
use PHPUnit\Framework\TestCase;

/**
 * 🧪 **ValidatorTest**
 *
 * ✅ Unit tests for {@see Validator} class.
 * Covers validation for:
 * - Email, URL, IP, UUID, slug, and phone numbers.
 * - Integer, float, and range checks.
 * - Type detection behavior.
 *
 * @package Maatify\Common\Tests\Validation
 *
 * @example
 * ```php
 * $this->assertTrue(Validator::email('info@maatify.dev'));
 * $this->assertSame('uuid', Validator::detectType('550e8400-e29b-41d4-a716-446655440000'));
 * ```
 */
final class ValidatorTest extends TestCase
{
    /**
     * 📧 Tests email validation using valid and invalid samples.
     *
     * @return void
     */
    public function testEmailValidation(): void
    {
        $this->assertTrue(Validator::email('test@maatify.dev'));
        $this->assertFalse(Validator::email('invalid-email'));
    }

    /**
     * 🌐 Tests URL validation logic for both valid and invalid URLs.
     *
     * @return void
     */
    public function testUrlValidation(): void
    {
        $this->assertTrue(Validator::url('https://maatify.dev'));
        $this->assertFalse(Validator::url('maatify.dev'));
    }

    /**
     * 🌍 Tests IPv4 and IPv6 validation, including invalid edge cases.
     *
     * @return void
     */
    public function testIpValidation(): void
    {
        $this->assertTrue(Validator::ip('192.168.1.1'));
        $this->assertTrue(Validator::ip('2001:db8::1'));
        $this->assertFalse(Validator::ip('999.999.999.999'));
    }

    /**
     * 🆔 Tests UUID format validation for correct and incorrect strings.
     *
     * @return void
     */
    public function testUuidValidation(): void
    {
        $this->assertTrue(Validator::uuid('123e4567-e89b-12d3-a456-426614174000'));
        $this->assertFalse(Validator::uuid('invalid-uuid'));
    }

    /**
     * 🏷️ Tests slug validation ensuring lowercase alphanumeric with optional dashes.
     *
     * @return void
     */
    public function testSlugValidation(): void
    {
        $this->assertTrue(Validator::slug('maatify-common'));
        $this->assertFalse(Validator::slug('Maatify Common!'));
    }

    /**
     * 📱 Tests international phone number validation logic.
     *
     * @return void
     */
    public function testPhoneValidation(): void
    {
        $this->assertTrue(Validator::phone('+201234567890'));
        $this->assertTrue(Validator::phone('0123456789'));
        $this->assertFalse(Validator::phone('abc123'));
    }

    /**
     * 🔢 Tests integer and float validators with edge cases.
     *
     * @return void
     */
    public function testIntegerAndFloatValidation(): void
    {
        // Integer tests
        $this->assertTrue(Validator::integer('123'));
        $this->assertTrue(Validator::integer(-55));
        $this->assertFalse(Validator::integer('12.3'));

        // Float tests
        $this->assertTrue(Validator::float('12.3'));
        $this->assertTrue(Validator::float(-45.67));
        $this->assertFalse(Validator::float('12a'));
    }

    /**
     * 🎚️ Tests numeric range validation logic.
     *
     * @return void
     */
    public function testBetweenValidation(): void
    {
        $this->assertTrue(Validator::between(5, 1, 10));
        $this->assertFalse(Validator::between(15, 1, 10));
    }

    /**
     * 🧭 Tests smart type detection logic for various inputs.
     *
     * @return void
     */
    public function testDetectType(): void
    {
        $this->assertSame('email', Validator::detectType('test@maatify.dev'));
        $this->assertSame('integer', Validator::detectType('42'));
        $this->assertSame('float', Validator::detectType('3.14'));
        $this->assertSame('slug', Validator::detectType('maatify-core'));
        $this->assertSame('slug_path', Validator::detectType('maatify/common/validation-test'));
        $this->assertSame('slug', Validator::detectType('no-type-here'));
    }
}
