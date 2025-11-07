# 🧩 **Maatify Rate Limiter**

A PSR-compliant Rate Limiter library supporting Redis, MongoDB, and MySQL
— with dynamic driver resolver, middleware integration, and reusable enum contracts.

---

<!-- PHASE_STATUS_START -->

## ✅ Completed Phases

* [x] Phase 1 – Environment Setup (Local)
* [x] Phase 2 – Core Architecture
* [x] Phase 3 – Storage Drivers
* [x] Phase 3.1 – Enum Contracts Refactor
* [x] Phase 4 – Resolver & Middleware

<!-- PHASE_STATUS_END -->

---

## ⚙️ Local Setup

```bash
composer install
cp .env.example .env
```

Then edit `.env` to match your local database and driver configuration.

---

## 🧠 Description

**Maatify Rate Limiter** provides a unified abstraction for distributed rate limiting
with multiple backends (Redis, MongoDB, MySQL) and dynamic resolver support.

It follows **PSR-12**, **PSR-15**, and **PSR-7** standards,
and can be integrated directly with frameworks like **Slim** or **Laravel**.

---

## 📂 Project Structure

```
maatify-rate-limiter/
│
├── .env.example
├── composer.json
├── .github/
│   └── workflows/
│       └── ci.yml
├── src/
│   ├── Config/
│   │   └── RateLimitConfig.php
│   ├── Contracts/
│   │   ├── RateLimiterInterface.php
│   │   ├── RateLimitActionInterface.php
│   │   └── PlatformInterface.php
│   ├── DTO/
│   │   └── RateLimitStatusDTO.php
│   ├── Drivers/
│   │   ├── RedisRateLimiter.php
│   │   ├── MongoRateLimiter.php
│   │   └── MySQLRateLimiter.php
│   ├── Enums/
│   │   ├── RateLimitActionEnum.php
│   │   └── PlatformEnum.php
│   ├── Exceptions/
│   │   └── TooManyRequestsException.php
│   ├── Middleware/
│   │   └── RateLimitHeadersMiddleware.php
│   └── Resolver/
│       └── RateLimiterResolver.php
│
├── tests/
│   ├── bootstrap.php
│   ├── CoreStructureTest.php
│   ├── DriversTest.php
│   └── MiddlewareTest.php
│
├── docs/
│   └── phases/
│       ├── README.phase1.md
│       ├── README.phase2.md
│       ├── README.phase3.md
│       ├── README.phase3.1.md
│       └── README.phase4.md
│
├── CHANGELOG.md
├── VERSION
└── README.md
```

---

## 🧩 Current Version

```
1.0.0-alpha-phase4
```

---

## 🧾 CHANGELOG SUMMARY

### Phase 1 – Environment Setup

* Composer initialized
* PHPUnit and GitHub Actions setup
* `.env.example` created for Redis, Mongo, MySQL

### Phase 2 – Core Architecture

* Added `RateLimiterInterface`, `RateLimitStatusDTO`, `TooManyRequestsException`
* Created enums `RateLimitActionEnum`, `PlatformEnum`
* Added configuration file `RateLimitConfig`

### Phase 3 – Storage Drivers

* Added drivers:

    * `RedisRateLimiter`
    * `MongoRateLimiter`
    * `MySQLRateLimiter`
* Added unit tests for configuration

### Phase 3.1 – Enum Contracts Refactor

* Introduced `RateLimitActionInterface` and `PlatformInterface`
* Updated enums to implement interfaces
* Modified drivers & interface to accept interface-based enums
* Greatly improved **reusability & flexibility**

### Phase 4 – Resolver & Middleware

* Added `RateLimiterResolver` (auto driver selection)
* Added PSR-15 compliant `RateLimitHeadersMiddleware`
* Added examples for **Slim**, **Laravel**, and **Custom Integration**

---

# 📘 USAGE EXAMPLES

---

## 🧱 1️⃣ Basic Example (Native PHP)

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
```

---

## ⚙️ 2️⃣ Slim Framework Example (Full Middleware Integration)

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

📘 Output Headers:

```
X-RateLimit-Limit: 5
X-RateLimit-Remaining: 4
X-RateLimit-Reset: 60
Retry-After: 60
```

---

## 🧩 3️⃣ Laravel Example (Custom Middleware)

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
            $status = $limiter->attempt($key, RateLimitActionEnum::API_CALL, PlatformEnum::API);
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

📘 Register in `Kernel.php`:

```php
'ratelimit' => \App\Http\Middleware\RateLimitHeaders::class,
```

Usage:

```php
Route::get('/api/orders', [OrderController::class, 'index'])->middleware('ratelimit');
```

---

## 🌍 4️⃣ API JSON Example (Custom Controller)

```php
<?php

use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\Exceptions\TooManyRequestsException;

$config = ['driver' => 'mysql', 'mysql_dsn' => 'mysql:host=127.0.0.1;dbname=ratelimiter', 'mysql_user' => 'root'];

$resolver = new RateLimiterResolver($config);
$limiter = $resolver->resolve();

header('Content-Type: application/json');
$key = $_SERVER['REMOTE_ADDR'];

try {
    $status = $limiter->attempt($key, RateLimitActionEnum::API_CALL, PlatformEnum::API);

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

## 🧠 5️⃣ Custom Enum Contracts Example (From Phase 3.1)

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

$status = $limiter->attempt('user-501', MyActionEnum::ORDER_SUBMIT, MyPlatformEnum::CUSTOMER_APP);

echo json_encode($status->toArray(), JSON_PRETTY_PRINT);
```

---

## 🧩 6️⃣ Custom Header Key Example (X-API-KEY Mode)

```php
$app->add(new RateLimitHeadersMiddleware(
    $limiter,
    RateLimitActionEnum::API_CALL,
    PlatformEnum::API,
    keyHeader: 'X-API-KEY'
));
```

---

## 📦 Composer Dependencies

To use this library fully:

```bash
composer require psr/http-message psr/http-server-middleware psr/http-server-handler
```

For Slim Framework support:

```bash
composer require slim/slim
```

---

## ✅ Summary Table

| Environment           | Supported | Notes                       |
|-----------------------|-----------|-----------------------------|
| PHP (raw)             | ✅         | Works out of the box        |
| Slim                  | ✅         | Fully PSR-15 compatible     |
| Laravel               | ✅         | Custom middleware ready     |
| Custom Enums          | ✅         | Through interface contracts |
| Redis / Mongo / MySQL | ✅         | Switch easily via resolver  |
| PSR Standards         | ✅         | PSR-7 / PSR-15 / PSR-12     |

---

## 🧱 Authors & Credits

**Developed by:** [Maatify.dev](https://www.maatify.dev)
**Maintainer:** Mohamed Abdulalim
**License:** MIT
**Project:** maatify:rate-limiter

---

