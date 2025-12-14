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
 * 🎯 Class RateLimitStatusDTO
 *
 * 🧩 Purpose:
 * Encapsulates the current rate-limit status for a given client,
 * including limits, remaining requests, reset window, retry info,
 * and adaptive backoff metadata.
 *
 * ⚙️ Typical Use Case:
 * Returned by a RateLimiter driver after performing an attempt or status check.
 * Can be serialized to JSON for APIs or logs.
 *
 * ✅ Example:
 * ```php
 * use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
 *
 * $status = new RateLimitStatusDTO(
 *     limit: 5,
 *     remaining: 0,
 *     resetAfter: 60,
 *     retryAfter: 30,
 *     blocked: true,
 *     backoffSeconds: 8,
 *     nextAllowedAt: '2025-11-07 01:05:23'
 * );
 *
 * print_r($status->toArray());
 * ```
 *
 * @package Maatify\RateLimiter\DTO
 */
final class RateLimitStatusDTO
{
    /**
     * 🧠 Constructor initializes a snapshot of the rate-limit status.
     *
     * @param int         $limit          Max allowed requests within the rate-limit window.
     * @param int         $remaining      Remaining number of allowed requests before hitting the limit.
     * @param int         $resetAfter     Seconds until counters reset automatically.
     * @param int|null    $retryAfter     Optional seconds to wait before retrying (if temporarily blocked).
     * @param bool        $blocked        Indicates whether the client is currently blocked.
     * @param int|null    $backoffSeconds Optional adaptive backoff delay in seconds.
     * @param string|null $nextAllowedAt  UTC timestamp when the next request is allowed.
     * @param string|null $source         Indicates whether the limiter was global or action-based.
     */
    public function __construct(
        public readonly int $limit,
        public readonly int $remaining,
        public readonly int $resetAfter,
        public readonly ?int $retryAfter = null,
        public readonly bool $blocked = false,
        public ?int $backoffSeconds = null,
        public ?string $nextAllowedAt = null,
        public ?string $source = null,
    ) {
    }

    /**
     * 🔹 Convert the DTO data into an associative array.
     *
     * 🎯 Useful for serialization, API responses, or logging.
     *
     * @return array{
     *     limit: int,
     *     remaining: int,
     *     reset_after: int,
     *     retry_after: int|null,
     *     blocked: bool,
     *     backoff_seconds: int|null,
     *     next_allowed_at: string|null,
     *     source: string|null
     * }
     *
     * ✅ Example:
     * ```php
     * echo json_encode($status->toArray(), JSON_PRETTY_PRINT);
     * ```
     */
    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'remaining' => $this->remaining,
            'reset_after' => $this->resetAfter,
            'retry_after' => $this->retryAfter,
            'blocked' => $this->blocked,
            'backoff_seconds' => $this->backoffSeconds,
            'next_allowed_at' => $this->nextAllowedAt,
            'source' => $this->source,
        ];
    }

    /**
     * Build a DTO from a serialized representation.
     */
    /**
     * @param array{
     *   limit?: int,
     *   remaining?: int,
     *   reset_after?: int,
     *   retry_after?: int|null,
     *   blocked?: bool,
     *   backoff_seconds?: int|null,
     *   next_allowed_at?: string|null,
     *   source?: string|null
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            limit: (int) ($data['limit'] ?? 0),
            remaining: (int) ($data['remaining'] ?? 0),
            resetAfter: (int) ($data['reset_after'] ?? 0),
            retryAfter: isset($data['retry_after']) ? (int) $data['retry_after'] : null,
            blocked: (bool) ($data['blocked'] ?? false),
            backoffSeconds: isset($data['backoff_seconds']) ? (int) $data['backoff_seconds'] : null,
            nextAllowedAt: $data['next_allowed_at'] ?? null,
            source: $data['source'] ?? null,
        );
    }
}
