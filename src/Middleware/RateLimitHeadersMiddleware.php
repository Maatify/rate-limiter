<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 02:54
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Middleware;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * ⚙️ Class RateLimitHeadersMiddleware
 *
 * 🧩 Purpose:
 * PSR-15 middleware that automatically integrates the rate limiter into HTTP requests.
 * It injects standard `X-RateLimit-*` headers and gracefully handles `429 Too Many Requests`
 * responses when limits are exceeded.
 *
 * ✅ Features:
 * - Automatically reads the client key (IP or header).
 * - Adds informative rate-limit headers to every response.
 * - Prevents over-limit requests with a `429 Too Many Requests` response.
 *
 * ⚙️ Example:
 * ```php
 * use Maatify\RateLimiter\Middleware\RateLimitHeadersMiddleware;
 * use Maatify\RateLimiter\Enums\RateLimitActionEnum;
 * use Maatify\RateLimiter\Enums\PlatformEnum;
 * use Maatify\RateLimiter\Drivers\RedisRateLimiter;
 * use Redis;
 *
 * $redis = new Redis();
 * $redis->connect('127.0.0.1');
 * $limiter = new RedisRateLimiter($redis);
 *
 * $middleware = new RateLimitHeadersMiddleware(
 *     limiter: $limiter,
 *     action: RateLimitActionEnum::LOGIN,
 *     platform: PlatformEnum::WEB,
 *     keyHeader: 'X-Client-IP'
 * );
 * ```
 *
 * @package Maatify\RateLimiter\Middleware
 */
final class RateLimitHeadersMiddleware implements MiddlewareInterface
{
    /**
     * 🧠 Constructor
     *
     * @param RateLimiterInterface $limiter  The rate limiter implementation.
     * @param RateLimitActionInterface $action The rate-limited logical action.
     * @param PlatformInterface $platform The platform context (e.g., WEB, API).
     * @param string|null $keyHeader Optional custom header to extract client key (defaults to `X-Client-IP`).
     */
    public function __construct(
        private readonly RateLimiterInterface $limiter,
        private readonly RateLimitActionInterface $action,
        private readonly PlatformInterface $platform,
        private readonly ?string $keyHeader = 'X-Client-IP'
    ) {
    }

    /**
     * 🚦 Process the incoming request through the rate limiter.
     *
     * 🎯 This middleware:
     * - Determines the request’s client identifier.
     * - Invokes the rate limiter.
     * - Sets appropriate `X-RateLimit-*` headers.
     * - Returns `429 Too Many Requests` if limit is exceeded.
     *
     * @param ServerRequestInterface $request  The incoming HTTP request.
     * @param RequestHandlerInterface $handler The next middleware or handler in the chain.
     *
     * @return ResponseInterface The HTTP response with rate-limit headers.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 🔍 Determine unique client key (from header or fallback to IP)
        $key = 'unknown';
        if ($this->keyHeader !== null) {
            $key = $request->getHeaderLine($this->keyHeader);
        }

        if ($key === '' || $key === 'unknown') {
            $val = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';
            $key = is_string($val) ? $val : 'unknown';
        }

        try {
            // 🧩 Attempt the rate-limited operation
            $status = $this->limiter->attempt($key, $this->action, $this->platform);
        } catch (TooManyRequestsException $e) {
            // 🚫 Over the limit → respond with 429 and relevant headers
            $response = $handler->handle($request);
            $status = $e->status;

            // Handle potentially null status safely
            $retryAfter = $status ? $status->retryAfter : 60;
            $limit = $status ? $status->limit : '';

            return $response
                ->withStatus(429)
                ->withHeader('Retry-After', (string)$retryAfter)
                ->withHeader('X-RateLimit-Limit', (string)$limit)
                ->withHeader('X-RateLimit-Remaining', '0');
        }

        // ✅ Proceed with request handling when within limit
        $response = $handler->handle($request);

        // 🧾 Attach rate-limit status headers
        return $response
            ->withHeader('X-RateLimit-Limit', (string)$status->limit)
            ->withHeader('X-RateLimit-Remaining', (string)$status->remaining)
            ->withHeader('X-RateLimit-Reset', (string)$status->resetAfter);
    }
}
