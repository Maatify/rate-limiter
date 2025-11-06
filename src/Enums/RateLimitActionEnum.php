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

/**
 * 🎯 Enum RateLimitActionEnum
 *
 * 🧩 Purpose:
 * Defines standardized rate-limited actions within the system.
 * Each enum case represents a specific user or system operation
 * that should be subject to rate control to prevent abuse.
 *
 * ⚙️ Usage:
 * ```php
 * use Maatify\RateLimiter\Enums\RateLimitActionEnum;
 *
 * $action = RateLimitActionEnum::LOGIN;
 * echo $action->value; // "login"
 * ```
 *
 * ✅ Common use cases:
 * - Rate-limiting authentication attempts (e.g., login, register).
 * - Controlling OTP or password-reset requests.
 * - Managing general API request frequency.
 *
 * @package Maatify\RateLimiter\Enums
 */
enum RateLimitActionEnum: string
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
}
