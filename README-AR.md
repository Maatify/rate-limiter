![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

[![Build Status](https://github.com/maatify/rate-limiter/actions/workflows/ci.yml/badge.svg)](https://github.com/maatify/rate-limiter/actions/workflows/ci.yml)
[![Current version](https://img.shields.io/packagist/v/maatify/rate-limiter)](https://packagist.org/packages/maatify/rate-limiter)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/rate-limiter)](https://packagist.org/packages/maatify/rate-limiter)
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/rate-limiter)](https://packagist.org/packages/maatify/rate-limiter/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/rate-limiter)](https://packagist.org/packages/maatify/rate-limiter/stats)
[![License](https://img.shields.io/github/license/maatify/rate-limiter)](https://github.com/maatify/rate-limiter/blob/main/LICENSE)

# 🧩 **Maatify Rate Limiter**

مكتبة **PSR-compliant Rate Limiter** تدعم Redis و MongoDB و MySQL  
مع موجه (Resolver) ديناميكي، وتكامل مباشر مع الـ Middleware، وتوافق مع العقود (Enums) القابلة لإعادة الاستخدام.

> 🔗 [English Version](./README.md)

---

<!-- PHASE_STATUS_START -->

## ✅ المراحل المكتملة

* [x] المرحلة 1 – إعداد البيئة (محليًا)
* [x] المرحلة 2 – الهيكل الأساسي
* [x] المرحلة 3 – مشغلات التخزين (Drivers)
* [x] المرحلة 3.1 – إعادة هيكلة Enums & Contracts
* [x] المرحلة 4 – الموجه & Middleware
* [x] المرحلة 4.1 – تكامل مستمر CI (Docker + GitHub Actions)
* [x] المرحلة 5 – التأخير التصاعدي (Exponential Backoff) والحد العام (Global Limit)
<!-- PHASE_STATUS_END -->

---

## ⚙️ الإعداد المحلي (Local Setup)

```bash
composer install
cp .env.example .env
````

ثم عدّل ملف `.env` ليطابق إعدادات قاعدة البيانات والمشغّل (Driver) المحلي لديك.

---

## 🧠 الوصف

توفر مكتبة **Maatify Rate Limiter** واجهة موحدة للتحكم في معدلات الطلب (Rate Limiting) عبر بيئات تخزين متعددة
(مثل Redis و MongoDB و MySQL) مع دعم موجه ديناميكي (Dynamic Resolver).

تتوافق مع معايير **PSR-12** و **PSR-15** و **PSR-7**
ويمكن دمجها مباشرة مع أطر مثل **Slim** أو **Laravel**.

---

## 📂 هيكل المشروع

```
maatify-rate-limiter/
│
├── .env.example
├── composer.json
├── .github/
│   └── workflows/
│       └── ci.yml
├── docker-compose.ci.yml
├── src/
│   ├── Config/
│   ├── Contracts/
│   ├── DTO/
│   ├── Drivers/
│   ├── Enums/
│   ├── Exceptions/
│   ├── Middleware/
│   └── Resolver/
│
├── tests/
├── docs/
│   └── phases/
│       ├── README.phase1.md
│       ├── README.phase2.md
│       ├── README.phase3.md
│       ├── README.phase3.1.md
│       ├── README.phase4.md
│       └── README.phase4.1.md
│
├── CHANGELOG.md
├── VERSION
└── README.md
```

---

## 🧩 التكامل المستمر CI/CD

🚀 تم تنفيذ التكامل الكامل باستخدام **Docker Compose + GitHub Actions**

* تشغيل Redis و MySQL و MongoDB داخل بيئات مستقلة.
* تنفيذ PHPUnit داخل Docker مع عرض مباشر للنتائج.
* توليد تلقائي لملف `.env` داخل خط الأنابيب.
* تخزين مؤقت لحزم Composer لتسريع التشغيل.
* إمكانية رفع نتائج الاختبارات تلقائيًا (`tests/_output`).

---

## 🧩 الإصدار الحالي

```
1.0.0-alpha-phase5
```

---

## 🧾 ملخص التغييرات (CHANGELOG SUMMARY)

### المرحلة 5 – Exponential Backoff & Global Limit

* إضافة **محدّد معدل تفاعلي** يعتمد على التأخير التصاعدي (2ⁿ).
* إضافة **حد عام لكل IP** عبر كل أنواع العمليات.
* توسيع `RateLimitStatusDTO` لتتضمن `backoffSeconds` و `nextAllowedAt`.
* إضافة اختبارات جديدة `tests/BackoffTest.php`.
* تحديث ملف `.env.example` بالقيم التالية:

    * `GLOBAL_RATE_LIMIT`
    * `GLOBAL_RATE_WINDOW`
    * `BACKOFF_BASE`
    * `BACKOFF_MAX`

---

## ✅ جدول الملخص

| البيئة                | مدعومة | الملاحظات                 |
| --------------------- | ------ | ------------------------- |
| PHP (محلي)            | ✅      | يعمل مباشرة               |
| Slim                  | ✅      | متوافق مع PSR-15          |
| Laravel               | ✅      | Middleware جاهز           |
| Redis / Mongo / MySQL | ✅      | يمكن التبديل بينها بسهولة |
| معايير PSR            | ✅      | PSR-7 / PSR-15 / PSR-12   |

---

# 📘 أمثلة الاستخدام (Usage Examples)

---

## 🧱 مثال 1️⃣ (باستخدام PHP فقط)

```php
$resolver = new RateLimiterResolver(['driver' => 'redis']);
$status = $resolver->resolve()->attempt('192.168.1.1', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
```

---

## ⚙️ مثال 2️⃣ (تكامل مع Slim Framework)

إضافة Middleware للتحكم في معدل الطلب:

```php
$app->add(new RateLimitHeadersMiddleware(
    $limiter,
    RateLimitActionEnum::LOGIN,
    PlatformEnum::WEB
));
```

---

## 🌍 مثال 3️⃣ (واجهة API بلغة JSON)

```php
try {
    $status = $limiter->attempt($key, RateLimitActionEnum::API_CALL, PlatformEnum::API);
    echo json_encode(['remaining' => $status->remaining]);
} catch (TooManyRequestsException $e) {
    http_response_code(429);
    echo json_encode(['retry_after' => $status->retryAfter ?? 60]);
}
```

---

## 🧠 مثال 4️⃣ (التأخير التصاعدي Exponential Backoff)

```php
try {
    $status = $limiter->attempt('192.168.1.5', RateLimitActionEnum::LOGIN, PlatformEnum::WEB);
} catch (TooManyRequestsException $e) {
    echo "⛔ انتظر {$status->backoffSeconds} ثانية قبل المحاولة التالية";
}
```

---

## ⚙️ إعدادات البيئة (Environment Variables)

تتحكم هذه المتغيرات في سلوك **الحد العام** و**التأخير التصاعدي**
وتُستخدم على مستوى النظام أو داخل ملفات `.env`.

| المتغير              | الشرح                                                                | المثال               | النوع              |
|----------------------|----------------------------------------------------------------------|----------------------|--------------------|
| `GLOBAL_RATE_LIMIT`  | الحد الأقصى للطلبات المسموح بها من نفس الـ IP خلال فترة زمنية محددة. | `5`                  | عدد صحيح           |
| `GLOBAL_RATE_WINDOW` | مدة نافذة القياس بالثواني (بعدها يتم تصفير العدّاد).                 | `60` (دقيقة واحدة)   | عدد صحيح           |
| `BACKOFF_BASE`       | الأساس الرياضي للتأخير التصاعدي.                                     | `2` → 2، 4، 8، 16... | رقم (float أو int) |
| `BACKOFF_MAX`        | الحد الأقصى لمدة الانتظار بالثواني.                                  | `3600` (ساعة واحدة)  | عدد صحيح           |

📘 **المعادلة الرياضية:**

```
backoff_seconds = min( BACKOFF_BASE ** violation_count , BACKOFF_MAX )
```

### 🔍 مثال على ملف `.env`

```env
GLOBAL_RATE_LIMIT=5
GLOBAL_RATE_WINDOW=60
BACKOFF_BASE=2
BACKOFF_MAX=3600
```

---

### 💡 نصائح

* استخدم قيمًا منخفضة مثل `5` طلبات في الدقيقة لتسجيل الدخول أو OTP.
* استخدم قيمًا أعلى للـ APIs العامة.
* `BACKOFF_BASE=2` يعطي سلوكًا تصاعديًا متوازنًا.
* تأكد من إرسال ترويسة `Retry-After` عند الرد برمز الحالة **429**.

---

### 📈 مثال عملي للتأخير التصاعدي

| عدد مرات التجاوز | مدة الانتظار (ثانية)         |
|------------------|------------------------------|
| 1                | 2                            |
| 2                | 4                            |
| 3                | 8                            |
| 4                | 16                           |
| 5                | 32                           |
| ...              | حتى الوصول إلى `BACKOFF_MAX` |

---

## 📦 التبعيات (Composer Dependencies)

لتشغيل المكتبة بشكل كامل:

```bash
composer require psr/http-message psr/http-server-middleware psr/http-server-handler
```

لدمجها مع **Slim Framework**:

```bash
composer require slim/slim
```

---

## 🪪 الترخيص (License)

**[MIT license](LICENSE)** © [Maatify.dev](https://www.maatify.dev)

مسموح بالاستخدام والتعديل والتوزيع مع ذكر المصدر.

---

## 🧱 المطورون والاعتمادات

**المطور:** [Maatify.dev](https://www.maatify.dev)

**المسؤول:** محمد عبدالعليم

**المشروع:** maatify:rate-limiter

---

> ✨ *تمت ترجمة هذا الملف رسميًا لتوفير توثيق عربي متكامل.*
> 🔗 [النسخة الأصلية بالإنجليزية](https://github.com/maatify/rate-limiter/blob/main/README.md)


---

