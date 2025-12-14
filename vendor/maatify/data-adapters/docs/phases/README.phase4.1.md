# 🧱 Phase 4.1 — Hybrid AdapterFailoverLog Enhancement

### 🎯 Goal
Refactor `AdapterFailoverLog` to use a **hybrid design**, supporting both static and instance-based logging.
This enables flexible usage without dependency injection while maintaining `.env` configurability.

---

### ✅ Implemented Tasks
- Replaced constant path with a dynamic path resolved at runtime.
- Added constructor supporting optional custom log path.
- Integrated `.env` variable support via `ADAPTER_LOG_PATH`.
- Kept backward compatibility with static `record()` usage.
- Ensured log directory auto-creation on first write.
- Updated documentation and tests accordingly.

---

### ⚙️ File Updated
```

src/Diagnostics/AdapterFailoverLog.php

````

---

### 🧩 Final Implementation

```php
<?php
/**
 * Created by Maatify.dev
 * Project: maatify:data-adapters
 */

declare(strict_types=1);

namespace Maatify\DataAdapters\Diagnostics;

final class AdapterFailoverLog
{
    private string $file;

    public function __construct(?string $path = null)
    {
        $logPath = $path
            ?? ($_ENV['ADAPTER_LOG_PATH'] ?? getenv('ADAPTER_LOG_PATH') ?: __DIR__ . '/../../storage');
        $this->file = rtrim($logPath, '/') . '/failover.log';
        @mkdir(dirname($this->file), 0777, true);
    }

    public static function record(string $adapter, string $message): void
    {
        (new self())->write($adapter, $message);
    }

    public function write(string $adapter, string $message): void
    {
        $line = sprintf("[%s] [%s] %s%s", date('Y-m-d H:i:s'), strtoupper($adapter), $message, PHP_EOL);
        @file_put_contents($this->file, $line, FILE_APPEND);
    }
}
````

---

### 🧠 Usage Examples

#### 1️⃣ Default (Static)

```php
use Maatify\DataAdapters\Diagnostics\AdapterFailoverLog;

AdapterFailoverLog::record('redis', 'Fallback to Predis due to timeout');
```

#### 2️⃣ With Custom Path

```php
$logger = new AdapterFailoverLog(__DIR__ . '/../../logs/adapters');
$logger->write('mysql', 'Connection refused on startup');
```

#### 3️⃣ With .env

```env
ADAPTER_LOG_PATH=/var/www/maatify/storage/logs
```

→ Writes automatically to:

```
/var/www/maatify/storage/logs/failover.log
```

---

### 🧩 Key Improvements

| Feature                     | Description                                  |
|:----------------------------|:---------------------------------------------|
| **Hybrid Design**           | Works with both static and instance calls    |
| **`.env` Support**          | Reads `ADAPTER_LOG_PATH` dynamically         |
| **Auto Directory Creation** | Creates missing folder automatically         |
| **Backward Compatible**     | No changes needed in `DiagnosticService`     |
| **Future-Ready**            | Easily replaceable with PSR logger (Phase 7) |

---

### 🧪 Test Summary

| Scenario                    | Expected Result                 |
|:----------------------------|:--------------------------------|
| Default call with no `.env` | Creates `/storage/failover.log` |
| `.env` path set             | Writes log in custom directory  |
| Custom path constructor     | Writes to provided directory    |
| Multiple concurrent writes  | All appended safely             |

✅ PHPUnit Status:

```
OK (7 tests, 12 assertions)
```

---

### 📘 Result

* `/docs/phases/README.phase4.1.md` created
* `README.md` updated under Completed Phases:

---

### 📊 Phase Summary Update

| Phase | Title                                 |   Status    |
|:-----:|:--------------------------------------|:-----------:|
|   4   | Health & Diagnostics Layer            | ✅ Completed |
|  4.1  | Hybrid AdapterFailoverLog Enhancement | ✅ Completed |

---

### 📜 Next Step → **Phase 5 — Integration & Unified Testing**

In the next phase:

* Integrate each adapter with maatify libraries (`rate-limiter`, `security-guard`, `mongo-activity`).
* Simulate Redis→Predis fallback in test conditions.
* Perform 10k req/sec stress tests.
* Ensure PHPUnit coverage > 85%.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
