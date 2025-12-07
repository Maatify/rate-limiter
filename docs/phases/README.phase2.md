# 🧩 Phase 2 – Core Architecture

[![Maatify Rate Limiter](https://img.shields.io/badge/Maatify-Rate--Limiter-blue?style=for-the-badge)](https://github.com/Maatify/rate-limiter)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

**Goal:**  
Define the foundational structure of the Maatify Rate Limiter library,  
including interfaces, DTOs, enums, and configuration classes.

---

## 🎯 Objective

Establish a clean, PSR-12–compliant architecture that defines  
the abstraction layer and ensures consistent communication  
between all future rate limiter drivers (Redis, Mongo, MySQL).

---

## ✅ Tasks Completed

- Created **`RateLimiterInterface`** defining core contract methods:
  - `attempt()`
  - `reset()`
  - `getStatus()`
- Implemented **`RateLimitStatusDTO`** — standardized data transfer object
- Added **`TooManyRequestsException`** for overload handling
- Added base enums:
  - `RateLimitActionEnum`
  - `PlatformEnum`
- Added configuration class `RateLimitConfig` for environment-driven settings
- Integrated `declare(strict_types=1);` and full DocBlocks across all files

---

## 📂 Files Created

```

src/
├── Config/
│   └── RateLimitConfig.php
├── Contracts/
│   └── RateLimiterInterface.php
├── DTO/
│   └── RateLimitStatusDTO.php
├── Enums/
│   ├── RateLimitActionEnum.php
│   └── PlatformEnum.php
└── Exceptions/
└── TooManyRequestsException.php

```

---

## 🧱 Architecture Overview

```

RateLimiterInterface  <───  Implemented by Redis/Mongo/MySQL drivers
│
├── attempt(string $key, RateLimitActionEnum $action, PlatformEnum $platform): RateLimitStatusDTO
├── reset(string $key, RateLimitActionEnum $action, PlatformEnum $platform): bool
└── getStatus(string $key, RateLimitActionEnum $action, PlatformEnum $platform): RateLimitStatusDTO

````

---

## 🧩 Example (Interface Usage)

```php
use Maatify\RateLimiter\Contracts\RateLimiterInterface;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

final class ExampleRateLimiter implements RateLimiterInterface
{
    public function attempt(string $key, RateLimitActionEnum $action, PlatformEnum $platform): RateLimitStatusDTO
    {
        return new RateLimitStatusDTO(limit: 5, remaining: 4, resetAfter: 60);
    }

    public function reset(string $key, RateLimitActionEnum $action, PlatformEnum $platform): bool
    {
        return true;
    }

    public function getStatus(string $key, RateLimitActionEnum $action, PlatformEnum $platform): RateLimitStatusDTO
    {
        return new RateLimitStatusDTO(limit: 5, remaining: 4, resetAfter: 30);
    }
}
````

---

## 🧠 DTO Structure

```php
final class RateLimitStatusDTO
{
    public function __construct(
        public readonly int $limit,
        public readonly int $remaining,
        public readonly int $resetAfter,
        public readonly ?int $retryAfter = null
    ) {}

    public function toArray(): array
    {
        return [
            'limit' => $this->limit,
            'remaining' => $this->remaining,
            'reset_after' => $this->resetAfter,
            'retry_after' => $this->retryAfter,
        ];
    }
}
```

---

## ⚙️ Enum Definitions

```php
enum RateLimitActionEnum: string
{
    case LOGIN = 'login';
    case REGISTER = 'register';
    case OTP_REQUEST = 'otp_request';
}

enum PlatformEnum: string
{
    case WEB = 'web';
    case API = 'api';
    case MOBILE = 'mobile';
}
```

---

## 🧪 Unit Test Example

```php
use PHPUnit\Framework\TestCase;
use Maatify\RateLimiter\DTO\RateLimitStatusDTO;

final class CoreStructureTest extends TestCase
{
    public function testDtoToArray(): void
    {
        $dto = new RateLimitStatusDTO(10, 5, 60, 30);
        $this->assertSame([
            'limit' => 10,
            'remaining' => 5,
            'reset_after' => 60,
            'retry_after' => 30,
        ], $dto->toArray());
    }
}
```

---

## 📊 Result Summary

| Component                  | Status | Description                             |
|----------------------------|--------|-----------------------------------------|
| `RateLimiterInterface`     | ✅      | Core abstraction for rate limiting      |
| `RateLimitStatusDTO`       | ✅      | Standardized rate-limit state container |
| `RateLimitActionEnum`      | ✅      | Defines logical user actions            |
| `PlatformEnum`             | ✅      | Defines origin platform type            |
| `TooManyRequestsException` | ✅      | Exception for overloads                 |
| `RateLimitConfig`          | ✅      | Loads config from environment variables |
| Unit Tests                 | ✅      | All verified locally                    |

---

## 🧩 Version

```
1.0.0-alpha-phase2
```

---

## 📜 Notes

This phase establishes the **core contracts and base data models** of the Maatify Rate Limiter.
From this point onward, all driver implementations, resolvers, and middleware will
strictly conform to these interfaces and DTO structures.
