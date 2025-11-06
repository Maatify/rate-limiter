<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 00:00
 * Project: maatify/rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Contracts;

use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

interface RateLimiterInterface
{
    /**
     * Check and update rate limit for a given action/platform/IP.
     *
     * @param string $ip
     * @param RateLimitActionEnum $action
     * @param PlatformEnum $platform
     * @return RateLimitStatusDTO
     * @throws TooManyRequestsException
     */
    public function attempt(string $ip, RateLimitActionEnum $action, PlatformEnum $platform): RateLimitStatusDTO;

    /**
     * Manually reset the rate limit for a specific key.
     */
    public function reset(string $ip, RateLimitActionEnum $action, PlatformEnum $platform): bool;

    /**
     * Get the current status without incrementing counters.
     */
    public function status(string $ip, RateLimitActionEnum $action, PlatformEnum $platform): RateLimitStatusDTO;
}