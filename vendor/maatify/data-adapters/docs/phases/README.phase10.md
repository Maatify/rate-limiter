![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

# 🚀 Phase 10 — DSN Support for All Adapters
**Version:** 1.1.0
**Module:** maatify/data-adapters
**Status:** ✅ Completed
**Maintainer:** Mohamed Abdulalim (megyptm)

---

# 🎯 Goal

Introduce **first-class DSN support** across *all* database adapters
(**MySQL**, **Redis**, **MongoDB**) to simplify environment configuration, reduce duplication,
and prepare the architecture for multi-profile routing and dynamic registry (Phase 13).

This phase modernizes the configuration layer and becomes a foundation for:

- Phase 11 → MySQL Profiles
- Phase 12 → Mongo Profiles
- Phase 13 → Dynamic Registry

---

# 🧠 Why DSN Support?

Before Phase 10, each adapter required 4–6 environment variables:

```
MYSQL_HOST
MYSQL_PORT
MYSQL_DB
MYSQL_USER
MYSQL_PASS
MYSQL_CHARSET
```

This becomes bulky and error-prone — especially with:

- Multiple MySQL connections
- Multiple Mongo profiles
- Custom Redis instances
- Future registry-based profiles

✨ **DSN reduces everything to ONE LINE:**

```
MYSQL_MAIN_DSN="mysql:host=127.0.0.1;dbname=maatify;charset=utf8mb4"
```

This aligns `maatify/data-adapters` with modern frameworks:

- Laravel
- Symfony
- Doctrine DBAL
- Native PDO

---

# 🧩 Phase Scope

This phase introduces:

### ✔ Full DSN support for all adapters
- **MySQL** — PDO + DBAL
- **MongoDB** — mongodb/mongodb
- **Redis** — phpredis + Predis

### ✔ Universal DSN priority system
1. DSN (`*_DSN`)
2. Prefixed env vars (`MYSQL_MAIN_HOST`)
3. Legacy vars (`MYSQL_HOST`)
4. ❌ Defaults (removed — now explicit only)

### ✔ Unified DSN Reader
EnvironmentConfig gains:

```php
getDsnConfig(string $type, ?string $profile = null)
```

### ✔ Adapter Enhancements
- Direct DSN handling
- Automatic merging of:

    * username
    * password
    * database
    * driver options

- No magic rewriting
- No auto bootstrap hacks

---

# 🏗️ Technical Design

## 1️⃣ Environment Variable Structure

### 🔹 MySQL
```
MYSQL_MAIN_DSN="mysql:host=10.10.0.5;dbname=maatify_main;charset=utf8mb4"
MYSQL_LOGS_DSN="mysql:host=10.10.0.7;dbname=maatify_logs"
```

### 🔹 MongoDB
```
MONGO_MAIN_DSN="mongodb://127.0.0.1:27017/maatify"
```

### 🔹 Redis
```
REDIS_CACHE_DSN="redis://127.0.0.1:6379"
```

---

## 2️⃣ DSN Priority Algorithm (Resolver Level)

```text
If DSN exists → use DSN
Else if HOST/PORT exist → build DSN
Else → throw InvalidConfigurationException
```

Applies to:
- mysql
- mysql.{profile}
- mongo
- mongo.{profile}
- redis

---

## 3️⃣ DatabaseResolver Updates

### Before Phase 10
```
resolve("mysql")
resolve("redis")
resolve("mongo")
```

### After Phase 10
```
resolve("mysql")           → DSN or env-vars
resolve("mysql.main")      → DSN or prefixed vars
resolve("redis.cache")     → DSN or env-vars
resolve("mongo.activity")  → DSN or env-vars
```

---

## 4️⃣ Adapter Updates

### 🔹 MySQLAdapter (PDO)
- Accepts DSN directly
- Merges credentials & options

### 🔹 MySQLDbalAdapter
- DSN becomes Doctrine `url` parameter

### 🔹 MongoAdapter
- DSN passed directly to `MongoDB\Client`

### 🔹 RedisAdapter / PredisAdapter
- DSN parsed to host/port/password

---

# 🔤 Resolver String-Based Routing (New Feature)

### Introduced in Phase 10:
Resolver can now parse connection strings like:

```
"mysql.main"
"mongo.logs"
"redis.cache"
```

### Parsing Logic
```
if contains "."
    type = before dot
    profile = after dot
else
    type = value
    profile = null
```

### Backward Compatibility
Enums still work:

```
$resolver->resolve(DatabaseType::MYSQL);
```

Internally normalized to:

```
resolve("mysql");
```

---

# 🧪 Testing

Phase 10 includes **6 new test suites**:

- `DsnResolverTest`
- `MysqlDsnAdapterTest`
- `MysqlDbalDsnAdapterTest`
- `MongoDsnAdapterTest`
- `RedisDsnAdapterTest`
- `PredisDsnAdapterTest`

### Coverage
✔ DSN priority
✔ Legacy fallback
✔ Profile routing
✔ Adapter-level DSN handling
✔ Resolver parsing

---

# 📝 Example Usage

## MySQL via DSN
```php
$config   = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($config);

$db = $resolver->resolve("mysql.main");
$db->connect();
```

**ENV**
```env
MYSQL_MAIN_DSN="mysql:host=192.168.1.55;dbname=maatify_main;charset=utf8mb4"
MYSQL_MAIN_USER="root"
MYSQL_MAIN_PASS="secret"
```

---

## Mongo via DSN
```php
$mongo = $resolver->resolve("mongo.logs");
$mongo->connect();
```

---

## Redis via DSN
```php
$redis = $resolver->resolve("redis.cache");
$redis->connect();
```

---

# ✔ Summary

Phase 10 introduces:

- A modern DSN-first architecture
- String-based routing for profiles
- Unified connection config
- Cleaner environment variables
- Future-proof design for upcoming dynamic registry work

This fully unlocks Phases 11, 12, and 13.

---

# 🔚 End of Phase 10

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))**
https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters
