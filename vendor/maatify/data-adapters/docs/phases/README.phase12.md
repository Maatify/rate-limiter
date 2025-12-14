# 🚀 Phase 12 — Multi-Profile MongoDB Support

**Version:** 1.1.0
**Module:** `maatify/data-adapters`
**Status:** ✅ Completed
**Maintainer:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))
**Date:** 2025-11-14

---

# 🎯 Goal
Add full profile-based MongoDB configuration and DSN support identical to MySQL’s Phase 11 architecture, while preserving backward compatibility and without modifying EnvironmentConfig.

---

# 🧩 Phase Scope
This phase introduces:
- Profile-aware MongoDB routing: `mongo.main`, `mongo.logs`, `mongo.activity`
- DSN-first configuration with automatic parsing
- New `MongoConfigBuilder` to extract host/port/database from DSN
- Adapter-level merge logic identical to MySQLAdapter
- Per-profile MongoAdapter caching inside DatabaseResolver
- New PHPUnit test suite for profile resolution
- Documentation for profile usage and DSN examples

---

# 🏗️ Technical Design

### ✔ BaseAdapter
- Already resolves environment variables by profile prefix
- Already supports DSN-first strategy
- Must NOT be modified

### ✔ MongoConfigBuilder (new class)
- Reads: `MONGO_<PROFILE>_DSN`
- Parses DSN into host/port/database
- Returns empty DTO when DSN not found (so BaseAdapter legacy logic remains)

### ✔ MongoAdapter (overrides resolveConfig)
- Calls BaseAdapter resolver
- Calls MongoConfigBuilder
- Merges:
  - dsn → profile then legacy
  - host/port/database → profile then legacy
  - user/pass/options → always legacy
- Connects using fully merged config

### ✔ DatabaseResolver
- Adds caching for profile-based Mongo instances
- Supports string routing: `mongo.{profile}`

---

# 🧪 Testing

### Test Suite: `MongoProfileResolverTest`

**Covers:**
- DSN-based profile extraction
- `mongo.main` and `mongo.logs` configurations
- Profile independence
- Integration with DatabaseResolver
- Final merged config fields

Example test setup:
```php
$_ENV['MONGO_MAIN_DSN'] = 'mongodb://localhost:27017/main';
$_ENV['MONGO_LOGS_DSN'] = 'mongodb://localhost:27017/logs';
````

---

# 📝 Example Usage

### Profile-based resolution:

```php
$resolver = new DatabaseResolver(new EnvironmentConfig(__DIR__));

$mongoMain = $resolver->resolve('mongo.main', autoConnect: true);
$mongoLogs = $resolver->resolve('mongo.logs', autoConnect: true);
```

### DSN Example:

```env
MONGO_MAIN_DSN=mongodb://localhost:27017/maatify
MONGO_LOGS_DSN=mongodb://localhost:27017/logs
```

### Resulting config:

```php
$cfg = $mongo->debugConfig();

$cfg->database; // "logs"
$cfg->host;     // "localhost"
$cfg->port;     // "27017"
```

---

# ✔ Summary

Phase 12 successfully adds:

* Clean profile-based MongoDB resolution
* DSN-first parsing with proper fallback
* New builder: `MongoConfigBuilder`
* Updated MongoAdapter merge logic
* Resolver-level caching
* Full test coverage
* Zero BC breaks

---

# 🔚 End of Phase 12

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — [https://www.maatify.dev](https://www.maatify.dev)

📘 Full documentation & source code:
[https://github.com/Maatify/data-adapters](https://github.com/Maatify/data-adapters)

---
