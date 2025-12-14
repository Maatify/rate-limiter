# 🧱 Phase 2 — Bootstrap Core

## 🎯 Goal
Implement the main **Bootstrap entry point** and integrate environment loading, time zone setup, and PSR-3-compatible error handling.
This phase establishes the unified initialization layer for all Maatify libraries, ensuring predictable startup behavior and consistent system configuration.

---

## ✅ Implemented Tasks
- [x] Created `Bootstrap` class with static `init()` entry point.
- [x] Integrated `EnvironmentLoader` for environment setup.
- [x] Implemented `ErrorHandler` class with PSR-3 logging hook.
- [x] Added default timezone configuration (`APP_TIMEZONE` → fallback `Africa/Cairo`).
- [x] Ensured idempotency: `Bootstrap::init()` runs only once.
- [x] Added `BootstrapTest` unit tests to confirm behavior.
- [x] Updated documentation and root README.

---

## ⚙️ Files Created
| File                        | Description                                                    |
|-----------------------------|----------------------------------------------------------------|
| `src/Core/Bootstrap.php`    | Main entry point handling initialization and environment setup |
| `src/Core/ErrorHandler.php` | Handles PHP errors and exceptions using PSR-3 logger           |
| `tests/BootstrapTest.php`   | Confirms initialization idempotency and handler registration   |
| `README.md`                 | Updated to reflect Phase 2 completion                          |

---

## 🧠 Usage Example
```php
use Maatify\Bootstrap\Core\Bootstrap;

require_once __DIR__ . '/vendor/autoload.php';

Bootstrap::init(__DIR__);

// Optionally access logger
$logger = Bootstrap::logger();
$logger?->info('Bootstrap initialized successfully.');
````

---

## 🧪 Testing & Verification

### ✅ Run Tests

```bash
vendor/bin/phpunit --testdox
```

### ✅ Expected Output

```
Maatify Bootstrap Test Suite
 ✔ Env loading priority
 ✔ Init is idempotent
```

### 🧩 Manual Verification

| Check                           | Expected Result                         |
|---------------------------------|-----------------------------------------|
| Double `Bootstrap::init()` call | No re-initialization or exception       |
| `ErrorHandler` registered       | PHP errors logged via PSR-3             |
| `APP_TIMEZONE` applied          | Matches system timezone                 |
| `APP_ENV` loaded correctly      | Matches .env.local or .env.testing file |
| Logger available                | Instance of `Psr\Log\LoggerInterface`   |

---

## 📘 Notes

* The **Bootstrap** class now provides a consistent initialization mechanism across all Maatify projects.
* Logging integration uses `maatify/psr-logger`, enabling error tracking and observability.
* `ErrorHandler` ensures unhandled exceptions and PHP warnings are captured cleanly.
* Initialization remains **idempotent**, making it safe to call `Bootstrap::init()` multiple times in large dependency graphs.
* Environment variables are loaded once and cached for the entire runtime.

---

## 🏁 Phase Status

✅ **Completed** — Core bootstrap logic, error handling, and initialization fully implemented and verified.


---

**© 2025 Maatify.dev — All rights reserved.**
