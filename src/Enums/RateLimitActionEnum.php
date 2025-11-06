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

enum RateLimitActionEnum: string
{
    case LOGIN = 'login';
    case REGISTER = 'register';
    case OTP_REQUEST = 'otp_request';
    case PASSWORD_RESET = 'password_reset';
    case API_CALL = 'api_call';
}