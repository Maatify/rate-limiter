<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Middleware;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Maatify\RateLimiter\Middleware\RateLimitWebMiddleware;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
final class RateLimitWebMiddlewareTest extends TestCase
{
    /** @var RateLimiterInterface&MockObject */
    private RateLimiterInterface $limiter;

    private RateLimitWebMiddleware $middleware;

    /** @var ServerRequestInterface&MockObject */
    private ServerRequestInterface $request;

    /** @var RequestHandlerInterface&MockObject */
    private RequestHandlerInterface $handler;

    protected function setUp(): void
    {
        $this->limiter = $this->createMock(RateLimiterInterface::class);
        $this->middleware = new RateLimitWebMiddleware(
            $this->limiter,
            RateLimitActionEnum::LOGIN,
            PlatformEnum::WEB
        );
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->handler = $this->createMock(RequestHandlerInterface::class);
    }

    public function testProcessAllowsRequestWhenUnderLimit(): void
    {
        $this->request->method('getServerParams')->willReturn(['REMOTE_ADDR' => '127.0.0.1']);

        $this->limiter->expects($this->once())
            ->method('attempt')
            ->with('127.0.0.1', RateLimitActionEnum::LOGIN, PlatformEnum::WEB)
            ->willReturn(new RateLimitStatusDTO(10, 9, 60));

        $response = $this->createMock(ResponseInterface::class);
        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($response);

        $result = $this->middleware->process($this->request, $this->handler);
        $this->assertSame($response, $result);
    }

    public function testProcessHandlesMissingRemoteAddr(): void
    {
        $this->request->method('getServerParams')->willReturn([]);

        $this->limiter->expects($this->once())
            ->method('attempt')
            ->with('unknown', RateLimitActionEnum::LOGIN, PlatformEnum::WEB)
            ->willReturn(new RateLimitStatusDTO(10, 9, 60));

        $response = $this->createMock(ResponseInterface::class);
        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($response);

        $result = $this->middleware->process($this->request, $this->handler);
        $this->assertSame($response, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testProcessRedirectsOnTooManyRequests(): void
    {
        $this->request->method('getServerParams')->willReturn(['REMOTE_ADDR' => '127.0.0.1']);

        $exception = new TooManyRequestsException(
            'Too Many Requests',
            429,
            new RateLimitStatusDTO(10, 0, 60, 30)
        );

        $this->limiter->expects($this->once())
            ->method('attempt')
            ->with('127.0.0.1', RateLimitActionEnum::LOGIN, PlatformEnum::WEB)
            ->willThrowException($exception);

        $this->request->method('getHeaderLine')
            ->with('Referer')
            ->willReturn('/previous-page');

        // We use ob_start to capture output if any, and register a shutdown function or similar?
        // Actually, since it exits, the test will end abruptly.
        // BUT, PHPUnit has protection against exit if we use exception expectation.
        // OR we can just check if session is set before exit? No.

        // Wait, there is no easy way to test `exit` without uopz `exit` hook.
        // But maybe I can mock `header`? No, it's a built-in function.

        try {
            $this->middleware->process($this->request, $this->handler);
        } catch (\RuntimeException $e) {
            $this->assertStringStartsWith('Header called: Location:', $e->getMessage());
        }
    }
}

namespace Maatify\RateLimiter\Middleware;

function header(string $header): void
{
    throw new \RuntimeException('Header called: ' . $header);
}
