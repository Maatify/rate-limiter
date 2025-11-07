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
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

/**
 * ⚠️ TooManyRequestsException
 *
 * Custom exception thrown when the rate limit is exceeded.
 * It carries a {@see RateLimitStatusDTO} object with full retry metadata.
 *
 * ✅ Example:
 * ```php
 * throw new TooManyRequestsException(
 *     'Rate limit exceeded. Retry after 10 seconds',
 *     status: $statusDTO
 * );
 * ```
 */
final class TooManyRequestsException extends Exception
{
    /**
     * Contains the rate-limit status at the moment of exception.
     */
    public readonly ?RateLimitStatusDTO $status;

    /**
     * @param string                 $message Custom error message
     * @param int                    $code    HTTP error code (default: 429)
     * @param RateLimitStatusDTO|null $status Optional DTO carrying metadata
     */
    public function __construct(
        string $message = 'Too many requests',
        int $code = 429,
        ?RateLimitStatusDTO $status = null
    ) {
        parent::__construct($message, $code);
        $this->status = $status;
    }
}

