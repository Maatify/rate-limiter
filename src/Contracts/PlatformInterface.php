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
 * Defines a contract for platform or context identifiers.
 */
interface PlatformInterface
{
    public function value(): string;
}
