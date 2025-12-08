<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Middleware;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
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

    /*
     * Note: Testing the TooManyRequestsException path is not possible in this environment
     * because the middleware calls `exit;` which terminates the test runner.
     * To test this, we would need the `uopz` extension or to modify the source code.
     *
    public function testProcessRedirectsOnTooManyRequests(): void
    {
        // ...
    }
    */
}
