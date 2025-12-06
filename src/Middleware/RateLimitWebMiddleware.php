<?php

/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 19:41
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

namespace Maatify\RateLimiter\Middleware;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

/**
 * 🌐 RateLimitWebMiddleware
 *
 * Handles rate limit enforcement for web-based PHP pages (non-API endpoints).
 * If the limit is exceeded, stores a temporary session message and redirects
 * the user back to the referring page.
 *
 * 🔒 Follows the Post/Redirect/Get pattern to avoid duplicate submissions.
 */
final class RateLimitWebMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly RateLimiterInterface $limiter,
        private readonly RateLimitActionEnum $action,
        private readonly PlatformEnum $platform
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        session_start();
        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';

        try {
            $this->limiter->attempt($ip, $this->action, $this->platform);
            return $handler->handle($request);
        } catch (TooManyRequestsException $e) {
            $status = $e->status;

            $_SESSION['rate_limit_error'] = [
                'retry_after' => $status->retryAfter ?? 5,
                'action'      => $this->action->value,
                'timestamp'   => time(),
            ];

            // redirect safely (Post/Redirect/Get pattern)
            $referer = $request->getHeaderLine('Referer') ?: '/';
            header("Location: {$referer}");
            exit;
        }
    }
}
