<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Middleware;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Middleware\RateLimitHeadersMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RateLimitHeadersMiddlewareTest extends TestCase
{
    public function testProcessAddsHeadersWhenWithinLimit(): void
    {
        $limiter = $this->createMock(RateLimiterInterface::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        // Mock Limiter Status
        $dto = new RateLimitStatusDTO(10, 9, 60);
        $limiter->method('attempt')->willReturn($dto);

        // Mock Handler to return response
        $handler->method('handle')->willReturn($response);

        // Expect Headers to be added
        $response->expects($this->exactly(3))->method('withHeader')->willReturnSelf();

        $middleware = new RateLimitHeadersMiddleware(
            $limiter,
            RateLimitActionEnum::LOGIN,
            PlatformEnum::WEB
        );

        $result = $middleware->process($request, $handler);
        $this->assertSame($response, $result);
    }

    public function testProcessReturns429WhenLimitExceeded(): void
    {
        $limiter = $this->createMock(RateLimiterInterface::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        // Mock Exception
        $dto = new RateLimitStatusDTO(10, 0, 60, 60, true);
        $exception = new TooManyRequestsException('Rate limit exceeded', 429, $dto);
        $limiter->method('attempt')->willThrowException($exception);

        // Mock Handler to return response (which will be modified)
        $handler->method('handle')->willReturn($response);

        // Expect 429 status and headers
        $response->expects($this->once())->method('withStatus')->with(429)->willReturnSelf();
        $response->expects($this->exactly(3))->method('withHeader')->willReturnSelf();

        $middleware = new RateLimitHeadersMiddleware(
            $limiter,
            RateLimitActionEnum::LOGIN,
            PlatformEnum::WEB
        );

        $result = $middleware->process($request, $handler);
        $this->assertSame($response, $result);
    }

    public function testProcessExtractsKeyFromHeader(): void
    {
        $limiter = $this->createMock(RateLimiterInterface::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        // Header present
        $request->method('getHeaderLine')->with('X-Client-IP')->willReturn('1.2.3.4');
        $limiter->expects($this->once())
            ->method('attempt')
            ->with('1.2.3.4', $this->anything(), $this->anything())
            ->willReturn(new RateLimitStatusDTO(10, 9, 60));

        $handler->method('handle')->willReturn($response);

        $middleware = new RateLimitHeadersMiddleware(
            $limiter,
            RateLimitActionEnum::LOGIN,
            PlatformEnum::WEB,
            'X-Client-IP'
        );

        $middleware->process($request, $handler);
    }

    public function testProcessExtractsKeyFromRemoteAddrWhenHeaderEmpty(): void
    {
        $limiter = $this->createMock(RateLimiterInterface::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        // Header empty, use REMOTE_ADDR
        $request->method('getHeaderLine')->willReturn('');
        $request->method('getServerParams')->willReturn(['REMOTE_ADDR' => '5.6.7.8']);

        $limiter->expects($this->once())
            ->method('attempt')
            ->with('5.6.7.8', $this->anything(), $this->anything())
            ->willReturn(new RateLimitStatusDTO(10, 9, 60));

        $handler->method('handle')->willReturn($response);

        $middleware = new RateLimitHeadersMiddleware(
            $limiter,
            RateLimitActionEnum::LOGIN,
            PlatformEnum::WEB,
            'X-Client-IP'
        );

        $middleware->process($request, $handler);
    }

    public function testProcessHandlesMissingRemoteAddr(): void
    {
        $limiter = $this->createMock(RateLimiterInterface::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        // Header empty, REMOTE_ADDR missing
        $request->method('getHeaderLine')->willReturn('');
        $request->method('getServerParams')->willReturn([]);

        $limiter->expects($this->once())
            ->method('attempt')
            ->with('unknown', $this->anything(), $this->anything())
            ->willReturn(new RateLimitStatusDTO(10, 9, 60));

        $handler->method('handle')->willReturn($response);

        $middleware = new RateLimitHeadersMiddleware(
            $limiter,
            RateLimitActionEnum::LOGIN,
            PlatformEnum::WEB,
            'X-Client-IP'
        );

        $middleware->process($request, $handler);
    }
}
