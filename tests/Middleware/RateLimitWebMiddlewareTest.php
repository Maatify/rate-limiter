<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Middleware;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Middleware\RateLimitWebMiddleware;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RateLimitWebMiddlewareTest extends TestCase
{
    #[RunInSeparateProcess]
    public function testProcessProceedsWhenWithinLimit(): void
    {
        $limiter = $this->createMock(RateLimiterInterface::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $dto = new RateLimitStatusDTO(10, 9, 60);
        $limiter->method('attempt')->willReturn($dto);
        $handler->method('handle')->willReturn($response);

        $middleware = new RateLimitWebMiddleware(
            $limiter,
            RateLimitActionEnum::LOGIN,
            PlatformEnum::WEB
        );

        $result = $middleware->process($request, $handler);
        $this->assertSame($response, $result);
    }

    // Testing the redirect path (exit) is hard in PHPUnit without specialized tools or structural changes.
    // We will skip testing the `exit;` path here as it terminates execution.
    // However, we can test that attempt is called.
}
