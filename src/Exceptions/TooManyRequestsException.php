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

final class TooManyRequestsException extends Exception
{
    public function __construct(
        string $message = 'Too many requests',
        int $code = 429
    ) {
        parent::__construct($message, $code);
    }
}