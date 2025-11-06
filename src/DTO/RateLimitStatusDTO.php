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
 * A simple Data Transfer Object (DTO) that encapsulates the
 * current rate-limit state for a client, including limit, remaining
 * attempts, reset timing, and block status.
 *
 * ⚙️ Typical use case:
 * This DTO is returned by the RateLimiter when checking or retrieving
 * the status of a particular IP/action/platform combination.
 *
 * ✅ Example:
 * ```php
 * use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
 *
 * $status = new RateLimitStatusDTO(
 *     limit: 5,
 *     remaining: 2,
 *     resetAfter: 60,
 *     retryAfter: 30,
 *     blocked: false
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
     * 🧠 Constructor initializes a snapshot of the current rate-limit status.
     *
     * @param int  $limit       Maximum allowed requests within the rate-limit window.
     * @param int  $remaining   Remaining number of requests before hitting the limit.
     * @param int  $resetAfter  Seconds until counters reset automatically.
     * @param int|null $retryAfter Optional seconds to wait before retrying (if temporarily blocked).
     * @param bool $blocked     Indicates whether the client is currently blocked.
     */
    public function __construct(
        public readonly int $limit,
        public readonly int $remaining,
        public readonly int $resetAfter,
        public readonly ?int $retryAfter = null,
        public readonly bool $blocked = false,
    ) {}

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
     *     blocked: bool
     * }
     *
     * ✅ Example:
     * ```php
     * $array = $status->toArray();
     * echo json_encode($array);
     * ```
     */
    public function toArray(): array
    {
        // ⚙️ Return structured rate-limit data for output or transport
        return [
            'limit' => $this->limit,
            'remaining' => $this->remaining,
            'reset_after' => $this->resetAfter,
            'retry_after' => $this->retryAfter,
            'blocked' => $this->blocked,
        ];
    }
}
