# Maatify Rate Limiter

**PSR-compliant distributed rate limiting engine supporting Redis, MongoDB, and MySQL with adaptive exponential backoff.**

![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

[![Version](https://img.shields.io/packagist/v/maatify/rate-limiter?label=Version&color=4C1)](https://packagist.org/packages/maatify/rate-limiter)
[![PHP](https://img.shields.io/packagist/php-v/maatify/rate-limiter?label=PHP&color=777BB3)](https://packagist.org/packages/maatify/rate-limiter)
![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-blue)

[![Build](https://github.com/Maatify/rate-limiter/actions/workflows/ci.yml/badge.svg?label=Build&color=brightgreen)](https://github.com/Maatify/rate-limiter/actions/workflows/ci.yml)

![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/rate-limiter?label=Monthly%20Downloads&color=00A8E8)
![Total Downloads](https://img.shields.io/packagist/dt/maatify/rate-limiter?label=Total%20Downloads&color=2AA9E0)

![Stars](https://img.shields.io/github/stars/Maatify/rate-limiter?label=Stars&color=FFD43B)
[![License](https://img.shields.io/github/license/Maatify/rate-limiter?label=License&color=blueviolet)](LICENSE)
![Status](https://img.shields.io/badge/Status-Stable-success)
[![Code Quality](https://img.shields.io/codefactor/grade/github/Maatify/rate-limiter/main?color=brightgreen)](https://www.codefactor.io/repository/github/Maatify/rate-limiter)

![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-4E8CAE)
![Coverage](https://img.shields.io/badge/Coverage-95%25-success)

[![Changelog](https://img.shields.io/badge/Changelog-View-blue)](CHANGELOG.md)
[![Security](https://img.shields.io/badge/Security-Policy-important)](SECURITY.md)

---

# 🚀 Overview

**Maatify Rate Limiter** is a fully decoupled, PSR-compliant rate-limiting engine designed for:

- Native PHP
- Slim Framework
- Laravel Middleware
- Custom API Gateways

It provides **distributed rate-limiting with adaptive exponential backoff**, unified across:

- **Redis**
- **MongoDB**
- **MySQL**

### Why this library?

- Zero storage lock-in  
- Unified attempt/status/reset API  
- Global per-IP rate limit  
- Adaptive **Exponential Backoff (2ⁿ)**  
- Full PSR-7 / PSR-15 middleware compatibility  
- PHPStan Level Max ready  

---

## ✅ Supported Drivers

| Backend  | Driver Type | Use Case                          |
|----------|-------------|-----------------------------------|
| Redis    | Real Driver | High-performance in-memory limits |
| MongoDB  | Real Driver | Distributed analytics             |
| MySQL    | Real Driver | Persistent audit & compliance     |

---

# 📦 Installation

```bash
composer require maatify/rate-limiter
````

---

# ⚡ Quick Usage

```php
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use Maatify\RateLimiter\Enums\RateLimitActionEnum;
use Maatify\RateLimiter\Enums\PlatformEnum;

$resolver = new RateLimiterResolver(['driver' => 'redis']);
$limiter  = $resolver->resolve();

$status = $limiter->attempt(
    '127.0.0.1',
    RateLimitActionEnum::LOGIN,
    PlatformEnum::WEB
);

echo $status->remaining;
```

📘 **Full usage examples (Native, Slim, Laravel, API, Enums, Backoff):**
➡️ **[examples/Examples.md](examples/Examples.md)**

---

# 🧩 Key Features

* **Unified API**: `attempt()`, `status()`, `reset()`
* **Global Per-IP Limit**
* **Adaptive Exponential Backoff**
* **DTO-based Response Model**
* **PSR-7 / PSR-15 Middleware Ready**
* **Custom Enum Contracts**
* **Driver Resolver**
* **Strict Validation & Type Safety**
* **PHPStan Level Max**

---

# 📄 Documentation

* [**Arabic Documentation**](README-AR.md)
* [**Usage Examples**](examples/Examples.md)
* [**Changelog**](CHANGELOG.md)
* [**Security Policy**](SECURITY.md)

<details>
<summary><strong>📚 Development History & Phase Details</strong></summary>

* Phase 1 – Environment Setup
* Phase 2 – Core Architecture
* Phase 3 – Storage Drivers
* Phase 3.1 – Enum Contracts Refactor
* Phase 4 – Resolver & Middleware
* Phase 4.1 – Continuous Integration
* Phase 5 – Exponential Backoff & Global Rate Limit

</details>

---

# 🧱 Dependencies Overview

`maatify/rate-limiter` relies on PSR standards and selected open-source libraries.

---

## 🔌 Direct Open-Source Dependencies

| Library                    | Purpose                 |
|----------------------------|-------------------------|
| psr/http-message           | HTTP message interfaces |
| psr/http-server-middleware | PSR-15 middleware       |
| psr/http-server-handler    | Request handler         |
| redis / predis             | Redis driver            |
| mongodb/mongodb            | MongoDB driver          |
| phpunit/phpunit            | Testing                 |
| phpstan/phpstan            | Static analysis         |

---

# 🧪 Testing

```bash
composer test
```

Runs:

* Driver consistency tests
* Resolver tests
* Middleware header tests
* Backoff & global limit tests
* Coverage reporting

---

## 🪪 License

**[MIT License](LICENSE)**
© [Maatify.dev](https://www.maatify.dev) — Free to use, modify, and distribute with attribution.

---

## 👤 Author

Engineered by **Mohamed Abdulalim** ([@megyptm](https://github.com/megyptm))
Backend Lead & Technical Architect — [https://www.maatify.dev](https://www.maatify.dev)

---

## 🤝 Contributors

Special thanks to the Maatify.dev engineering team and all open-source contributors.
Your efforts help make this library stable, secure, and production-ready.

Before opening a Pull Request, please read:

* [Contributing Guide](CONTRIBUTING.md)
* [Code of Conduct](CODE_OF_CONDUCT.md)

---

<p align="center">
  <sub>Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</sub>
</p>