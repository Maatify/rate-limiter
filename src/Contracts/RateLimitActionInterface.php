<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 02:30
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Contracts;

/**
 * Defines a contract for all rate limit action identifiers.
 *
 * This allows external projects to implement their own enums
 * (e.g., AppActionEnum, CustomRateActionEnum) while keeping compatibility.
 */
interface RateLimitActionInterface
{
    public function value(): string;
}
