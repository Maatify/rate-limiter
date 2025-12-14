# 📘 **Phase 15 — Raw Access Layer + DSN Stabilization**

**Version:** 1.2.0
**Base Version:** 1.2.0
**Maintainer:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))
**Project:** maatify/data-adapters
**Module:** Data Adapters
**Date:** 2025-11-17
**Status:** ✅ Completed

---

## 🚀 Overview

Phase 15 introduces a fully unified **raw driver access layer** across all database adapters in the Maatify ecosystem, together with a complete stabilization of **DSN parsing**, **Doctrine URL handling**, and **safe password encoding**.

This phase resolves long-standing issues with DSN parsing, unsafe characters in Doctrine URLs, and inconsistencies in MySQL profile routing.
It also provides a safe and clean API for developers to directly access native drivers (PDO, DBAL, MongoDB Database, Redis Client) when needed.

---

## 🎯 Goals

### **1. Unified Raw Access Layer**

* Expose underlying drivers:

    * **PDO** for MySQL
    * **Doctrine DBAL Connection** for MySQL
    * **MongoDB\Database** for MongoDB
    * **Redis / Predis\Client** for Redis
* Provide:

  ```php
  $repo->getDriver();
  ```

  across all repositories.

### **2. DSN Stabilization**

* Accurate parsing of:

    * PDO DSNs (`mysql:host=...;port=...;dbname=...`)
    * Doctrine URL DSNs (`mysql://user:pass@host:port/db`)
* Introduced strict regex parser to replace `parse_url()` which breaks on unsafe passwords.
* Fixed handling of:

    * Encoded passwords (`%40`, `%3A`, etc.)
    * Missing database
    * Missing port
    * Null values
    * Trailing slashes

### **3. Routing & Driver Selection Fixes**

* Accurate routing for:

  ```
  mysql
  mysql.main
  mysql.reporting
  mysql.billing
  ```
* Respecting:

  ```
  MYSQL_<PROFILE>_DRIVER=pdo|dbal
  ```
* Full alignment between DSN, legacy ENV, and registry.

### **4. Real Connection Support (Local + CI)**

* Clean separation between:

    * Routing-only tests
    * Real connection tests
    * Parser tests
* Real MySQL connection test now works reliably in both:

    * Local development (reads .env)
    * GitHub CI (reads workflow-provided env vars)

---

## 🧩 Implemented Features

### **Raw Access Layer**

| Repository | getDriver() returns             |
|------------|---------------------------------|
| MySQL      | PDO or Doctrine DBAL Connection |
| MongoDB    | MongoDB\Database                |
| Redis      | Redis/Predis Client             |

---

## 🔧 Internal Changes

### **DSN Handling**

* Replaced `parse_url()` with **safe regex parser**
* Encoded password support
* Doctrine DSN validation
* PDO DSN normalization

### **MySqlConfigBuilder Updates**

* Consistent config normalization
* DSN → Legacy → Registry merge ordering
* Fixed null-database and missing-port issues

### **Adapter Updates**

* MySQLAdapter DSN-first logic
* MySQLDbalAdapter URL-DSN password patch + fallback logic
* DatabaseResolver routing stabilization

---

## 🧪 Testing Summary

### **Unit Tests**

* `MysqlDsnParserTest`

    * Pure parser verification
    * No real DB hit
    * Uses `$_ENV` in testing mode
* `RawDriverRoutingTest`

    * Ensures correct adapter class selected
    * Never creates real DB connections

### **Integration Tests**

* `RawAccessTest`

    * Ensures getDriver() returns correct driver instance
* `RealMysqlDualConnectionTest`

    * Runs real MySQL connections for:

        * PDO (MYSQL_DSN)
        * DBAL (MYSQL_MAIN_DSN)
    * Supports overriding DSN via `putenv()` before reloading EnvironmentConfig

---

## 📦 Completed Tasks

* Added `getDriver()` to MySQL / Mongo / Redis repositories
* Added DSN normalization + strict parser
* Safe password encoding for URL-based DSNs
* Improved driver routing logic
* Integration tests for raw access
* Unified CI + Local testing behavior
* Updated MySQLAdapter + MySQLDbalAdapter
* Fixed null database/port issues
* Updated phase output and executor.json linkage

---

## 🧹 Fixed Issues

* Doctrine DSN failures with `@ : ; } | ?` characters
* parse_url() incorrect parsing for URL-DSNs
* ltrim() fatal error on null database
* DBAL connection failing due to unencoded password
* DSN overrides being ignored in profile routing
* EnvironmentConfig skipping correct source in testing mode

---

## 📌 Notes for Developers

* Always reload `EnvironmentConfig` after calling `putenv()` in tests.
* Use:

  ```php
  $_ENV["APP_ENV"] = "testing";
  ```

  when wanting **predictable** environment behavior.
* For real connection tests:

    * CI must provide MySQL creds
    * Local environment uses `.env` unless overridden

---

## 🏁 Conclusion

Phase 15 delivers a stable, reliable, and developer-friendly foundation for database interaction across the entire Maatify ecosystem.

With unified raw access, robust DSN handling, and consistent routing, the data layer is now fully ready for advanced repository patterns in the upcoming **Phase 16 (Pagination Engine)**.

---

# 🔚 End of Phase 15

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
