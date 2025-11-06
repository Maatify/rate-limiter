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

namespace Maatify\RateLimiter\DTO;

/**
 * Represents current rate limit status.
 */
final class RateLimitStatusDTO
{
    public function __construct(
        public readonly int $limit,
        public readonly int $remaining,
        public readonly int $resetAfter,
        public readonly ?int $retryAfter = null,
        public readonly bool $blocked = false,
    ) {}

    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'remaining' => $this->remaining,
            'reset_after' => $this->resetAfter,
            'retry_after' => $this->retryAfter,
            'blocked' => $this->blocked,
        ];
    }
}