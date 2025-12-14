# 🧱 Phase 4 — Integration Layer

## 🎯 Goal
Integrate the **Bootstrap** initialization process with other Maatify libraries and ensure consistent environment loading, error handling, and timezone configuration when multiple packages are used together.
This phase validates interoperability across the Maatify ecosystem and prepares the library for shared CI/CD pipelines.

---

## ✅ Implemented Tasks
- [x] Implemented `IntegrationManager` class for coordinated cross-library startup.
- [x] Added `IntegrationValidator` for runtime environment, timezone, and handler checks.
- [x] Verified idempotent initialization when multiple libraries register simultaneously.
- [x] Added PHPUnit tests for multi-library integration (`IntegrationTest`).
- [x] Updated documentation and root `README.md`.

---

## ⚙️ Files Created
| File                                | Description                                                              |
|-------------------------------------|--------------------------------------------------------------------------|
| `src/Core/IntegrationManager.php`   | Manages registration of libraries that rely on Bootstrap                 |
| `src/Core/IntegrationValidator.php` | Validates environment, timezone, and error handler consistency           |
| `tests/IntegrationTest.php`         | Confirms that all dependent libraries initialize correctly and only once |

---

## 🧠 Usage Example
```php
use Maatify\Bootstrap\Core\IntegrationManager;
use Maatify\Bootstrap\Core\IntegrationValidator;

// Register other maatify packages
IntegrationManager::register('maatify/data-adapters', __DIR__);
IntegrationManager::register('maatify/rate-limiter', __DIR__);
IntegrationManager::register('maatify/security-guard', __DIR__);

// Check diagnostics
print_r(IntegrationValidator::diagnostics());
````

Expected diagnostic output:

```php
Array
(
    [env_loaded] => true
    [timezone] => Africa/Cairo
    [handlers_ok] => true
    [registered_libs] => Array
        (
            [0] => maatify/data-adapters
            [1] => maatify/rate-limiter
            [2] => maatify/security-guard
        )
)
```

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
 ✔ Env helper returns expected value
 ✔ Path helper builds consistent paths
 ✔ Integration across libraries
```

### 🧩 Manual Verification

| Check                  | Expected Result                                          |
|------------------------|----------------------------------------------------------|
| Duplicate registration | No re-initialization or error                            |
| Env variables          | Available across all libraries                           |
| Timezone consistency   | Matches `.env` configuration                             |
| ErrorHandler           | Shared across libraries                                  |
| Registered libs        | Returned correctly by `IntegrationManager::registered()` |

---

## 📘 Notes

* This layer ensures all Maatify components share a single, stable bootstrap context.
* Prevents double initialization in large dependency trees.
* Suitable for CI pipelines that load multiple maatify packages in a single test runner.
* Diagnostic reporting uses lightweight array output for cross-library observability.
* Lays groundwork for the upcoming **Phase 5 — Diagnostics & Safe Mode**.

---

## 🏁 Phase Status

✅ **Completed** — Integration layer implemented, tested, and verified across all dependent Maatify libraries.


---

**© 2025 Maatify.dev — All rights reserved.**
