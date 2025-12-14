# 🧱 Phase 2 — Core Interfaces & Base Structure

### 🎯 Goal
Define shared interfaces, base classes, exceptions, and resolver logic for adapters.

---

### ✅ Implemented Tasks
- Created `AdapterInterface`
- Added `BaseAdapter` abstract class
- Added `ConnectionException`, `FallbackException`
- Implemented `EnvironmentConfig` loader
- Implemented `DatabaseResolver`
- Added environment auto-detection for Redis/Mongo/MySQL

---

### ⚙️ Files Created
````

src/Contracts/AdapterInterface.php
src/Core/BaseAdapter.php
src/Core/Exceptions/ConnectionException.php
src/Core/Exceptions/FallbackException.php
src/Core/EnvironmentConfig.php
src/Core/DatabaseResolver.php
tests/Core/CoreStructureTest.php

````

---

### 🧠 Usage Example
```php
$config = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($config);
$adapter = $resolver->resolve('redis');
$adapter->connect();
````

---

### 🧩 Verification Notes

✅ Namespace autoload checked
✅ BaseAdapter instantiated successfully
✅ EnvironmentConfig loaded `.env` values

---

### 📘 Result

* `/docs/phases/README.phase2.md` created
* `README.md` updated (Phase 2 completed)

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
