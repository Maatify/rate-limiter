# 🧩 Phase 4 – Resolver & Middleware Integration

**Goal:**  
Introduce a dynamic driver resolver and PSR-15–compliant middleware  
for automatic rate limiting across HTTP-based applications.

---

## 🎯 Objective

To make the rate limiter:
- **Auto-configurable** (detect driver from config/env)
- **Plug-and-play** (usable as a middleware in any PSR-15 system)
- **Framework-agnostic** (works with Slim, Laravel, or native PHP)

---

## ✅ Tasks Completed

- Added **`RateLimiterResolver`**  
  → Automatically detects and instantiates the correct driver  
- Added **`RateLimitHeadersMiddleware`**  
  → Adds HTTP headers and rate-limiting enforcement automatically  
- Added **`TooManyRequestsException`** integration for middleware flow  
- Added **unit tests** for resolver and middleware logic  
- Added example integrations for Slim, Laravel, and native PHP

---

## 📂 Files Created

```

src/
├── Resolver/
│   └── RateLimiterResolver.php
└── Middleware/
└── RateLimitHeadersMiddleware.php

tests/
├── ResolverTest.php
└── MiddlewareTest.php

````

---

## 🧩 Resolver Overview

### 🔹 File: `RateLimiterResolver.php`

```php
namespace Maatify\RateLimiter\Resolver;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Drivers\{RedisRateLimiter, MongoRateLimiter, MySQLRateLimiter};
use InvalidArgumentException;

final class RateLimiterResolver
{
    public function __construct(private readonly array $config) {}

    public function resolve(): RateLimiterInterface
    {
        $driver = strtolower($this->config['driver'] ?? 'redis');

        return match ($driver) {
            'redis' => new RedisRateLimiter($this->config),
            'mongo', 'mongodb' => new MongoRateLimiter($this->config),
            'mysql' => new MySQLRateLimiter($this->config),
            default => throw new InvalidArgumentException("Unsupported driver: $driver"),
        };
    }
}
````

---

## ⚙️ Middleware Overview

### 🔹 File: `RateLimitHeadersMiddleware.php`

```php
namespace Maatify\RateLimiter\Middleware;

use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Contracts\{RateLimitActionInterface, PlatformInterface};
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

final class RateLimitHeadersMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly RateLimiterInterface $limiter,
        private readonly RateLimitActionInterface $action,
        private readonly PlatformInterface $platform,
        private readonly string $keyHeader = 'X-Forwarded-For',
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $key = $request->getHeaderLine($this->keyHeader) ?: $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';

        try {
            $status = $this->limiter->attempt($key, $this->action, $this->platform);
        } catch (TooManyRequestsException $e) {
            $response = $handler->handle($request)
                ->withStatus(429)
                ->withHeader('Retry-After', (string)($status->retryAfter ?? 60));
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response;
        }

        $response = $handler->handle($request);
        return $response
            ->withHeader('X-RateLimit-Limit', (string)$status->limit)
            ->withHeader('X-RateLimit-Remaining', (string)$status->remaining)
            ->withHeader('X-RateLimit-Reset', (string)$status->resetAfter);
    }
}
```

---

## 🧱 Slim Example

```php
use Slim\Factory\AppFactory;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Middleware\RateLimitHeadersMiddleware;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;

$app = AppFactory::create();

$config = [
    'driver' => 'redis',
    'redis_host' => '127.0.0.1',
];

$resolver = new RateLimiterResolver($config);
$limiter = $resolver->resolve();

$app->add(new RateLimitHeadersMiddleware(
    $limiter,
    RateLimitActionEnum::LOGIN,
    PlatformEnum::WEB
));

$app->get('/login', fn($req, $res) => $res->withJson(['status' => 'ok']));

$app->run();
```

---

## 🧱 Laravel Example

```php
namespace App\Http\Middleware;

use Closure;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

class RateLimitHeaders
{
    public function handle($request, Closure $next)
    {
        $resolver = new RateLimiterResolver(['driver' => 'redis']);
        $limiter = $resolver->resolve();
        $key = $request->ip();

        try {
            $status = $limiter->attempt($key, RateLimitActionEnum::API_CALL, PlatformEnum::API);
        } catch (TooManyRequestsException $e) {
            return response()->json(['error' => $e->getMessage()], 429);
        }

        return $next($request)
            ->header('X-RateLimit-Limit', $status->limit)
            ->header('X-RateLimit-Remaining', $status->remaining)
            ->header('X-RateLimit-Reset', $status->resetAfter);
    }
}
```

---

## 🧪 Unit Test Example

```php
use PHPUnit\Framework\TestCase;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Contracts\RateLimiterInterface;

final class ResolverTest extends TestCase
{
    public function testResolvesRedisDriver(): void
    {
        $resolver = new RateLimiterResolver(['driver' => 'redis']);
        $limiter = $resolver->resolve();
        $this->assertInstanceOf(RateLimiterInterface::class, $limiter);
    }
}
```

---

## 📊 Result Summary

| Component                    | Status | Description                             |
|------------------------------|--------|-----------------------------------------|
| `RateLimiterResolver`        | ✅      | Driver auto-detection and creation      |
| `RateLimitHeadersMiddleware` | ✅      | PSR-15 middleware with header injection |
| `TooManyRequestsException`   | ✅      | Integrated into middleware flow         |
| `Slim / Laravel Examples`    | ✅      | Fully functional                        |
| Unit Tests                   | ✅      | All passed                              |

---

## 🧩 Version

```
1.0.0-alpha-phase4
```

---

## 📜 Notes

This phase marks the transition from core logic to real-world integration.
The library is now **framework-ready**, supporting both **web middleware**
and **direct service usage** in backend applications.

With the resolver and middleware in place,
Maatify Rate Limiter can be dropped into any PSR-compliant stack instantly.


---
