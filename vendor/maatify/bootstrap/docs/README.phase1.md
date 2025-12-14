# 🧱 Phase 1 — Foundation Setup

## 🎯 Goal
Initialize the core bootstrap structure, namespaces, and environment-loader foundation.
This phase ensures every Maatify library can load environment variables in a consistent order
(local → testing → production) while maintaining PSR-12 compliance and minimal dependencies.

---

## ✅ Implemented Tasks
- [x] Composer initialization with maatify/common dependency
- [x] Added PSR-4 autoload for `Maatify\Bootstrap\` namespace
- [x] Implemented `EnvironmentLoader` class with priority detection (`.env.local` → `.env.testing` → `.env`)
- [x] Added `.env.example` for reference variables
- [x] Added PHPUnit configuration and environment-loading tests
- [x] Documented phase summary and usage example

---

## ⚙️ Files Created
| File                              | Description                                                                     |
|-----------------------------------|---------------------------------------------------------------------------------|
| `src/Core/EnvironmentLoader.php`  | Handles environment variable loading and timezone initialization                |
| `tests/EnvironmentLoaderTest.php` | Verifies correct load order and environment variable access                     |
| `.env.example`                    | Provides example environment variables (`APP_ENV`, `APP_TIMEZONE`, `APP_DEBUG`) |
| `composer.json`                   | Defines package name, dependencies, and autoload configuration                  |
| `phpunit.xml`                     | Configures PHPUnit test suite                                                   |
| `README.md`                       | Root documentation with phase progress markers                                  |

---

## 🧠 Usage Example
```php
use Maatify\Bootstrap\Core\EnvironmentLoader;

require_once __DIR__ . '/vendor/autoload.php';

$env = new EnvironmentLoader(__DIR__);
$env->load();

echo getenv('APP_ENV');        // Outputs: local
echo date_default_timezone_get(); // Outputs: Africa/Cairo (default)
````

---

## 🧪 Testing & Verification

### ✅ Run Tests

Execute the following command from the project root:

```bash
vendor/bin/phpunit --testdox
```

### ✅ Expected Output

```
Maatify Bootstrap Test Suite
 ✔ Environment loading priority
```

### 🧩 Manual Verification

| Check                | Expected Result                               |
|----------------------|-----------------------------------------------|
| `.env.local` present | Takes priority over `.env.testing` and `.env` |
| `APP_TIMEZONE` value | System timezone matches `.env` configuration  |
| No .env file found   | Exception is thrown with clear message        |
| PHPUnit result       | All tests pass successfully                   |

### ⚙️ Environment Priority

1. `.env.local` — local development overrides
2. `.env.testing` — used for automated tests
3. `.env` — default production fallback

---

## 📘 Notes

* The loader uses **vlucas/phpdotenv** to manage `.env` files safely.
* Timezone defaults to **Africa/Cairo** when not set explicitly.
* All classes follow Maatify conventions: strict typing, PSR-12 formatting, and standard header blocks.
* This foundation ensures environment isolation across all Maatify projects (`data-adapters`, `rate-limiter`, `security-guard`, etc.).

---

## 🏁 Phase Status

✅ Completed — Project successfully bootstrapped and ready for Phase 2.


---

**© 2025 Maatify.dev — All rights reserved.**
