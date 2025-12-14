# 🧱 Phase 5 — Diagnostics & Safe Mode

## 🎯 Goal
Introduce runtime diagnostics and safe-initialization mechanisms for production environments.
This phase adds `BootstrapDiagnostics` to verify environment integrity, timezone configuration, error-handler readiness, and to enforce **Safe Mode** when potentially unsafe environment files are detected.

---

## ✅ Implemented Tasks
- [x] Added `BootstrapDiagnostics` class
- [x] Implemented `checkEnv()`, `checkTimezone()`, `checkErrors()` and `isSafeMode()` methods
- [x] Integrated Safe Mode activation (`activateSafeMode()`)
- [x] Logged diagnostics via PSR-3 if logger available
- [x] Added unit tests (`DiagnosticsTest`) with environment isolation
- [x] Enhanced `EnvironmentLoader` with `.env.example` fallback
- [x] Documented environment-file priority and safety rules

---

## ⚙️ Files Created / Updated
- `src/Core/BootstrapDiagnostics.php`
- `tests/DiagnosticsTest.php`
- `src/Core/EnvironmentLoader.php` (updated for fallback support)
- `docs/phases/README.phase5.md` (this file)

---

## 🧠 Usage Example
```php
use Maatify\Bootstrap\Core\BootstrapDiagnostics;
use Maatify\PsrLogger\LoggerFactory;

$logger = LoggerFactory::create('bootstrap');
$diag = new BootstrapDiagnostics($logger);

$results = $diag->run();
print_r($results);

// Optionally enforce Safe Mode
$diag->activateSafeMode();
````

---

## 🧪 Testing & Verification

Run full test suite:

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

### Verified Scenarios

| Scenario                           | Result                          |
|------------------------------------|---------------------------------|
| APP_ENV=production with .env.local | Safe Mode enabled ✅             |
| CI=true                            | Safe Mode disabled ✅            |
| Missing env files                  | Loads `.env.example` fallback ✅ |
| Timezone absent                    | Defaults to `Africa/Cairo` ✅    |

---

## 🧩 Environment Loading Priority (Full Explanation)

Your loader checks environment files in this strict order:

```php
$envFiles = ['.env.local', '.env.testing', '.env', '.env.example'];
```

It stops after loading the first file found — the `break;` statement ensures only one environment file is active per execution.

### 🔍 Behavior per Environment

| Environment                    | Files Present          | Loaded File                        | Reason                                   |
|--------------------------------|------------------------|------------------------------------|------------------------------------------|
| Local Development              | `.env.local`           | ✅ `.env.local`                     | Highest priority for developer overrides |
| Testing / CI                   | `.env.testing` or none | ✅ `.env.testing` or `.env.example` | Ensures isolation and predictability     |
| Production                     | `.env`, `.env.example` | ✅ `.env`                           | Stops on official production file        |
| Fresh Install / Minimal System | only `.env.example`    | ✅ `.env.example`                   | Fallback prevents exception              |

### 🧠 Why This Order Matters

| Priority | File           | Purpose                   | Safe to Commit ? |
|----------|----------------|---------------------------|------------------|
| 🥇 1     | `.env.local`   | Local developer overrides | ❌ (private)      |
| 🥈 2     | `.env.testing` | CI / PHPUnit config       | ✅                |
| 🥉 3     | `.env`         | Default production config | ✅                |
| 🏁 4     | `.env.example` | Fallback template         | ✅                |

**Rationale:**

* `.env.local` comes first so developers can safely override settings without affecting production.
* `.env.testing` is second to protect automated tests from touching real data.
* `.env` is third as the canonical production configuration.
* `.env.example` is last for CI and bootstrap fallbacks.

**Immutable Load Mode (`Dotenv::createImmutable`)**

> Prevents later files or system variables from overwriting existing values.
> Even if `.env.example` exists in production, it cannot override `.env`.

---

## 🧾 Summary

Phase 5 completes the foundation for runtime validation and secure startup behavior.
Safe Mode logic and diagnostics are now production-ready, providing automatic environment auditing for the entire Maatify ecosystem.


---

**© 2025 Maatify.dev — All rights reserved.**
