# 🧱 Phase 3 — Helpers & Utilities

## 🎯 Goal
Add unified utility helpers for environment and path operations, ensuring consistent behavior across the Maatify ecosystem.
These helpers are foundational for all future libraries and make the bootstrap package self-sufficient for standalone or integrated use.

---

## ✅ Implemented Tasks
- [x] Created `PathHelper` for normalized and project-relative paths.
- [x] Added `EnvHelper` for safe environment-variable retrieval and caching.
- [x] Integrated fallback logic compatible with immutable and mutable Dotenv modes.
- [x] Added PHPUnit tests for both helpers.
- [x] Updated documentation and phase tracker in the root README.

---

## ⚙️ Files Created
| File                         | Description                                                               |
|------------------------------|---------------------------------------------------------------------------|
| `src/Helpers/PathHelper.php` | Generates safe and consistent directory paths (base, storage, logs, etc.) |
| `src/Helpers/EnvHelper.php`  | Provides cached and safe access to environment variables                  |
| `tests/HelpersTest.php`      | Tests EnvHelper and PathHelper for correct behavior                       |

---

## 🧠 Usage Examples
```php
use Maatify\Bootstrap\Helpers\EnvHelper;
use Maatify\Bootstrap\Helpers\PathHelper;

// ✅ Get environment variable safely
$timezone = EnvHelper::get('APP_TIMEZONE', 'Africa/Cairo');

// ✅ Check if variable exists
if (EnvHelper::has('APP_ENV')) {
    echo 'Environment: ' . EnvHelper::get('APP_ENV');
}

// ✅ Retrieve cached environment data
print_r(EnvHelper::cached());

// ✅ Build consistent paths
echo PathHelper::base();           // /var/www/maatify-bootstrap
echo PathHelper::storage('cache'); // /var/www/maatify-bootstrap/storage/cache
echo PathHelper::logs();           // /var/www/maatify-bootstrap/storage/logs
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
 ✔ EnvHelper returns expected value
 ✔ PathHelper builds consistent paths
```

### 🧩 Manual Verification

| Check                               | Expected Result                             |
|-------------------------------------|---------------------------------------------|
| EnvHelper::get() returns .env value | Matches loaded .env file                    |
| EnvHelper::cached() stores values   | Returns previously fetched variables        |
| PathHelper::base()                  | Points to project root                      |
| PathHelper::logs()                  | Returns valid logs directory path           |
| PSR-12 compliance                   | All helpers follow Maatify coding standards |

---

## 📘 Notes

* `EnvHelper` abstracts over `$_ENV`, `$_SERVER`, and `getenv()` sources.
* Cached access improves performance and avoids redundant lookups.
* `PathHelper` ensures cross-platform safety using `realpath()` normalization.
* These utilities reduce dependency on external packages for routine environment and filesystem logic.
* Both helpers are pure-static, lightweight, and dependency-free.

---

## 🏁 Phase Status

✅ **Completed** — Helper utilities implemented, tested, and fully documented.

---

**© 2025 Maatify.dev — All rights reserved.**
