![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

[![Version](https://img.shields.io/packagist/v/maatify/data-adapters?label=Version&color=4C1)](https://packagist.org/packages/maatify/data-adapters)
[![PHP](https://img.shields.io/packagist/php-v/maatify/data-adapters?label=PHP&color=777BB3)](https://packagist.org/packages/maatify/data-adapters)
[![Build](https://github.com/Maatify/data-adapters/actions/workflows/test.yml/badge.svg?label=Build&color=brightgreen)](https://github.com/Maatify/data-adapters/actions/workflows/test.yml)

[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/data-adapters?label=Monthly%20Downloads&color=00A8E8)](https://packagist.org/packages/maatify/data-adapters)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/data-adapters?label=Total%20Downloads&color=2AA9E0)](https://packagist.org/packages/maatify/data-adapters)

[![Stars](https://img.shields.io/github/stars/Maatify/data-adapters?label=Stars&color=FFD43B&cacheSeconds=3600)](https://github.com/Maatify/data-adapters/stargazers)
[![License](https://img.shields.io/github/license/Maatify/data-adapters?label=License&color=blueviolet)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Stable-success?style=flat-square)]()
[![Code Quality](https://img.shields.io/codefactor/grade/github/Maatify/data-adapters/main?color=brightgreen)](https://www.codefactor.io/repository/github/Maatify/data-adapters)

[![Changelog](https://img.shields.io/badge/Changelog-View-blue)](CHANGELOG.md)
[![Security](https://img.shields.io/badge/Security-Policy-important)](SECURITY.md)

---

# 📦 maatify/data-adapters
**Unified Data Connectivity & Diagnostics Layer**

---

> 🔗 [بالعربي 🇸🇦 ](./README-AR.md)

## 🧭 Overview

**maatify/data-adapters** is a unified, framework-agnostic layer for managing Redis, MongoDB,
and MySQL connections with centralized diagnostics and auto-detection.
It serves as the core data layer of the **Maatify Ecosystem**.

---

## ⚙️ Installation

```bash
composer require maatify/data-adapters
```

---

> **Requirements:**
> • PHP ≥ 8.4
> • Redis (phpredis recommended — Predis auto-fallback)
> • MongoDB extension (optional)
> • PDO MySQL required (DBAL optional)

---

## ✨ Features
- Unified configuration engine (Phase 13)
- Registry → DSN → Legacy priority system
- Fully unified config builders for MySQL / MongoDB / Redis
- Multi-profile MySQL & MongoDB (unlimited profiles)
- Redis unified builder (future multi-profile ready)
- Centralized diagnostics & health checks
- Unified raw driver layer (`getDriver()`) for PDO / DBAL / MongoDB / Redis
- Full DSN stabilization (PDO-style + Doctrine URL-style)

---

# 🌟 Why Choose `maatify/data-adapters`?

`maatify/data-adapters` is not just another database wrapper —
it is a **unified, production-ready connectivity engine** powering the entire Maatify Ecosystem.

Here’s why developers and teams prefer it:

---

## 🚀 1. One Resolver for All Data Sources
Connect to **MySQL (PDO/DBAL)**, **MongoDB**, **Redis**, and **Predis** using the same API:

```php
$adapter = $resolver->resolve('mysql.main');
````

No manual wiring, no duplicate logic — **one resolver handles everything**.

---

## 🧠 2. DSN-First Architecture (PDO + Doctrine + Mongo + Redis)

Phase 13 introduced a complete DSN engine:

* `mysql://user:pass@host:3306/db`
* `mongodb://host:27017/admin`
* `redis://:password@host:6379/2`

This ensures **clean configuration**, **multi-profile support**, and **zero boilerplate**.

---

## 🔁 3. Multi-Profile Support (Unlimited Profiles)

```php
mysql.main
mysql.logs
mysql.analytics
mongo.activity
redis.cache
```

Run **isolated databases per module** with effortless switching.

---

## 🧩 4. Raw Driver Access (Phase 15)

Need native database power?

```php
$pdo = $mysql->getDriver(); // PDO
$db  = $mongo->getDriver(); // MongoDB\Database
$rd  = $redis->getDriver(); // Redis or Predis\Client
```

Perfect for advanced queries, analytics, or legacy migrations.

---

## 🩺 5. Built-In Diagnostics & Telemetry

Each adapter includes:

* `healthCheck()`
* latency metrics
* Prometheus-ready telemetry
* structured log context
* fallback logging

Ideal for microservices & modern cloud infrastructure.

---

## 🛡 6. Bulletproof Configuration Resolution

A 3-layer priority model:

```
REGISTRY  →  DSN  →  Legacy environment variables
```

This gives the library:

* ⭐ Predictable behavior
* ⭐ Zero config duplication
* ⭐ Clean environment files
* ⭐ Dynamic overrides in production

---

## 🧪 7. Fully Tested — 93% Coverage

Test suite includes:

* DSN parsing
* Registry merging
* Multi-profile routing
* Raw driver access
* Diagnostics & telemetry
* Redis/Mongo/MySQL integration

CI is stable and GitHub Actions validated.

---

## 🔥 8. Framework-Agnostic

Works with:

* maatify/bootstrap
* maatify/security-guard
* maatify/rate-limiter
* maatify/mongo-activity
* Laravel, Symfony, Slim, custom frameworks

You own the stack — the library adapts to you.

---

## 🌐 9. Enterprise-Grade Production Stability

Phase 15–17 implemented:

* DSN stabilization
* Doctrine URL normalization
* strict typing (PHPStan MAX)
* DBAL safety
* CI stability patch
* cross-platform reliability (Linux/macOS/CI)

This is not hobby code — it’s ecosystem infrastructure.

---

## 🌈 10. Designed for Multi-Service Architectures

Supports:

* microservices
* containers & Docker
* Kubernetes
* cloud deployments
* serverless adapters
* distributed logging
* cross-service caches

Your data layer becomes **scalable, testable, and predictable**.

---

# 🔥 In short…

**If you want Redis, MongoDB, and MySQL to behave like a single unified system —
this is the library.**

---
## 🔥 New in Phase 13 — Unified Configuration Architecture

Phase 13 finalizes the unified configuration engine across all adapters.

### ✔ Global Configuration Priority
**Registry → DSN → Legacy (Deprecated)**
This applies consistently to MySQL, MongoDB, and Redis.

### ✔ Unified Builder Behavior (Final)
All builders now:
- Return **fully normalized configuration objects**
- Use identical DSN parsing rules
- Support unlimited profiles (`mysql.main`, `mongo.logs`, …)
- Merge configuration with the same priority logic
- Expose driver + profile metadata

### ✔ Registry JSON Support
A new `registry.json` file allows runtime overrides:

```json
{
  "mysql": {
    "main": { "user": "override_user" }
  },
  "redis": {
    "cache": { "host": "10.0.0.1", "port": 6380 }
  }
}
````

This overrides DSN & legacy variables automatically.

### ✔ Redis Builder Unified

The Redis builder has been rewritten to match MySQL/Mongo logic
and is now **future-ready for multi-profile support**.

---

# 🔥 **New in Phase 15 — Raw Driver Layer + DSN Stabilization**

Phase 15 introduces a **unified low-level driver access layer** and fully stabilizes
DSN parsing across all adapters (PDO, Doctrine, Mongo, Redis).

This phase ensures every adapter exposes its **native underlying driver** safely:

| Adapter      | raw driver (`getDriver()`) |
|--------------|----------------------------|
| MySQL (PDO)  | `PDO`                      |
| MySQL (DBAL) | `Doctrine\DBAL\Connection` |
| MongoDB      | `MongoDB\Database`         |
| Redis        | `Redis` or `Predis\Client` |

---

## ✔ Unified Raw Driver Access (`getDriver()`)

Every adapter now supports:

```php
$pdo  = $mysql->getDriver();          // PDO
$dbal = $mysqlDbal->getDriver();      // Doctrine Connection
$mongo = $mongoMain->getDriver();     // MongoDB Database
$redis = $redisCache->getDriver();    // Redis or Predis
```

Perfect for:

* Building your own query layers
* Passing native connections into other libraries
* High-performance custom operations

---

## ✔ Full DSN Stabilization (PDO + Doctrine)

Phase 15 completes the DSN architecture:

### PDO-style DSNs

```
mysql:host=127.0.0.1;port=3306;dbname=demo
```

### Doctrine URL DSNs

```
mysql://user:P%40ss%3B@10.0.0.5:3306/mydb
```

✓ Special characters now parsed correctly
✓ Passwords preserved safely (encoded/decoded)
✓ Unified parser for both formats
✓ No more `parse_url()` issues

---

## ✔ Accurate Driver Routing (`driver=pdo` / `driver=dbal`)

Profiles can now explicitly choose driver:

```
MYSQL_MAIN_DRIVER=pdo
MYSQL_LOGS_DRIVER=dbal
MYSQL_REPORTING_DRIVER=dbal
```

Or DSN auto-detects driver automatically.

---

## ✔ Real MySQL Dual-Driver Test (Local + CI)

Phase 15 upgrades the integration tests so they:

* Load real `.env` values
* Support DSN override via `putenv()`
* Work in both **CI** and **local development**
* Auto-detect PDO and DBAL correctness
* Skip only when connection fails intentionally

---

## ✔ Improved Config Normalization

`MySqlConfigBuilder` / `MongoConfigBuilder` / `RedisConfigBuilder` now guarantee:

* No null database issues
* No partial DSN parsing
* No empty user/pass fallback bugs
* No malformed DSN from registry merge

---

## ✔ Raw Driver Tests Added

* `RawDriverRoutingTest`
* `RawAccessTest`
* `MysqlDsnParserTest` (enhanced)

All confirming:

* Correct low-level driver type
* Correct DSN behavior
* Correct profile routing
* Correct priority merge

---

# 🟦 Summary of Phase 15

✓ Raw driver access layer
✓ Stable DSN parsing for all formats
✓ Reliable driver routing
✓ Full MySqlConfigBuilder normalization
✓ Raw-access tests + DSN tests + dual-driver tests
✓ Architecture ready for **Failover Routing (Phase 16)**

---

## 🧩 Compatibility
Fully framework-agnostic.
Optional auto-wiring available via **maatify/bootstrap**.
- Fully compatible with the new Phase 13 unified configuration engine
- Supports runtime overrides through registry.json
---


## 🚀 Quick Usage (Updated for Phase 15 — Raw Driver Layer + DSN Stabilization)

```php
use Maatify\DataAdapters\Core\EnvironmentConfig;
use Maatify\DataAdapters\Core\DatabaseResolver;

$config   = new EnvironmentConfig(__DIR__);

// Phase 13: Registry → DSN → Legacy
$resolver = new DatabaseResolver($config);

// ------------------------------------
// 🟣 MySQL — Multi-Profile (Phase 13)
// ------------------------------------
$mainDb = $resolver->resolve("mysql.main", autoConnect: true);
$logsDb = $resolver->resolve("mysql.logs");
$billingDb = $resolver->resolve("mysql.billing"); // unlimited profiles

$stmt = $mainDb->getConnection()->query("SELECT 1");
echo $stmt->fetchColumn(); // 1

// ------------------------------------
// 🟢 MongoDB — Multi-Profile (Phase 13)
// ------------------------------------
$mongoMain = $resolver->resolve("mongo.main", autoConnect: true);
$mongoLogs = $resolver->resolve("mongo.logs");

$db     = $mongoMain->getConnection()->selectDatabase("admin");
$ok     = $db->command(["ping" => 1])->toArray()[0]["ok"];

echo $ok; // 1

// ------------------------------------
// 🔴 Redis — Unified Builder (Phase 13)
// ------------------------------------
$redis = $resolver->resolve("redis.cache", autoConnect: true);
$redis->getConnection()->set("key", "maatify");
echo $redis->getConnection()->get("key"); // maatify

// ------------------------------------
// 🔍 Debug Final Merged Configuration
// (Phase 13 unified DTO output)
// ------------------------------------
print_r(
    $mainDb->debugConfig()->toArray()
);

/*
Output example:
[
    "dsn"      => "mysql://user:pass@127.0.0.1:3306/main",
    "host"     => "127.0.0.1",
    "port"     => "3306",
    "user"     => "user",
    "pass"     => "pass",
    "database" => "main",
    "driver"   => "pdo",
    "profile"  => "main"
]
*/

// ------------------------------------
// 📦 Registry Override Example
// registry.json:
// {
//   "mysql": { "main": { "user": "override_user" } }
// }
// ------------------------------------

$mainDbFromRegistry = $resolver->resolve("mysql.main");
print_r($mainDbFromRegistry->debugConfig()->user);
// override_user


// ------------------------------------
// 🛠 Raw Driver Access (Phase 15)
// ------------------------------------
$native = $mainDb->getDriver();   // PDO or Doctrine Connection
var_dump($native instanceof PDO); // true (example)

$mongoNative = $mongoMain->getDriver(); // MongoDB\Database

$redisNative = $redis->getDriver(); // Redis or Predis\Client
```

---

## 🧩 Diagnostics & Health Checks

All adapters include self-diagnostic capabilities and unified health reporting.

```php
use Maatify\DataAdapters\Diagnostics\DiagnosticService;

$diagnostic = new DiagnosticService($config, $resolver);
echo $diagnostic->toJson();
```

**Example Output**

```json
{
  "diagnostics": [
    {"adapter": "redis", "connected": true},
    {"adapter": "mongo", "connected": true},
    {"adapter": "mysql", "connected": true}
  ]
}
```

## 🧪 Testing

```bash
vendor/bin/phpunit
```

- Added Phase 15 tests:
    - RawDriverRoutingTest
    - RawAccessTest
    - Enhanced DSN parsing tests
    - Real MySQL dual-driver test (PDO + DBAL)

**Coverage:** **≈ 93%**
**Status:** ✅ All tests passing (DSN, registry, multi-profile, diagnostics, metrics)
**Suites:**

* Unit Tests
* Integration Tests
* DSN Parsing Tests
* Registry Merge Tests
* Multi-Profile MySQL & MongoDB Tests
* Redis Builder Tests
* Diagnostics & Metrics Tests
* Phase 15 introduced raw driver validation inside diagnostics, ensuring
  accurate low-level connectivity (PDO/DBAL/MongoDB/Redis).

---


## 📚 Documentation

* **Introduction:** [`docs/README.intro.md`](docs/README.intro.md)
* **Environment Reference:** [`docs/env.md`](docs/env.md)
* **Telemetry:** [`docs/telemetry.md`](docs/telemetry.md)
* **Architecture:** [`docs/architecture.md`](docs/architecture.md)
* **Multi-Profile MySQL:** [`docs/mysql-profiles.md`](docs/mysql-profiles.md)
* **Usage Examples:** [`docs/USAGE.md`](docs/USAGE.md)
* **Phases:** [`docs/README.roadmap.md`](docs/README.roadmap.md)
* **Changelog:** [`CHANGELOG.md`](CHANGELOG.md)

---

## 🔗 Related Maatify Libraries

* [maatify/common](https://github.com/Maatify/common)
* [maatify/psr-logger](https://github.com/Maatify/psr-logger)
* [maatify/bootstrap](https://github.com/Maatify/bootstrap)
* [maatify/rate-limiter](https://github.com/Maatify/rate-limiter)
* [maatify/security-guard](https://github.com/Maatify/security-guard)
* [maatify/mongo-activity](https://github.com/Maatify/mongo-activity)

---
> 🔗 **Full documentation & release notes:** see [/docs/README.full.md](docs/README.full.md)
---

## 🪪 License

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)
You’re free to use, modify, and distribute this library with attribution.

---

## 👤 Author
**Mohamed Abdulalim** — Backend Lead & Technical Architect
🔗 https://www.maatify.dev | ✉️ mohamed@maatify.dev

## 🤝 Contributors
Special thanks to the Maatify.dev engineering team and open-source contributors.


---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
