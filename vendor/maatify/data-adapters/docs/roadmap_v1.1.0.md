# 📦 **maatify/data-adapters**

## **Roadmap — Version 1.1.0 (Updated After Phase 12)**

![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

**Owner:** Maatify.dev
**Base Version:** 1.0.0
**Maintainer:** Mohamed Abdulalim (megyptm)
**Goal:** Extend the unified connectivity layer with DSN-first configuration, multi-profile database resolution, and optional dynamic registry.

---

# 🚀 Overview

Version **1.1.0** now includes all major architectural improvements:

* ✔ Full DSN support across all adapters (Phase 10)
* ✔ MySQL multi-profile system with builder integration (Phase 11)
* ✔ MongoDB multi-profile system with DSN parsing (Phase 12)
* 🔄 Optional dynamic JSON registry (Phase 13 — planned)
* 📚 Final documentation + release flow (Phase 14 — pending)

All core phases (10–12) are now **fully completed**.

---

# 🧩 **Phase 10 — DSN Support (COMPLETED)**

### *Status: ✅ Completed — 100%*

### 🎯 Goal

Introduce DSN-first configuration across MySQL, MongoDB, and Redis with full backward compatibility.

### ✔ Completed Work

* Added `getDsnConfig()` to EnvironmentConfig
* DSN variables supported:

    * `MYSQL_*_DSN`
    * `MONGO_*_DSN`
    * `REDIS_*_DSN`
* DSN parsing for all adapters
* Resolver updated for DSN-priority workflow
* Added DSN test suite
* New documentation: `README.phase10.md`

---

# 🧩 **Phase 11 — Multi-Profile MySQL (COMPLETED)**

### *Status: ✅ Completed — 100%*

### 🎯 Goal

Enable unlimited MySQL profiles such as:

```
mysql.main
mysql.logs
mysql.analytics
mysql.billing
mysql.<custom>
```

### ✔ Completed Work

* Implemented `MySqlConfigBuilder`
* Added dynamic profile support: `MYSQL_<PROFILE>_*`
* Overrode `resolveConfig()` inside `MySQLAdapter` + `MySQLDbalAdapter`
* Merge priority: **DSN → builder → legacy**
* Resolver now caches adapters per profile
* Full test suite implemented
* New documentation: `README.phase11.md`

---

# 🧩 **Phase 12 — Multi-Profile MongoDB (COMPLETED)**

### *Status: ✅ Completed — 100%*

### 🎯 Goal

Add profile-aware MongoDB resolution identical to MySQL architecture:

```
mongo.main
mongo.logs
mongo.activity
mongo.events
mongo.<custom>
```

### ✔ Completed Work

* Added `MongoConfigBuilder`
* DSN parsing for MongoDB (`mongodb://` and `mongodb+srv://`)
* Overrode `resolveConfig()` in MongoAdapter to merge builder + legacy
* Resolver caching for Mongo profiles
* Full Mongo test suite
* Documentation: `README.phase12.md`

---

# 🧩 **Phase 13 — Dynamic JSON Registry (Optional)**

### *Status: ⏳ Planned — 0%*

### 🎯 Goal

Load connection profiles dynamically from JSON:

```
config/databases.json
```

With priority:

**JSON → DSN → ENV**

### 🔧 Planned Tasks

* Registry loader
* JSON schema
* Merge strategy
* Hot reload support
* Registry tests
* Documentation: `README.phase13.md`

---

# 🧩 **Phase 14 — Documentation & Release 1.1.0**

### *Status: 🟨 Pending — 0%*

### 🎯 Goal

Finalize all documentation and publish version **1.1.0**.

### 🔧 Remaining Tasks

* Consolidate all phase docs into `docs/README.full.md`
* Update root README
* Update CHANGELOG
* Ensure >90% test coverage
* Tag and publish 1.1.0 on Packagist

---

# 🟦 Summary

| Phase | Title                         | Status      |
|-------|-------------------------------|-------------|
| 10    | DSN Support                   | ✅ Completed |
| 11    | Multi-Profile MySQL           | ✅ Completed |
| 12    | Multi-Profile MongoDB         | ✅ Completed |
| 13    | Dynamic JSON Registry         | ⏳ Planned   |
| 14    | Documentation & Release 1.1.0 | 🟨 Pending  |


---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim (megyptm)** — [https://www.maatify.dev](https://www.maatify.dev)
📘 Full source code: [https://github.com/Maatify/data-adapters](https://github.com/Maatify/data-adapters)

---
