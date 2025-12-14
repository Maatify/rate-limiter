
---

> 🔗 [English Version 🇬🇧](env.md)

---

# 🧩 المتغيرات الأساسية (DSN-First + Multi-Profile)

## ✔ MySQL (Phase 11 — دعم الـ Profiles المتعددة)

| المثال                           | الوصف                                                          |
|----------------------------------|----------------------------------------------------------------|
| `MYSQL_MAIN_DSN`                 | الـ DSN الخاص بقاعدة بيانات الـ Main                           |
| `MYSQL_LOGS_DSN`                 | الـ DSN الخاص بقاعدة بيانات الـ Logs                           |
| `MYSQL_ANALYTICS_DSN`            | الـ DSN الخاص بقاعدة بيانات الـ Analytics                      |
| `MYSQL_<PROFILE>_DSN`            | **أي Profile إضافي** (مثل billing, reporting… إلخ)             |
| `MYSQL_<PROFILE>_USER` / `_PASS` | بيانات تسجيل الدخول (تُستخدم إذا لم يحتوي DSN على credentials) |

---

## ✔ MongoDB (Phase 12 — دعم الـ Profiles المتعددة)

| المثال                           | الوصف                                               |
|----------------------------------|-----------------------------------------------------|
| `MONGO_MAIN_DSN`                 | DSN ملف الـ main                                    |
| `MONGO_LOGS_DSN`                 | DSN ملف logs                                        |
| `MONGO_ACTIVITY_DSN`             | DSN ملف activity                                    |
| `MONGO_<PROFILE>_DSN`            | **أي Profile إضافي** مثل analytics، archive، events |
| `MONGO_<PROFILE>_USER` / `_PASS` | بيانات تسجيل الدخول لكل Profile                     |

---

## ✔ Redis (Phase 10+)

| المتغير               | الوصف                                  |
|-----------------------|----------------------------------------|
| `REDIS_CACHE_DSN`     | DSN كامل للـ Redis (الكاش / الطوابير). |
| `REDIS_<PROFILE>_DSN` | دعم ملفات Redis متعددة (دعم مستقبلي).  |
| `REDIS_PASS`          | كلمة المرور إن لم تُكتب داخل الـ DSN.  |

---

## ✔ متغيرات النظام العامة

| المتغير                 | الوصف                                           |
|-------------------------|-------------------------------------------------|
| `APP_ENV`               | بيئة التطبيق (`local`, `testing`, `production`) |
| `LOG_PATH`              | مسار ملفات اللوج الأساسية                       |
| `ADAPTER_LOG_PATH`      | مسار لوجات الأدابتور لكل Driver                 |
| `METRICS_ENABLED`       | تفعيل Exporter (Prometheus / JSON)              |
| `METRICS_EXPORT_FORMAT` | `prometheus`, `json`, أو `none`                 |
| `METRICS_SAMPLING_RATE` | نسبة أخذ العينات (0.0 – 1.0)                    |

---

# ⚠️ المتغيرات القديمة (ما زالت مدعومة — لكن Deprecated)

| المتغيرات القديمة                      | البديل الرسمي   |
|----------------------------------------|-----------------|
| `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_DB` | → `MYSQL_*_DSN` |
| `MONGO_HOST`, `MONGO_PORT`, `MONGO_DB` | → `MONGO_*_DSN` |
| `REDIS_HOST`, `REDIS_PORT`             | → `REDIS_*_DSN` |

---

# 🧠 **مثال لملف `.env` بعد Phase 10 → Phase 12**

```env
# ----------------------------------------------------------
# 🔵 MYSQL (دعم Multi-Profile — Phase 11)
# ----------------------------------------------------------

# قاعدة بيانات MAIN
MYSQL_MAIN_DSN="mysql:host=127.0.0.1;dbname=maatify_main;charset=utf8mb4"
MYSQL_MAIN_USER=root
MYSQL_MAIN_PASS=secret_main

# قاعدة بيانات LOGS
MYSQL_LOGS_DSN="mysql:host=127.0.0.1;dbname=maatify_logs;charset=utf8mb4"
MYSQL_LOGS_USER=logger
MYSQL_LOGS_PASS=secret_logs

# قاعدة بيانات ANALYTICS
MYSQL_ANALYTICS_DSN="mysql:host=127.0.0.1;dbname=maatify_analytics"
MYSQL_ANALYTICS_USER=analytics_user
MYSQL_ANALYTICS_PASS=secret_analytics

# مثال لملف مخصص (Billing)
MYSQL_BILLING_DSN="mysql:host=127.0.0.1;dbname=billing_service"
MYSQL_BILLING_USER=billing_user
MYSQL_BILLING_PASS=secret_billing


# ----------------------------------------------------------
# 🟢 MONGODB (دعم Multi-Profile — Phase 12)
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

# ملف مخصص (Events)
MONGO_EVENTS_DSN="mongodb://127.0.0.1:27017/events"
MONGO_EVENTS_USER=mongo_events_user
MONGO_EVENTS_PASS=mongo_events_pass


# ----------------------------------------------------------
# 🔴 REDIS (DSN-First)
# ----------------------------------------------------------
REDIS_CACHE_DSN="redis://127.0.0.1:6379"
REDIS_CACHE_PASS=redis_password


# ----------------------------------------------------------
# ⚙️ إعدادات عامة
# ----------------------------------------------------------
APP_ENV=local
LOG_PATH=storage/logs
ADAPTER_LOG_PATH=storage/adapter_logs


# ----------------------------------------------------------
# 📊 القياس والمراقبة (Metrics)
# ----------------------------------------------------------
METRICS_ENABLED=true
METRICS_EXPORT_FORMAT=prometheus
METRICS_SAMPLING_RATE=1.0
```

---

# © 2025 Maatify.dev

مصمم ومطوّر بواسطة **محمد عبدالعليم ([@megyptm](https://github.com/megyptm))** — [https://www.maatify.dev](https://www.maatify.dev)

📘 المستودع الرسمي + التوثيق الكامل:
[https://github.com/Maatify/data-adapters](https://github.com/Maatify/data-adapters)

---
