# 📦 **maatify/data-adapters**

## **Roadmap — Version 1.1.0 (Updated After Phase 12)**

![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

**Owner:** Maatify.dev
**Base Version:** 1.0.0
**Maintainer:** Mohamed Abdulalim (megyptm)
**Goal:** Build a unified, modern, profile-aware connectivity layer with DSN-first configuration and optional dynamic registry.

---

# 🚀 Overview

Version **1.1.0** now includes major enhancements:

* ✔ **Full DSN support for all adapters** (Phase 10)
* ✔ **Multi-profile MySQL** with a dedicated builder (Phase 11)
* ✔ **Multi-profile MongoDB** with DSN parsing and profile merging (Phase 12)
* 🔄 Optional dynamic registry (Phase 13 — pending)
* 📚 Final release documentation (Phase 14 — pending)

All core architecture phases (10–12) are now **fully completed**.

---

# 🧩 **Phase 10 — DSN Support (COMPLETED)**

### *Status: ✅ Completed — 100%*

### 🎯 Goal

اعتماد DSN كطريقة التكوين الأساسية، مع إبقاء المتغيرات القديمة مدعومة لتوافق كامل.

### 🔧 Completed Tasks

* إضافة `getDsnConfig()`
* دعم كامل:

    * `MYSQL_*_DSN`
    * `MONGO_*_DSN`
    * `REDIS_*_DSN`
* تفعيل أولوية DSN على جميع المتغيرات القديمة
* إضافة DSN parsing لكل Adapter
* إضافة Test Suite خاص بالـ DSN
* إنشاء ملف: `README.phase10.md`

---

# 🧩 **Phase 11 — Multi-Profile MySQL (COMPLETED)**

### *Status: ✅ Completed — 100%*

### 🎯 Goal

إضافة دعم Profiles غير محدودة مثل:

```
mysql.main
mysql.logs
mysql.analytics
mysql.billing
mysql.<any>
```

### 🔧 Completed Tasks

* إنشاء `MySqlConfigBuilder`
* دعم غير محدود للملفات عبر `MYSQL_<PROFILE>_*`
* Override `resolveConfig()` داخل MySQLAdapter
* دمج DSN → Builder → Legacy
* إضافة caching على مستوى الـ Resolver
* إضافة Test Suite
* إنشاء ملف `README.phase11.md`

---

# 🧩 **Phase 12 — Multi-Profile MongoDB (COMPLETED)**

### *Status: ✅ Completed — 100%*

### 🎯 Goal

دعم كامل لجميع ملفات MongoDB، بنفس أسلوب مرحلـة MySQL:

```
mongo.main
mongo.logs
mongo.activity
mongo.events
mongo.<any>
```

### 🔧 Completed Tasks

* إنشاء `MongoConfigBuilder`
* دعم DSN + Legacy لكل ملف
* Override `resolveConfig()` في MongoAdapter
* Cache على مستوى الـ Resolver
* Test Suite كامل للملفات
* إنشاء ملف `README.phase12.md`

---

# 🧩 **Phase 13 — Dynamic JSON Registry (Optional)**

### *Status: ⏳ Planned — 0%*

### 🎯 Goal

تحميل إعدادات قواعد البيانات من JSON واحد:

```
config/databases.json
```

مع أولوية:

**JSON → DSN → ENV**

### 🔧 Tasks

* إضافة Registry Loader
* تعريف Schema
* دعم Hot Reload
* إضافة Tests
* إنشاء `README.phase13.md`

### 🔗 Dependencies

`phase10`, `phase11`, `phase12`

---

# 🧩 **Phase 14 — Documentation & Release 1.1.0**

### *Status: 🟨 Pending — 0%*

### 🎯 Goal

إصدار النسخة النهائية 1.1.0 مع توثيق شامل.

### 🔧 Tasks

* دمج كل مراحل التوثيق داخل `docs/README.full.md`
* تحديث README الأساسي
* تحديث CHANGELOG
* تأكيد تغطية الاختبارات (> 90%)
* نشر النسخة على Packagist

### 🔗 Dependencies

`phase10`, `phase11`, `phase12`, `phase13`

---

# 🟦 Summary (Updated)

| Phase | Title                         | Status      |
|-------|-------------------------------|-------------|
| 10    | DSN Support                   | ✅ Completed |
| 11    | Multi-Profile MySQL           | ✅ Completed |
| 12    | Multi-Profile Mongo           | ✅ Completed |
| 13    | Dynamic JSON Registry         | ⏳ Planned   |
| 14    | Documentation & Release 1.1.0 | 🟨 Pending  |


---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — [https://www.maatify.dev](https://www.maatify.dev)

📘 Full documentation & source code:
[https://github.com/Maatify/data-adapters](https://github.com/Maatify/data-adapters)

---
