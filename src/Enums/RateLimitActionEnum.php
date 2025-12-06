<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:04
 * Project: maatify/rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Enums;

use Maatify\RateLimiter\Contracts\RateLimitActionInterface;

/**
 * 🎯 Enum RateLimitActionEnum
 *
 * 🧩 Purpose:
 * Provides a strongly typed enumeration of rate-limited actions across the system.
 * Each case represents a distinct action (e.g., login, OTP request) that can be
 * individually configured and controlled through the rate limiter.
 *
 * This enum also implements {@see RateLimitActionInterface} for compatibility
 * with type-hinted method signatures and dependency injection across Maatify components.
 *
 * ⚙️ Usage:
 * ```php
 * use Maatify\RateLimiter\Enums\RateLimitActionEnum;
 *
 * $action = RateLimitActionEnum::LOGIN;
 * echo $action->value; // "login"
 * ```
 *
 * ✅ Typical use cases:
 * - Authentication and signup rate control.
 * - Throttling OTP or password reset attempts.
 * - General API request throttling.
 *
 * @package Maatify\RateLimiter\Enums
 */
enum RateLimitActionEnum: string implements RateLimitActionInterface
{
    /** 🔐 User login attempts. */
    case LOGIN = 'login';

    /** 🧾 New user registration attempts. */
    case REGISTER = 'register';

    /** 🔢 One-Time Password (OTP) request attempts. */
    case OTP_REQUEST = 'otp_request';

    /** 🔑 Password reset request attempts. */
    case PASSWORD_RESET = 'password_reset';

    /** ⚙️ General API call rate-limiting. */
    case API_CALL = 'api_call';

    /**
     * 🧠 Retrieve the string value of the enum case.
     *
     * 🎯 This helper method provides an explicit interface-compliant
     * way to retrieve the action value, maintaining backward compatibility
     * with non-enum implementations that expect a `value()` method.
     *
     * @return string The string value of the rate-limit action.
     *
     * ✅ Example:
     * ```php
     * echo RateLimitActionEnum::REGISTER->value(); // "register"
     * ```
     */
    public function value(): string
    {
        // 🔹 Returns the internal enum value (same as ->value property)
        return $this->value;
    }
}
