
---

> 🔗 [بالعربي 🇸🇦 ](env-ar.md)

---

# 🔧 **Environment Variables (Updated for Phase 10 → Phase 13 Final Architecture)**

> ⚠️ **As of Phase 13: Global precedence is now:**
> **Registry → DSN → Legacy (Deprecated)**
> All three adapters (MySQL, MongoDB, Redis) now use **identical builder logic** and return a **fully normalized configuration DTO**.

---

## 🧩 Primary Rules Introduced in Phase 13

### ✔ Unified builder behavior

All builders now normalize and return:

```
host, port, user, pass, database, options, driver, profile
```

### ✔ Registry-first priority

If registry.json contains overrides, they override:

* DSN
* Legacy
* Default values

### ✔ Unified DSN parsing

MySQL, MongoDB, and Redis now follow the same parsing strategy and the same merge rules.

### ✔ Unlimited profiles

MySQL and MongoDB — fully supported.
Redis — future-ready with same builder structure (Phase 13).

---

# 🧩 DSN-First Variables (Multi-Profile)

## ✔ MySQL (Phase 11 + Phase 13)

| Variable Example                 | Description                                            |
|----------------------------------|--------------------------------------------------------|
| `MYSQL_MAIN_DSN`                 | Main profile DSN                                       |
| `MYSQL_LOGS_DSN`                 | Logs database DSN                                      |
| `MYSQL_ANALYTICS_DSN`            | Analytics DSN                                          |
| `MYSQL_<PROFILE>_DSN`            | Unlimited profiles (billing, reporting, archive, etc.) |
| `MYSQL_<PROFILE>_USER` / `_PASS` | Used only when DSN does not contain credentials        |

---

## ✔ MongoDB (Phase 12 + Phase 13)

| Variable Example                 | Description                                           |
|----------------------------------|-------------------------------------------------------|
| `MONGO_MAIN_DSN`                 | Main profile DSN                                      |
| `MONGO_LOGS_DSN`                 | Logs profile DSN                                      |
| `MONGO_ACTIVITY_DSN`             | Activity database DSN                                 |
| `MONGO_<PROFILE>_DSN`            | Unlimited profiles (events, analytics, archive, etc.) |
| `MONGO_<PROFILE>_USER` / `_PASS` | Used if DSN has no auth                               |

---

## ✔ Redis (Phase 10 + Phase 13 Unified Builder)

| Variable Example       | Description                        |
|------------------------|------------------------------------|
| `REDIS_CACHE_DSN`      | Main Redis DSN                     |
| `REDIS_<PROFILE>_DSN`  | Future-ready multi-profile support |
| `REDIS_<PROFILE>_PASS` | Used when DSN lacks credentials    |

---

# 🧠 Registry JSON (Introduced in Phase 13)

```json
{
  "redis": {
    "cache": {
      "host": "10.0.0.1",
      "port": 6380
    }
  },
  "mysql": {
    "main": {
      "user": "override_user"
    }
  }
}
```

> This overrides DSN + legacy, every time.

---

### ✔ System Variables

| Variable                | Description                      |
|-------------------------|----------------------------------|
| `APP_ENV`               | `local`, `testing`, `production` |
| `LOG_PATH`              | Application logs                 |
| `ADAPTER_LOG_PATH`      | Adapter-level driver logs        |
| `METRICS_ENABLED`       | Enable Prometheus/JSON exporter  |
| `METRICS_EXPORT_FORMAT` | `prometheus`, `json`, or `none`  |
| `METRICS_SAMPLING_RATE` | Sampling rate (0.0–1.0)          |

---

# ⚠️ Deprecated Variables (Still Supported)

| Deprecated Variables                   | Replaced by     |
|----------------------------------------|-----------------|
| `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_DB` | → `MYSQL_*_DSN` |
| `MONGO_HOST`, `MONGO_PORT`, `MONGO_DB` | → `MONGO_*_DSN` |
| `REDIS_HOST`, `REDIS_PORT`             | → `REDIS_*_DSN` |

---

# 🧠 **Example `.env` (Fully Updated — Phases 10 → 12)**

```env

# ----------------------------------------------------------
# 🔵 MYSQL ADAPTER (Multi-Profile — Phase 11)
# ----------------------------------------------------------

# MAIN DATABASE
MYSQL_MAIN_DSN="mysql:host=127.0.0.1;dbname=maatify_main;charset=utf8mb4"
MYSQL_MAIN_USER=root
MYSQL_MAIN_PASS=secret_main

# LOGS DATABASE
MYSQL_LOGS_DSN="mysql:host=127.0.0.1;dbname=maatify_logs;charset=utf8mb4"
MYSQL_LOGS_USER=logger
MYSQL_LOGS_PASS=secret_logs

# ANALYTICS DATABASE
MYSQL_ANALYTICS_DSN="mysql:host=127.0.0.1;dbname=maatify_analytics"
MYSQL_ANALYTICS_USER=analytics_user
MYSQL_ANALYTICS_PASS=secret_analytics

# Custom profile example (billing)
MYSQL_BILLING_DSN="mysql:host=127.0.0.1;dbname=billing_service"
MYSQL_BILLING_USER=billing_user
MYSQL_BILLING_PASS=secret_billing


# ----------------------------------------------------------
# 🟢 MONGODB ADAPTER (Multi-Profile — Phase 12)
# ----------------------------------------------------------

# MAIN
MONGO_MAIN_DSN="mongodb://127.0.0.1:27017/maatify_main"
MONGO_MAIN_USER=mongo_main_user
MONGO_MAIN_PASS=mongo_main_pass

# LOGS
MONGO_LOGS_DSN="mongodb://127.0.0.1:27017/logs"
MONGO_LOGS_USER=mongo_logs_user
MONGO_LOGS_PASS=mongo_logs_pass

# ACTIVITY
MONGO_ACTIVITY_DSN="mongodb://127.0.0.1:27017/activity"
MONGO_ACTIVITY_USER=mongo_activity_user
MONGO_ACTIVITY_PASS=mongo_activity_pass

# Custom profile (events)
MONGO_EVENTS_DSN="mongodb://127.0.0.1:27017/events"
MONGO_EVENTS_USER=mongo_events_user
MONGO_EVENTS_PASS=mongo_events_pass


# ----------------------------------------------------------
# 🔴 REDIS ADAPTER (DSN-First)
# ----------------------------------------------------------
REDIS_CACHE_DSN="redis://127.0.0.1:6379"
REDIS_CACHE_PASS=redis_password


# ----------------------------------------------------------
# ⚙️ GENERAL CONFIGURATION
# ----------------------------------------------------------
APP_ENV=local
LOG_PATH=storage/logs
ADAPTER_LOG_PATH=storage/adapter_logs


# ----------------------------------------------------------
# 📊 METRICS & OBSERVABILITY
# ----------------------------------------------------------
METRICS_ENABLED=true
METRICS_EXPORT_FORMAT=prometheus
METRICS_SAMPLING_RATE=1.0


```

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
