# 🧾 **CHANGELOG — Maatify Bootstrap**

> **Project:** `maatify/bootstrap`
> **Maintainer:** Mohamed Abdulalim (megyptm)
> **Organization:** Maatify.dev
> **License:** MIT

---

## [1.0.4] - 2025-11-14
### Changed
- Removed legacy environment variable restoration logic from `EnvironmentLoader`.
    - Deleted the immutability block that re-applied original `$_ENV` values after loading.
    - Simplified the loader flow to avoid unintended overrides and ensure consistent variable precedence.

### Improved
- Environment loading now behaves predictably and respects the current process environment without forced rollback.
- Codebase cleanup for clarity and reduced side-effects.

---

## [1.0.3] — 2025-11-13
Release 1.0.3 (fix missing VERSION update)

---

## [1.0.2] — 2025-11-13

### 🔒 **Stable Environment Loader (No Override Mode)**

A major improvement ensuring complete safety and determinism in environment loading.

#### ✨ Added

* **Pre-load snapshot system**
  Captures all `$_ENV` + `putenv()` variables before loading `.env`, ensuring no value is lost.

* **Post-load variable restoration**
  Re-assigns all pre-existing variables, preventing `.env` files from overriding runtime, CI, or PHPUnit test variables.

* **Full isolation for test environments**
  Guarantees that any env variables injected by PHPUnit (`putenv`, `$_ENV`) remain untouched.

#### 🛠 Improved

* Environment handling is now **predictable, deterministic, and override-proof**.
* Perfect consistency across Maatify libraries (`data-adapters`, `rate-limiter`, etc.).
* Strengthened compatibility with CI/CD pipelines and Dockerized environments.

#### 🧪 Testing

* Updated integration tests confirm that `.env` loading **never overrides** runtime or test variables.
* Verified support for parallel test runners and isolated env contexts.

---

## [1.0.1] — 2025-11-12

### 📦 Dependency Update

* Updated requirement: `"maatify/common": "^1.0"`
* Internal helpers refactored for compatibility with latest `maatify/common`.

*No functional or breaking changes in this release.*

---

## [1.0.0] — 2025-11-09

### 🧱 **Initial Stable Release**

The foundational version that introduced the full bootstrap system:

#### 🚀 Features

* `Bootstrap::init()` unified entry point
* Smart `.env` loader with priority:
  `.env.local` → `.env.testing` → `.env` → `.env.example`
* Immutable Dotenv mode
* Automatic timezone setup
* Diagnostics + Safe Mode
* Helpers (EnvHelper, PathHelper)
* Full CI & Docker integration
* Complete documentation & PHPUnit coverage

---

# 📌 Summary of Recent Changes

| Version   | Purpose                              | Stability |
|-----------|--------------------------------------|-----------|
| **1.0.2** | No override env loader + test safety | 🟢 Stable |
| **1.0.1** | Update dependencies                  | 🟢 Stable |
| **1.0.0** | Initial public release               | 🟢 Stable |

---

## [1.0.0] — 2025-11-09
### 🧱 Phase 1 — Foundation Setup
- Initialized project structure and Composer package.
- Implemented PSR-4 autoloading for `Maatify\Bootstrap\`.
- Added `.env.example` and base PHPUnit configuration.
- Introduced `EnvironmentLoader` with timezone fallback to `Africa/Cairo`.

### ⚙️ Phase 2 — Bootstrap Core
- Added main `Bootstrap::init()` entry point.
- Integrated environment loader and error handler.
- Ensured idempotent initialization and runtime safety.

### 🧩 Phase 3 — Helpers & Utilities
- Added `EnvHelper` (cached environment variable access).
- Added `PathHelper` (consistent path resolution).
- Integrated with `maatify/common` utilities.

### 🔗 Phase 4 — Integration Layer
- Verified multi-library boot order with `maatify/data-adapters`, `maatify/rate-limiter`, and `maatify/security-guard`.
- Ensured environment variables load only once per runtime.
- Added CI integration tests for shared initialization.

### 🧠 Phase 5 — Diagnostics & Safe Mode
- Implemented `BootstrapDiagnostics` class with environment, timezone, and error-handler validation.
- Added Safe Mode detection when `.env.local` or `.env.testing` exist under production environment.
- Integrated PSR-3 logging for diagnostic reporting.
- Updated `EnvironmentLoader` to include `.env.example` as fallback.
- Added complete environment-file priority documentation.
- All PHPUnit tests passing across environments.

---

## 🌐 Upcoming — Phase 6: Advanced Integration & Release
- Add GitHub Actions workflow for automated CI/CD.
- Add Dockerfile + docker-compose for local bootstrap testing.
- Auto-generate and validate documentation during CI.
- Tag release **v1.0.0** and publish to Packagist.
