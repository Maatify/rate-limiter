# 🚀 Phase 11 — Multi-Profile MySQL Connections

**Version:** 1.1.0
**Module:** `maatify/data-adapters`
**Status:** ✅ Completed
**Maintainer:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))
**Date:** 2025-11-14

---

# 🎯 Goal

Enable **fully dynamic multi-profile MySQL configuration**, supporting routes such as:

* `mysql.main`
* `mysql.logs`
* `mysql.analytics`
* `mysql.reporting`
* `mysql.<any-profile>` (A2 + Dynamic)

Each profile must map automatically to its environment variables:

```
MYSQL_MAIN_DSN
MYSQL_LOGS_HOST
MYSQL_ANALYTICS_DB
MYSQL_REPORTING_USER
MYSQL_<CUSTOM>_PASS
```

### Key objectives

* 🧠 **DSN-first resolution**
* 🔄 **Backward compatibility** with legacy `*_HOST`, `*_PORT`, `*_DB`, etc.
* 🧩 **Profile isolation per adapter**
* ⚙️ **Centralized configuration via MySqlConfigBuilder**
* 🧪 **Comprehensive PHPUnit coverage**

---

# 🧩 Phase Scope

### Introduced in this phase

* ✅ `MySqlConfigBuilder` (new)
* ✅ MySQL adapters now override `resolveConfig()`
* ✅ Dynamic unlimited profile support
* ✅ Centralized merging logic (Builder + BaseAdapter + DSN)
* ✅ Full test suite for all profile variations

### Outside the scope (future phases)

* ❌ MongoDB profile support → **Phase 12**
* ❌ Dynamic registry → **Phase 13**

---

# 🏗️ Technical Design

## 1) MySQL Adapter Architecture

Both MySQL adapters now override:

```php
protected function resolveConfig(ConnectionTypeEnum $type): ConnectionConfigDTO
```

Resolution steps:

1. BaseAdapter builds legacy configuration (`mysql`, `mysql.main`, etc.)
2. `MySqlConfigBuilder` builds DSN-aware profile configuration
3. Builder overrides BaseAdapter
4. DSN overrides everything (highest priority)
5. Result = unified `ConnectionConfigDTO`

---

## 2) Dynamic Profile Resolution

Profiles are **not limited** to `main/logs/analytics`.

Example:

```
mysql.billing
mysql.reporting
mysql.admin
```

Automatically mapped to:

```
MYSQL_BILLING_HOST
MYSQL_ADMIN_DSN
MYSQL_REPORTING_DB
MYSQL_REPORTING_USER
```

No registration, no enum, no static list → **fully dynamic**.

---

## 3) DSN Priority

Supported formats:

### ① PDO-Style DSN

```
mysql:host=1.2.3.4;dbname=test;port=3310;charset=utf8mb4
```

### ② Doctrine-Style URL

```
mysql://user:pass@10.0.0.5:3307/logsdb
```

### ③ Legacy Variables

```
MYSQL_LOGS_HOST
MYSQL_LOGS_PORT
MYSQL_LOGS_DB
MYSQL_LOGS_USER
MYSQL_LOGS_PASS
```

Priority:

```
DSN → Builder → Legacy
```

---

## 4) Affected Components

| File / Component     | Change                      |
|----------------------|-----------------------------|
| `MySqlConfigBuilder` | ✅ New class                 |
| `MySQLAdapter`       | 🔄 Uses builder & merges    |
| `MySQLDbalAdapter`   | 🔄 Same unified config path |
| `BaseAdapter`        | ❌ Unchanged                 |
| `EnvironmentConfig`  | ❌ Unchanged                 |
| Tests                | ✅ New test suite            |

---

# 🧪 Testing

### Test Suite:

`tests/MySQL/MysqlProfileResolverTest.php`

### Verified Scenarios:

| Scenario                                      | Status |
|-----------------------------------------------|--------|
| DSN overrides all other variables             | ✅      |
| Dynamic profiles (`mysql.reporting`) work     | ✅      |
| Doctrine DSN parsing                          | ✅      |
| Legacy-only env still supported               | ✅      |
| Builder merging with BaseAdapter              | ✅      |
| DBAL adapter uses builder correctly           | ✅      |
| Unknown/unregistered profiles behave properly | ✅      |

### Environment:

```
APP_ENV=testing
```

All tests passed.

---

# 📝 Example Usage

## 1️⃣ Resolver

```php
$resolver = new DatabaseResolver($config);

$logsDb = $resolver->resolve('mysql.logs', autoConnect: true);
```

## 2️⃣ .env Example

```env
MYSQL_LOGS_DSN=mysql:host=10.0.0.10;dbname=logs

MYSQL_REPORTING_HOST=192.168.22.5
MYSQL_REPORTING_USER=report
MYSQL_REPORTING_PASS=secret
MYSQL_REPORTING_DB=analytics_data
```

## 3️⃣ Direct Adapter

```php
$adapter = new MySQLAdapter($config, profile: 'reporting');
$adapter->connect();
```

---

# ✔ Summary

Phase 11 delivers:

* 🔥 Fully dynamic multi-profile MySQL connections
* 🧠 DSN-aware centralized configuration
* 🛠 Robust backward compatibility
* 🧪 90%+ coverage for all MySQL profile modes
* 🧰 Standardized config path preparing for Phase 12 (Mongo)

This phase completes the MySQL configuration system and sets the foundation for the next two phases of the architecture roadmap.

---

# 🧱 Phase Status

| Phase | Title                           | Status      |
|-------|---------------------------------|-------------|
| 10    | DSN Support for All Adapters    | ✅ Completed |
| 11    | Multi-Profile MySQL Connections | ✅ Completed |

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — [https://www.maatify.dev](https://www.maatify.dev)

📘 Full documentation & source code:
[https://github.com/Maatify/data-adapters](https://github.com/Maatify/data-adapters)

---
