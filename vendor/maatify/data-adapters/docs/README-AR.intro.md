# 📘 **Maatify Data-Adapters — Clear Intro Guide (Arabic Version)**

![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

# 📦 **Maatify Data-Adapters**

**Unified, clean, and consistent database connectivity layer for Maatify PHP projects.**

هذه الوثيقة تشرح المشروع بشكل بسيط وواضح لأي شخص جديد عليه.

---

# 🚀 ما هو المشروع؟

**maatify/data-adapters** هو مكتبة PHP مسئولة عن إدارة الاتصال بالأنظمة التالية:

* **MySQL** (PDO + Doctrine DBAL)
* **Redis** (phpredis + predis fallback)
* **MongoDB**

المكتبة تقدم **واجهة واحدة موحّدة** للتعامل مع هذه الأنظمة بدون تكرار الكود في كل مشروع.

---

# 🧩 كيف تعمل المكتبة؟

تتكون من 3 عناصر رئيسية:

---

## 1️⃣ EnvironmentConfig

المسؤول عن جلب الإعدادات من البيئة (`$_ENV`):

* لا يقوم بتحميل ملف `.env`
* المشروع نفسه يقوم بتحميل `.env`
* المكتبة تقرأ فقط القيم الجاهزة
* تدعم MySQL profiles مثل:

```
MYSQL_MAIN_HOST
MYSQL_LOGS_HOST
MYSQL_ANALYTICS_HOST
```

---

## 2️⃣ DatabaseResolver

العقل الأساسي للمكتبة.

وظيفته:

* اختيار الـ Adapter المناسب
* دعم profiles في MySQL
* اختيار الـ driver (PDO / DBAL)
* اختيار Redis driver حسب المتاح (phpredis أو predis)

الاستخدام:

```php
$resolver->resolve("mysql.main");
$resolver->resolve("mysql.logs");
$resolver->resolve("redis");
$resolver->resolve("mongo");
```

---

## 3️⃣ Adapters

لكل نوع من قاعدة البيانات يوجد Adapter مستقل:

### ✔️ MySQL

* `MySQLAdapter` (PDO)
* `MySQLDbalAdapter` (DBAL)

### ✔️ Redis

* `RedisAdapter`
* `PredisAdapter` (fallback)

### ✔️ Mongo

* `MongoAdapter`

---

# 🔥 كيف يتم بناء المشروع؟

نستخدم نظام مراحل (Phases).
لكل Phase:

* هدف
* مهام
* تعديلات في الملفات
* اختبارات (PHPUnit)
* توثيق داخل `docs/phases`
* تحديث README و CHANGELOG

نظام ثابت، يجعل تطوّر المشروع نظيف وواضح واحترافي.

---

# 📊 الوضع الحالي للمشروع

## ✔️ النسخة 1.0.0

* جميع الـ Adapters جاهزة
* Diagnostics / Logging / Metrics
* اختبارات Integration
* توثيق كامل
* حذف fallback القديم من النظام

## ✔️ النسخة 1.1.0 (جارية الآن)

### Phase 10 — Multi-Profile MySQL

إضافة دعم:

```
mysql.main
mysql.logs
mysql.analytics
```

مع القدرة على تحميل إعدادات منفصلة لكل Profile.

---

# 🎯 ماذا لا تفعله المكتبة؟

* لا تحمل `.env`
* لا تعيد المحاولة أو تعمل auto-reconnect
* لا تدير fallback queues أو recovery workers
* لا تتحكم في الـ environment — المشروع الخارجي هو المسؤول

---

# 🛠️ مثال للاستخدام

```php
$config = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($config);

// Connect to main MySQL
$db = $resolver->resolve("mysql.main");
$db->connect();

// Connect to logs database
$logs = $resolver->resolve("mysql.logs");
$logs->connect();

// Redis
$redis = $resolver->resolve("redis");
$redis->connect();
```

---

# 🎯 لمن هذه الوثيقة؟

هذه الصفحة مناسبة لأي شخص:

* جديد على المشروع
* يريد فهمه خلال 3 دقائق
* يحتاج البدء في Phase جديدة
* يعمل على مكتبة داخل Ecosystem Maatify
* أو يريد استخدام المكتبة داخل مشروعه

---

# 🔑 المبادئ الأساسية

* المكتبة بسيطة وليست framework
* تستخدم PSR-12 + strong typing
* لا يوجد أي magic behavior
* كل شيء يتم عبر EnvironmentConfig + DatabaseResolver
* التوثيق دائمًا داخل `docs/phases`

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
