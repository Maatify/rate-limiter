<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:06
 * Project: maatify/rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Exceptions;

use Exception;

/**
 * 🚫 Class TooManyRequestsException
 *
 * 🧩 Purpose:
 * Thrown when a client exceeds the allowed number of requests
 * within a defined rate-limit window. Typically results in
 * HTTP status code **429 Too Many Requests**.
 *
 * ⚙️ Usage:
 * ```php
 * use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
 *
 * throw new TooManyRequestsException('You have exceeded the limit.');
 * ```
 *
 * ✅ Common use cases:
 * - Login brute-force protection.
 * - OTP / SMS / API rate control.
 * - Preventing abuse in high-frequency endpoints.
 *
 * @package Maatify\RateLimiter\Exceptions
 */
final class TooManyRequestsException extends Exception
{
    /**
     * 🧠 Construct the TooManyRequestsException.
     *
     * @param string $message Custom error message (default: "Too many requests").
     * @param int    $code    HTTP error code (default: 429).
     *
     * ✅ Example:
     * ```php
     * throw new TooManyRequestsException('Wait 5 minutes before retrying');
     * ```
     */
    public function __construct(
        string $message = 'Too many requests',
        int $code = 429
    ) {
        // 🔹 Initialize parent Exception with provided message and code
        parent::__construct($message, $code);
    }
}
