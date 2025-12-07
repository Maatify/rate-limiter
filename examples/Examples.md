# 📘 Maatify Rate Limiter – Usage Examples

[![Maatify Rate Limiter](https://img.shields.io/badge/Maatify-Rate--Limiter-blue?style=for-the-badge)](https://github.com/Maatify/rate-limiter)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

This document provides **full real-world usage examples** for the  
`maatify/rate-limiter` library across multiple environments and frameworks.

---

## 1️⃣ Native PHP Example (Basic Usage)

```php
<?php

require 'vendor/autoload.php';

use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

$config = [
    'driver' => 'redis',
    'redis_host' => '127.0.0.1',
    'redis_port' => 6379,
];

$resolver = new RateLimiterResolver($config);
$limiter = $resolver->resolve();

$key = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

try {
    $status = $limiter->attempt($key, RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
    echo "✅ Allowed. Remaining: {$status->remaining}\n";
} catch (TooManyRequestsException $e) {
    echo "⛔ {$e->getMessage()}. Try again later.\n";
}
````

---

## 2️⃣ Slim Framework – Full Middleware Integration

```php
use Slim\Factory\AppFactory;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Middleware\RateLimitHeadersMiddleware;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;

require __DIR__ . '/vendor/autoload.php';

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

$app->get('/login', function ($request, $response) {
    $response->getBody()->write('Welcome to login endpoint!');
    return $response;
});

$app->run();
```

### ✅ Output Headers

```
X-RateLimit-Limit: 5
X-RateLimit-Remaining: 4
X-RateLimit-Reset: 60
Retry-After: 60
```

---

## 3️⃣ Laravel – Custom Middleware

📄 `app/Http/Middleware/RateLimitHeaders.php`

```php
<?php

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
        $config = ['driver' => 'redis', 'redis_host' => '127.0.0.1'];
        $resolver = new RateLimiterResolver($config);
        $limiter = $resolver->resolve();

        $key = $request->ip();

        try {
            $status = $limiter->attempt(
                $key,
                RateLimitActionEnum::API_CALL,
                PlatformEnum::API
            );
        } catch (TooManyRequestsException $e) {
            return response()->json([
                'error' => 'Too many requests',
                'retry_after' => $status->retryAfter ?? 60,
            ], 429);
        }

        $response = $next($request);

        return $response
            ->header('X-RateLimit-Limit', $status->limit)
            ->header('X-RateLimit-Remaining', $status->remaining)
            ->header('X-RateLimit-Reset', $status->resetAfter);
    }
}
```

📄 `app/Http/Kernel.php`

```php
'ratelimit' => \App\Http\Middleware\RateLimitHeaders::class,
```

Usage:

```php
Route::get('/api/orders', [OrderController::class, 'index'])
    ->middleware('ratelimit');
```

---

## 4️⃣ JSON API Example (Custom Controller)

```php
<?php

use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

$config = [
    'driver' => 'mysql',
    'mysql_dsn' => 'mysql:host=127.0.0.1;dbname=ratelimiter',
    'mysql_user' => 'root'
];

$resolver = new RateLimiterResolver($config);
$limiter = $resolver->resolve();

header('Content-Type: application/json');
$key = $_SERVER['REMOTE_ADDR'];

try {
    $status = $limiter->attempt(
        $key,
        RateLimitActionEnum::API_CALL,
        PlatformEnum::API
    );

    echo json_encode([
        'success' => true,
        'remaining' => $status->remaining,
        'reset_after' => $status->resetAfter,
    ]);
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'retry_after' => $status->retryAfter ?? 60,
    ]);
}
```

---

## 5️⃣ Custom Enum Contracts Example

```php
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;

enum MyActionEnum: string implements RateLimitActionInterface
{
    case ORDER_SUBMIT = 'order_submit';
    public function value(): string { return $this->value; }
}

enum MyPlatformEnum: string implements PlatformInterface
{
    case CUSTOMER_APP = 'customer_app';
    public function value(): string { return $this->value; }
}

$config = ['driver' => 'redis'];
$resolver = new RateLimiterResolver($config);
$limiter = $resolver->resolve();

$status = $limiter->attempt(
    'user-501',
    MyActionEnum::ORDER_SUBMIT,
    MyPlatformEnum::CUSTOMER_APP
);

echo json_encode($status->toArray(), JSON_PRETTY_PRINT);
```

---

## 6️⃣ Custom Header Key (X-API-KEY Mode)

```php
$app->add(new RateLimitHeadersMiddleware(
    $limiter,
    RateLimitActionEnum::API_CALL,
    PlatformEnum::API,
    keyHeader: 'X-API-KEY'
));
```

---

## 7️⃣ Exponential Backoff (Redis Adaptive Mode)

```php
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

$config = [
    'driver' => 'redis',
    'redis_host' => '127.0.0.1',
    'redis_port' => 6379,
    'backoff_base' => 2,
    'backoff_max' => 3600,
];

$resolver = new RateLimiterResolver($config);
$limiter  = $resolver->resolve();

$key = 'ip:' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');

try {
    $status = $limiter->attempt(
        $key,
        RateLimitActionEnum::LOGIN,
        PlatformEnum::WEB
    );
    echo "✅ Allowed – Remaining: {$status->remaining}";
} catch (TooManyRequestsException $e) {
    echo "⛔ Retry after {$status->backoffSeconds}s (next allowed: {$status->nextAllowedAt})";
}
```

---

## 8️⃣ Environment Variables (Global Configuration)

```env
GLOBAL_RATE_LIMIT=5
GLOBAL_RATE_WINDOW=60
BACKOFF_BASE=2
BACKOFF_MAX=3600
```

### 📐 Backoff Formula

```
backoff_seconds = min( BACKOFF_BASE ** violation_count , BACKOFF_MAX )
```

---

## ✅ Exponential Pattern Example

| Violation | Delay (sec) |
|-----------|-------------|
| 1         | 2           |
| 2         | 4           |
| 3         | 8           |
| 4         | 16          |
| 5         | 32          |
| ...       | ...         |

---

✅ For more details, visit:

* Main Documentation: [`README.md`](../README.md)
* Changelog: [`CHANGELOG.md`](../CHANGELOG.md)
* CI Workflow: `.github/workflows/ci.yml`