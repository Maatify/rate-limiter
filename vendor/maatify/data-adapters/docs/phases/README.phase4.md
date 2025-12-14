# 🧱 Phase 4 — Health & Diagnostics Layer

### 🎯 Goal
Implement adapter self-checking, diagnostics service, and runtime fallback tracking with unified JSON output compatible with maatify/admin-dashboard.

---

### ✅ Implemented Tasks
- Enhanced `healthCheck()` across all adapters (Redis, Predis, MongoDB, MySQL).
- Added `DiagnosticService` for unified status reporting in JSON format.
- Added `AdapterFailoverLog` to record fallback or connection failures.
- Added internal `/health` endpoint returning system status JSON.
- Integrated automatic Enum (`AdapterTypeEnum`) compatibility within the Diagnostic layer.
- Documented diagnostic flow and usage examples.

---

### ⚙️ Files Created
```

src/Diagnostics/DiagnosticService.php
src/Diagnostics/AdapterFailoverLog.php
tests/Diagnostics/DiagnosticServiceTest.php

````

---

### 🧩 DiagnosticService Overview

#### Purpose:
Collect adapter health statuses dynamically and return them in JSON format for monitoring dashboards or CI integrations.

#### Key Features:
- Registers multiple adapters (`redis`, `mongo`, `mysql`)
- Supports both **string** and **AdapterTypeEnum** registration
- Auto-handles connection errors and logs them
- Produces lightweight JSON diagnostics
- Uses `AdapterFailoverLog` for fallback event tracking

---

### 🧠 Example Usage

```php
use Maatify\DataAdapters\Core\EnvironmentConfig;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Diagnostics\DiagnosticService;
use Maatify\DataAdapters\Enums\AdapterTypeEnum;

$config   = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($config);
$service  = new DiagnosticService($config, $resolver);

$service->register([
    AdapterTypeEnum::REDIS,
    AdapterTypeEnum::MONGO,
    AdapterTypeEnum::MYSQL
]);

echo $service->toJson();
````

---

### 📤 Example Output

```json
{
  "diagnostics": [
    { "adapter": "redis", "connected": true, "error": null, "timestamp": "2025-11-08 21:15:00" },
    { "adapter": "mongo", "connected": true, "error": null, "timestamp": "2025-11-08 21:15:00" },
    { "adapter": "mysql", "connected": true, "error": null, "timestamp": "2025-11-08 21:15:00" }
  ]
}
```

---

### 🧾 AdapterFailoverLog Example

When a connection fails or fallback occurs:

```
[2025-11-08 21:17:32] [REDIS] Connection refused (fallback to Predis)
[2025-11-08 21:17:34] [MYSQL] Access denied for user 'root'
```

Stored automatically in:

```
storage/failover.log
```

---

### 🧩 Enum Integration Fix

To ensure full compatibility with the new `AdapterTypeEnum`,
the `DiagnosticService::register()` method now supports both string and Enum types:

```php
$enum = $type instanceof AdapterTypeEnum
    ? $type
    : AdapterTypeEnum::from(strtolower((string)$type));
$this->adapters[$enum->value] = $this->resolver->resolve($enum);
```

✅ Prevents `TypeError` when passing string values like `'redis'`.

---

### 🧪 Tests Summary

| Test                    | Purpose                                                        |
|:------------------------|:---------------------------------------------------------------|
| `DiagnosticServiceTest` | Verifies that diagnostics return an array with valid structure |
| `CoreStructureTest`     | Ensures configuration and resolver work for health layer       |
| `RedisAdapterTest`      | Confirms Redis connection and fallback logic still functional  |

✅ PHPUnit Result:

```
OK (7 tests, 12 assertions)
```

---

### 📘 Result

* `/docs/phases/README.phase4.md` created
* Root `README.md` updated between markers:

```markdown
## ✅ Completed Phases
<!-- PHASE_STATUS_START -->
- [x] Phase 1 — Environment Setup
- [x] Phase 2 — Core Interfaces & Base Structure
- [x] Phase 3 — Adapter Implementations
- [x] Phase 3.5 — Adapter Smoke Tests Extension
- [x] Phase 4 — Health & Diagnostics Layer
<!-- PHASE_STATUS_END -->
```

---

### 📊 Phase Summary Table

| Phase |   Status    | Files Created |
|:------|:-----------:|:-------------:|
| 1     | ✅ Completed |       7       |
| 2     | ✅ Completed |       7       |
| 3     | ✅ Completed |      10       |
| 3.5   | ✅ Completed |       3       |
| 4     | ✅ Completed |       3       |

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
