![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

# ⚙️ Maatify Bootstrap — Full Technical Documentation
**Project:** `maatify:bootstrap`
**Version:** 1.0.0-rc
**Author:** [Mohamed Abdulalim (megyptm)](mailto:mohamed@maatify.dev)
**License:** MIT
**© 2025 Maatify.dev**
### Unified Environment Initialization & Startup Foundation

---

## 🧭 Overview
`maatify/bootstrap` provides a consistent and safe initialization layer for all Maatify libraries and applications.
It standardizes environment loading, timezone setup, diagnostics, and startup integrity checks — ensuring predictable and secure application bootstrapping across development, testing, and production environments.

This library serves as the core foundation for all other Maatify components such as:
- `maatify/common`
- `maatify/psr-logger`
- `maatify/redis-cache`
- `maatify/data-adapters`
- `maatify/rate-limiter`
- `maatify/security-guard`

---

## ✅ Completed Phases

| Phase | Title                         | Status      |
|-------|-------------------------------|-------------|
| 1     | Foundation Setup              | ✅ Completed |
| 2     | Bootstrap Core                | ✅ Completed |
| 3     | Helpers & Utilities           | ✅ Completed |
| 4     | Integration Layer             | ✅ Completed |
| 5     | Diagnostics & Safe Mode       | ✅ Completed |
| 6     | CI/CD & Docker                | ✅ Completed |
| 7     | Release & Documentation Merge | ✅ Completed |

---

## 🧱 Phase 1 — Foundation Setup

### 🎯 Goal
Initialize the core bootstrap structure, namespaces, and environment loader foundation.

### ⚙️ Implemented Features
- PSR-4 autoload configuration
- `EnvironmentLoader` class for unified `.env` file handling
- `.env.example` template
- PHPUnit configuration for base environment testing

### 🧠 Usage Example
```php
use Maatify\Bootstrap\Core\EnvironmentLoader;

$env = new EnvironmentLoader(__DIR__);
$env->load();
````

### ✅ Verification

* `.env.local` and `.env.testing` supported
* Defaults timezone to `Africa/Cairo` if not set
* PHPUnit passes all base loader tests

---

## 🧱 Phase 2 — Bootstrap Core

### 🎯 Goal

Implement main `Bootstrap` entry point integrating environment loader, timezone setup, and error handler registration.

### ⚙️ Implemented Features

* Added `Bootstrap` class with `init()` static entry
* Integrated `EnvironmentLoader` and custom error handler
* Ensured idempotency to prevent double initialization
* Set timezone dynamically from environment

### 🧠 Usage Example

```php
use Maatify\Bootstrap\Core\Bootstrap;

Bootstrap::init();
```

### ✅ Verification

* Multiple calls to `init()` cause no side effects
* Logs error handler initialization success

---

## 🧱 Phase 3 — Helpers & Utilities

### 🎯 Goal

Introduce helper utilities for cross-library bootstrap consistency.

### ⚙️ Implemented Features

* `PathHelper`: ensures consistent project-relative paths
* `EnvHelper`: unified, cached access to environment variables
* Integration with `maatify/common` for safe path operations

### 🧠 Usage Example

```php
use Maatify\Bootstrap\Helpers\EnvHelper;
use Maatify\Bootstrap\Helpers\PathHelper;

$timezone = EnvHelper::get('APP_TIMEZONE', 'Africa/Cairo');
$basePath = PathHelper::base();
```

### ✅ Verification

* `EnvHelper` uses cache with runtime override support
* `PathHelper` resolves consistent directories in CI and local

---

## 🧱 Phase 4 — Integration Layer

### 🎯 Goal

Ensure compatibility across all Maatify libraries.

### ⚙️ Implemented Features

* Confirmed shared environment initialization
* Verified that environment loads once per runtime
* Added CI integration test for multi-library boot order

### 🧠 Example

```php
// In maatify/data-adapters
\Maatify\Bootstrap\Core\Bootstrap::init();
```

### ✅ Verification

* Integration tests across libraries successful
* No reinitialization or conflicts detected

---

## 🧱 Phase 5 — Diagnostics & Safe Mode

### 🎯 Goal

Add runtime diagnostics and safe initialization fallbacks for production environments.

### ⚙️ Implemented Features

* `BootstrapDiagnostics` with `checkEnv()`, `checkTimezone()`, `checkErrors()`, `isSafeMode()`
* Safe Mode auto-enables if `.env.local` or `.env.testing` exists in production
* `.env.example` used as fallback
* PSR-3 logging for audit trails

### 🧠 Usage Example

```php
use Maatify\Bootstrap\Core\BootstrapDiagnostics;
use Maatify\PsrLogger\LoggerFactory;

$logger = LoggerFactory::create('bootstrap');
$diag = new BootstrapDiagnostics($logger);

$results = $diag->run();
print_r($results);

$diag->activateSafeMode();
```

### ✅ Testing

```bash
composer run-script test
```

Expected output:

```
Maatify Bootstrap Test Suite
 ✔ Init is idempotent
 ✔ Diagnostics return expected structure
 ✔ Safe mode detection
 ✔ Env loading priority
 ✔ Env helper returns expected value
 ✔ Path helper builds consistent paths
 ✔ Integration across libraries
```

---

## 🧩 Environment Loading Priority — Full Explanation

### 🔍 Load Order

```php
$envFiles = ['.env.local', '.env.testing', '.env', '.env.example'];
```

The loader checks in this strict order and **stops immediately** after finding the first existing file.
Only one `.env*` file is ever loaded per execution.

### 🧠 Behavior per Environment

| Environment       | Files Present             | Loaded File                        | Reason                                   |
|-------------------|---------------------------|------------------------------------|------------------------------------------|
| Local Development | `.env.local`              | ✅ `.env.local`                     | Highest priority for developer overrides |
| Testing / CI      | `.env.testing` or none    | ✅ `.env.testing` or `.env.example` | Prevents CI from using production data   |
| Production        | `.env` and `.env.example` | ✅ `.env`                           | Official production environment          |
| Fresh Install     | only `.env.example`       | ✅ `.env.example`                   | Fallback for first-time setup            |

### ⚙️ Why This Order

| Priority | File           | Purpose              | Safe to Commit? |
|----------|----------------|----------------------|-----------------|
| 🥇 1     | `.env.local`   | Developer overrides  | ❌ Private       |
| 🥈 2     | `.env.testing` | CI / PHPUnit configs | ✅               |
| 🥉 3     | `.env`         | Production config    | ✅               |
| 🏁 4     | `.env.example` | Template fallback    | ✅               |

> `Dotenv::createImmutable()` prevents overwriting any existing variables.
> Even if `.env.example` is present in production, it **cannot override** `.env`.

---

## 🧪 Phase 6 — CI/CD & Docker Integration

### 🚀 Overview

Adds automated testing and container validation to guarantee consistent builds.

### ⚙️ GitHub Actions Workflow

Location: `.github/workflows/tests.yml`

#### Stages

1. **Setup** → PHP 8.4 + Composer install
2. **Test** → Run PHPUnit with `CI=true`
3. **Docs** → Validate `README.full.md` & `CHANGELOG.md`
4. **Docker** → Build test container for reproducibility

Triggered on each push or pull-request to `main`, `master`, or `develop`.

### 🐳 Docker Integration

Files:

```
docker/Dockerfile
docker/docker-compose.yml
```

#### Build & Run

```bash
docker compose up --build
```

Re-run tests inside the container:

```bash
docker compose exec bootstrap composer run-script test
```

### 🧩 Environment Rules Recap

| Priority       | File               | Context |
|----------------|--------------------|---------|
| `.env.local`   | Developer override |         |
| `.env.testing` | CI/testing         |         |
| `.env`         | Production/staging |         |
| `.env.example` | Fallback           |         |

* CI uses `.env.testing` with `CI=true`.
* Developers use `.env.local`.
* Production uses `.env`.
* `.env.example` guarantees boot even if others are missing.

---

## 🧰 Project Structure

```
maatify/bootstrap/
├── src/Core/
│   ├── Bootstrap.php
│   ├── BootstrapDiagnostics.php
│   └── EnvironmentLoader.php
├── tests/
│   ├── BootstrapTest.php
│   ├── EnvironmentLoaderTest.php
│   ├── DiagnosticsTest.php
│   ├── HelpersTest.php
│   └── IntegrationTest.php
├── docker/
│   ├── Dockerfile
│   └── docker-compose.yml
├── .github/workflows/tests.yml
└── composer.json
```

---

## 🧩 Phase 7 — Release & Documentation Merge (Planned)

**Goal:**
Finalize v1.0.0 public release with:

* CI badges (build + PHP version + Packagist)
* Automated version tagging
* Unified documentation (`README.full.md`)
* Publication to [Packagist](https://packagist.org/packages/maatify/bootstrap)

### 🧾 Release Steps

```bash
git tag -a v1.0.0 -m "Initial release — Maatify Bootstrap"
git push origin v1.0.0
```

Composer metadata:

```json
{
  "name": "maatify/bootstrap",
  "type": "library",
  "license": "MIT"
}
```

---



## 🧱 Phase 7 — Release & Documentation Merge

### 🎯 Goal
Finalize the **Maatify Bootstrap** library for public release, merge all technical documentation, and ensure full CI/CD and Packagist readiness.

### ⚙️ Implemented Tasks
| Task                   | Description                                          | Status |
|------------------------|------------------------------------------------------|--------|
| Public README          | Concise, badge-ready overview for GitHub & Packagist | ✅      |
| `README.full.md` merge | Combined all phases into one reference               | ✅      |
| CI workflow            | GitHub Actions for PHP 8.4 + Docker                  | ✅      |
| CHANGELOG              | Track release versions                               | ✅      |
| Composer metadata      | Name, description, keywords, authors                 | ✅      |
| Packagist prep         | Optimized summary + keywords                         | ✅      |
| Release tag            | `v1.0.0` stable                                      | ✅      |

### 🧾 Composer Metadata

```json
{
  "name": "maatify/bootstrap",
  "description": "Unified environment initialization and diagnostics foundation for all Maatify PHP projects. Provides predictable .env loading, timezone configuration, and Safe Mode protection across local, CI, and production environments — forming the core bootstrap layer for the Maatify ecosystem.",
  "keywords": [
    "maatify", "bootstrap", "environment", "dotenv", "safe mode",
    "timezone", "diagnostics", "startup", "configuration", "phpunit",
    "ci", "docker", "psr", "framework", "ecosystem", "maatify.dev"
  ],
  "license": "MIT",
  "authors": [
    { "name": "Mohamed Abdulalim", "email": "mohamed@maatify.dev" }
  ],
  "autoload": { "psr-4": { "Maatify\\Bootstrap\\": "src/" } },
  "require": {
    "php": ">=8.2",
    "vlucas/phpdotenv": "^5.6",
    "psr/log": "^3.0"
  },
  "require-dev": { "phpunit/phpunit": "^10.5" },
  "scripts": { "test": "vendor/bin/phpunit --testdox" }
}
```

## 🧠 Summary Matrix

| Aspect              | Status | Notes                                                                            |
|---------------------|--------|----------------------------------------------------------------------------------|
| Environment Loading | ✅      | Deterministic, priority-based across `.env` files                                |
| Timezone Config     | ✅      | Defaults to `Africa/Cairo` if undefined                                          |
| Safe Mode           | ✅      | Automatically activates in production when `.env.local` or `.env.testing` exists |
| Logging Integration | ✅      | Fully PSR-3 compatible, integrates with maatify/psr-logger                       |
| PHPUnit Coverage    | ✅      | >95% coverage with deterministic test results                                    |
| CI/CD Pipeline      | ✅      | Automated via GitHub Actions and Docker parity                                   |
| Docker Support      | ✅      | Consistent builds for local, CI, and production                                  |
| Documentation Merge | ✅      | Includes all phases and merged `README.full.md`                                  |
| Release Tag         | ✅      | Stable release `v1.0.0` published                                                |


---

## 🏁 Conclusion

**Maatify Bootstrap** provides a reliable, modular, and automated foundation for all Maatify projects.
It ensures predictable initialization, stable testing, and secure deployment workflows across development, CI, and production.

---

**© 2025 Maatify.dev — All Rights Reserved**
**Project:** `maatify:bootstrap`
**Website:** [https://www.maatify.dev](https://www.maatify.dev)
