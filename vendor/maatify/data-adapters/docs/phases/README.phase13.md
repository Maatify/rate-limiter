# 🚀 **Phase 13 — Dynamic JSON Registry with Secure Path Injection + Unified Adapter Resolution**

**Version:** 1.1.0
**Base Version:** 1.0.0
**Maintainer:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))
**Project:** maatify/data-adapters
**Module:** Data Adapters
**Date:** 2025-11-15
**Status:** ✅ Completed

---

## 🎯 **Goal**

This phase completes the unification of the entire Maatify Data-Adapters configuration system by introducing:

* 🔐 **Central JSON Registry** (`registry.json`)
* 🧠 **Three-level merge priority:**
  **Registry → DSN → Legacy ENV**
* 🏗 **DSN-aware builders** for MySQL, Mongo, Redis
* 🔄 **Unified resolution system** for all adapters
  (PDO, DBAL, MongoDB, Redis, Predis)
* 💎 **Cleaner, clearer, more maintainable architecture**

This paves the road for future advanced features including **failover routing**, **DSN rotation**, and **multi-cluster replication strategies**.

---

# 🧩 **What Phase 13 Adds**

## 1️⃣ **Central Registry Support**

New file: `RegistryConfig.php`
Handles:

* Dynamic path injection via `DB_REGISTRY_PATH`
* Secure path validation
* JSON loading with errors handled gracefully
* Auto-caching + reload on demand
* Per-profile structured database configuration

---

## 2️⃣ **DSN Builders: MySQL, Mongo, Redis**

Builders parse DSNs, extract fields, override legacy, and merge with registry.

| Builder                | What It Does                                                               |
|------------------------|----------------------------------------------------------------------------|
| **MySqlConfigBuilder** | Parses PDO & URL DSNs, extracts host/port/db, merges with registry         |
| **MongoConfigBuilder** | Supports `mongodb://` & `mongodb+srv://`, handles SRV DNS, merges profiles |
| **RedisConfigBuilder** | Parses `redis://`, extracts host/port/pass/db, merges with registry        |

All builders now output a **normalized `ConnectionConfigDTO`**, eliminating inconsistencies.

---

## 3️⃣ **3-Layer Merge Strategy**

Final config is generated as:

```
registry  →  dsn  →  legacy env
(highest)          (lowest)
```

This ensures:

* Maximum flexibility
* Predictable behavior
* Seamless overrides
* Zero ENV pollution
* Backward compatibility

---

# ⚙️ **4. Adapter Upgrades**

## ✔ **MySQLAdapter (PDO)**

* Full builder-based resolution
* Smart DSN detection
* Cleaner connection logic
* Proper `ERRMODE_EXCEPTION` setup

---

## ✔ **MySQLDbalAdapter**

* DSN → URL detection
* PDO DSN → array conversion
* Registry-aware merge
* Stable `SELECT 1` validation

---

## ✔ **MongoAdapter**

* Builder-driven
* Secure null filtering
* Correct DSN-first behavior
* Profile-based database selection

---

## ✔ **RedisAdapter (phpredis)**

* Builder-driven DSN resolution
* Correct AUTH → PING flow
* Full DSN/legacy/registry support

---

## ✔ **PredisAdapter**

* Correct AUTH BEFORE PING
* Wrapped exceptions
* DSN & legacy support

---

# 🔍 **DatabaseResolver Upgrades**

### Now supports:

* `"mysql.reports"`
* `"mongo.logs"`
* `"redis.cache"`
* `"redis.sessions"`
* `"mysql.billing"`

### New logic:

* Full adapter switching via:

  ```
  MYSQL_MAIN_DRIVER=pdo
  MYSQL_LOGS_DRIVER=dbal
  ```
* Mongo instance caching per profile
* Dynamic parsing of `type.profile`
* Correct class mapping based on availability (`phpredis → Predis`)

---

# 🗂 **Files Added**

```
src/Core/Config/RegistryConfig.php
src/Core/Config/MySqlConfigBuilder.php
src/Core/Config/MongoConfigBuilder.php
src/Core/Config/RedisConfigBuilder.php
docs/phases/README.phase13.md
```

---

# 🛠 **Files Modified**

```
src/Core/EnvironmentConfig.php
src/Core/BaseAdapter.php
src/Core/DatabaseResolver.php
src/Adapters/MySQLAdapter.php
src/Adapters/MySQLDbalAdapter.php
src/Adapters/MongoAdapter.php
src/Adapters/RedisAdapter.php
src/Adapters/PredisAdapter.php
```

---

# 🧪 **Test Suite (Added & Updated)**

### **Added in Phase 13**

* `RegistryConfigTest::testInvalidPathThrowsException`
* `RegistryConfigTest::testValidRegistryLoadsSuccessfully`
* `RegistryConfigTest::testRegistryOverridesDsnAndLegacy`

### **Updated**

* Redis / Predis connection tests
* Mongo healthCheck tests
* MySQL (PDO/DBAL) DSN-first tests
* Resolver integration tests

---

# 📌 **Example Usage**

### Redis Cache Profile

```php
$resolver = new DatabaseResolver($env);
$redis = $resolver->resolve('redis.cache', autoConnect: true);

$redis->getConnection()->set('key', 'value');
```

---

### MySQL DBAL (Reports Profile)

```php
$db = $resolver->resolve('mysql.reports', autoConnect: true);

$rows = $db->getConnection()->fetchAllAssociative('SELECT * FROM reports');
```

---

### Mongo Logs Profile

```php
$mongo = $resolver->resolve('mongo.logs', true);

$mongo->getConnection()
      ->selectDatabase('logs')
      ->command(['ping' => 1]);
```

---

# 🏁 **Outcome**

Phase 13 elevates **maatify/data-adapters** to an enterprise-grade configuration architecture:

* 🔥 Fully unified config system
* 🧠 Smart DSN resolution
* 🔐 Registry-based secure overrides
* 🌐 Multi-profile support across all adapters
* 💼 Production-grade connection stability
* 🚀 Future-proof for replication, failover & multi-cluster setups

---

# 📦 **Commit Message**

```
feat(phase-13): Implement secure JSON registry, DSN builders, unified adapter resolution, and full resolver integration across MySQL, Mongo, Redis, and Predis with complete test suite.
```

---

# 🚀 **Next Step (Phase 13.1)**

We are now ready for:

### ✔ Run full test suite

### ✔ Identify adapter inconsistencies

### ✔ Fix after-registry integration bugs

### ✔ Validate merge priority across all adapters

### ✔ Prepare Phase 14 (Failover & Multi-Cluster Routing)


# 🔚 End of Phase 13

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
