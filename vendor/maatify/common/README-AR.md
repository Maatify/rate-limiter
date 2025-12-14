![**Maatify.dev**](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

# 📦 maatify/common

[![Version](https://img.shields.io/packagist/v/maatify/common?label=Version&color=4C1)](https://packagist.org/packages/maatify/common)
[![PHP](https://img.shields.io/packagist/php-v/maatify/common?label=PHP&color=777BB3)](https://packagist.org/packages/maatify/common)
[![Build](https://github.com/Maatify/common/actions/workflows/ci.yml/badge.svg?label=Build&color=brightgreen)](https://github.com/Maatify/common/actions/workflows/ci.yml)
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/common?label=Monthly%20Downloads&color=00A8E8)](https://packagist.org/packages/maatify/common)
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/common?label=Total%20Downloads&color=2AA)](https://packagist.org/packages/maatify/common)
[![Stars](https://img.shields.io/github/stars/Maatify/common?label=Stars&color=FFD43B)](https://github.com/Maatify/common/stargazers)
[![License](https://img.shields.io/github/license/Maatify/common?label=License&color=blueviolet)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Stable-success?style=flat-square)]()
[![Code Quality](https://img.shields.io/codefactor/grade/github/Maatify/common/main)](https://www.codefactor.io/repository/github/Maatify/common)

---

🏁 **الإصدار المستقر v1.0.0** — المكتبة الأساسية الجوهرية لمنظومة **Maatify.dev**، والتي توفر أدوات قياسية مثل DTOs، التحقق (Validation)، التعقيم (Sanitization)، التاريخ والوقت (Date/Time)، أنظمة القفل (Locking)، وأدوات النصوص (Text Utilities) لجميع الوحدات الخلفية (Backend Modules).

> 📦 هذا هو أول إصدار رسمي مستقر **(v1.0.0)** من مكتبة `maatify/common`، الصادر بتاريخ **10 نوفمبر 2025**.

> 🔗 [English Version 🇬🇧](./README.md)

---


## 🧭 معلومات الإصدار

| المفتاح                | القيمة                      |
|------------------------|-----------------------------|
| الإصدار                | **1.0.0 مستقر (Stable)**    |
| تاريخ الإصدار          | 2025-11-10                  |
| متطلبات PHP            | ≥ 8.1                       |
| الترخيص                | MIT                         |
| نسبة التغطية           | 98٪                         |
| عدد الاختبارات الناجحة | 66 اختبار (150 Assertion)   |

---

## 🧩 نظرة عامة

توفّر هذه المكتبة مكوّنات أساسية قابلة لإعادة الاستخدام **(Reusable Building Blocks)**
ومستقلة عن أي إطار عمل **(Framework-Agnostic)**،
تشمل: **DTOs، Helpers، Traits، Enums، وValidators**،
وتُستخدم عبر جميع مكتبات منظومة **Maatify** مثل:

`maatify/mongo-activity`,
`maatify/psr-logger`,
وغيرها من المكتبات التابعة لنظام **Maatify.dev**.

---
## 📘 التوثيق وملفات الإصدار

| الملف                                                           | الوصف                                      |
|-----------------------------------------------------------------|--------------------------------------------|
| [`/docs/README.full.md`](docs/README.full.md)                   | التوثيق الكامل الموحّد لجميع المراحل (1–8) |
| [`/docs/enums.md`](docs/enums.md)                               | مرجع تفصيلي لـ Enums و Constants           |
| [`/docs/phases/README.phase7.md`](docs/phases/README.phase7.md) | تفصيل المرحلة السابعة وملاحظات EnumHelper  |
| [`CHANGELOG.md`](CHANGELOG.md)                                  | السجل الكامل لتاريخ الإصدارات              |
| [`CONTRIBUTING.md`](CONTRIBUTING.md)                            | إرشادات المساهمة في المشروع                |
| [`VERSION`](VERSION)                                            | رقم الإصدار الحالي                         |

---

## 🧱 **الوحدات الأساسية (Core Modules):**

* 🧮 **مساعدو التصفح (Pagination Helpers)** — `PaginationHelper`, `PaginationDTO`, `PaginationResultDTO`
  هيكل موحّد للتقسيم (Pagination) في استجابات الـ API واستعلامات MySQL.

* 🔐 **نظام القفل (Lock System)** — `FileLockManager`, `RedisLockManager`, `HybridLockManager`
  إدارة آمنة لتنفيذ المهام المجدولة (Cron Jobs) والمهام الموزعة (Distributed Tasks) وعمال الطوابير (Queue Workers).

* 🧼 **تنظيف البيانات (Security Sanitization)** — `InputSanitizer`, `SanitizesInputTrait`
  تنظيف آمن لمدخلات المستخدم ومنع الثغرات باستخدام تكامل داخلي مع مكتبة `HTMLPurifier`.

* 🧠 **الخصائص الأساسية (Core Traits)** — `SingletonTrait`, `SanitizesInputTrait`
  خصائص قابلة لإعادة الاستخدام لأنماط التصميم الأساسية مثل Singleton ومعالجة المدخلات الآمنة.

* ✨ **أدوات النصوص والقوالب (Text & Placeholder Utilities)** — `TextFormatter`, `PlaceholderRenderer`, `RegexHelper`, `SecureCompare`
  أدوات قوية لتنسيق النصوص، واستبدال القوالب النصية (Placeholders)، والمقارنة الآمنة بين السلاسل النصية.

* 🕒 **أدوات التاريخ والوقت (Date & Time Utilities)** — `DateFormatter`, `DateHelper`
  حساب الفروقات الزمنية بطريقة بشرية (Humanized) وتحويل المناطق الزمنية وعرض التاريخ بصيغة محلية (EN/AR/FR).

* 🧩 **أدوات التحقق والتنقية (Validation & Filtering Tools)** — `Validator`, `Filter`, `ArrayHelper`
  التحقق من صحة البريد الإلكتروني / الروابط / UUID / Slug، واكتشاف نوع المدخلات، وتنظيف المصفوفات المتقدمة.

* ⚙️ **توحيد التعدادات والثوابت (Enums & Constants Standardization)** —
  `TextDirectionEnum`, `MessageTypeEnum`, `ErrorCodeEnum`, `PlatformEnum`, `AppEnvironmentEnum`,
  `CommonPaths`, `CommonLimits`, `CommonHeaders`, `Defaults`, `EnumHelper`
  تعريفات مركزية للتعدادات والثوابت تضمن التوحيد القياسي، وإعادة الاستخدام، واتساق الإعدادات بين جميع مكتبات Maatify.

---
## ⚙️ التثبيت (Installation)

لتثبيت المكتبة عبر Composer:

```bash
composer require maatify/common
````

---

## 📦 التبعيات (Dependencies)

تعتمد هذه المكتبة بشكل مباشر على المكتبات التالية:

| المكتبة (Dependency)    | الغرض (Purpose)                                          | الرابط (Link)                                                            |
|-------------------------|----------------------------------------------------------|--------------------------------------------------------------------------|
| **ezyang/htmlpurifier** | محرّك آمن لتنقية HTML ومنع ثغرات XSS                     | [github.com/ezyang/htmlpurifier](https://github.com/ezyang/htmlpurifier) |
| **psr/log**             | واجهة تسجيل قياسية (PSR-3)                               | [php-fig.org/psr/psr-3](https://www.php-fig.org/psr/psr-3/)              |
| **phpunit/phpunit**     | إطار عمل لاختبارات الوحدات (للاستخدام أثناء التطوير فقط) | [phpunit.de](https://phpunit.de)                                         |

> تقوم مكتبة `maatify/common` بدمج هذه المكتبات مفتوحة المصدر لتوفير أساس موحّد وآمن
> يُستخدم في جميع مكوّنات منظومة **Maatify** الأخرى.

---

> 🧠 **ملاحظة:**
> تقوم `maatify/common` تلقائيًا بتهيئة مكتبة **HTMLPurifier**
> لاستخدام مجلد تخزين داخلي في المسار التالي:
>
> ```
> storage/purifier_cache
> ```
>
> وذلك لتحسين الأداء وتسريع عملية التنقية في الاستدعاءات اللاحقة
> دون الحاجة إلى أي إعداد يدوي.
>
> إذا كنت ترغب في تغيير مسار ذاكرة التخزين المؤقت (Cache Path)،
> يمكنك ضبط متغير البيئة التالي:
>
> ```bash
> HTMLPURIFIER_CACHE_PATH=/path/to/custom/cache
> ```
>
> أو تعديله برمجيًا عبر الشيفرة:
>
> ```php
> $config->set('Cache.SerializerPath', '/custom/cache/path');
> ```

---

## 🧠 SingletonTrait

تنفيذ نظيف ومتوافق مع معايير **PSR** لنمط التصميم **Singleton**،
يُستخدم لإدارة الأصناف (Classes) التي يجب أن تمتلك نسخة واحدة فقط (Single Instance) بطريقة آمنة ومنظمة.

---

### 🔹 مثال على الاستخدام (Example Usage)

```php
use Maatify\Common\Traits\SingletonTrait;

final class ConfigManager
{
    use SingletonTrait;

    public function get(string $key): ?string
    {
        return $_ENV[$key] ?? null;
    }
}

// ✅ دائمًا تُعيد نفس النسخة من الكائن
$config = ConfigManager::obj();

// ♻️ إعادة التهيئة (للاختبارات)
ConfigManager::reset();
````

---

### ✅ المميزات (Features)

* يمنع الإنشاء المباشر للكائنات (Direct Construction)، وكذلك النسخ (Cloning) أو إلغاء التسلسل (Unserialization).
* يوفّر الدالة الثابتة `obj()` للوصول إلى النسخة العامة (Global Instance).
* يتضمّن الدالة `reset()` لإعادة التهيئة أثناء الاختبارات أو إعادة التشغيل.

---

## 📚 مثال على استخدام التصفح (Pagination Example Usage)

---

### 🔹 تقسيم البيانات داخل مصفوفة (Paginate Array Data)

```php
use Maatify\Common\Pagination\Helpers\PaginationHelper;

$items = range(1, 100);

$result = PaginationHelper::paginate($items, page: 2, perPage: 10);

print_r($result);
````

**الناتج:**

```php
[
    'data' => [11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
    'pagination' => Maatify\Common\DTO\PaginationDTO {
        page: 2,
        perPage: 10,
        total: 100,
        totalPages: 10,
        hasNext: true,
        hasPrev: true
    }
]
```

---

### 🔹 التعامل مع الكائن `PaginationDTO`

```php
use Maatify\Common\Pagination\DTO\PaginationDTO;

$pagination = new PaginationDTO(
    page: 1,
    perPage: 25,
    total: 200,
    totalPages: 8,
    hasNext: true,
    hasPrev: false
);

print_r($pagination->toArray());
```

🧩 هذا المثال يوضّح كيفية استخدام الكائن `PaginationDTO`
لتمثيل معلومات التصفح (Pagination Metadata) بشكل منسّق ومنفصل عن البيانات الأساسية،
بما في ذلك رقم الصفحة، عدد العناصر في الصفحة، العدد الإجمالي، وعدد الصفحات الكلي.

---

## 🔐 نظام القفل (Lock System)

أدوات قفل متقدّمة تهدف إلى منع تنفيذ العمليات المتزامنة في **مهام الـ Cron** أو **عمّال الطوابير (Queue Workers)**
أو أي عمليات حساسة داخل الـ API.

---

### 🔹 أنواع مديري القفل المتاحة (Available Managers)

| الفئة (Class)       | النوع (Type) | الوصف (Description)                                                                  |
|---------------------|--------------|--------------------------------------------------------------------------------------|
| `FileLockManager`   | Local        | قفل يعتمد على الملفات ويُخزَّن في مجلد `/tmp` أو أي مسار آخر.                        |
| `RedisLockManager`  | Distributed  | يستخدم Redis أو Predis لإنشاء أقفال موزّعة وآمنة عبر الشبكة.                         |
| `HybridLockManager` | Smart        | يختار Redis تلقائيًا إن وُجد، وإلا يعود لاستخدام القفل القائم على الملفات.           |
| `LockCleaner`       | Utility      | يقوم بحذف ملفات الأقفال القديمة (`.lock`) بعد انتهاء صلاحيتها.                       |
| `LockModeEnum`      | Enum         | يحدّد ما إذا كان القفل من نوع `EXECUTION` (غير مانع) أو `QUEUE` (ينتظر حتى التحرير). |

---

### 🧠 المثال 1 — قفل الملفات (File Lock)

```php
use Maatify\Common\Lock\FileLockManager;

$lock = new FileLockManager('/tmp/maatify/cron/report.lock', 600);

if (! $lock->acquire()) {
    exit("Another job is running.\n");
}

echo "Running safely...\n";

$lock->release();
````

🔸 في هذا المثال، يتم إنشاء قفل محلي لحماية تنفيذ مهمة مجدولة (Cron Job)
من التشغيل المتكرر في نفس الوقت.

---

### ⚙️ المثال 2 — قفل Redis (Redis Lock)

```php
use Maatify\Common\Lock\RedisLockManager;

$lock = new RedisLockManager('cleanup_task', ttl: 600);

if ($lock->acquire()) {
    echo "Cleaning...\n";
    $lock->release();
}
```

✅ يعمل هذا القفل تلقائيًا مع كل من `phpredis` و `predis`.
وإذا تعطل خادم Redis، يتم تسجيل الخطأ تلقائيًا عبر مكتبة `maatify/psr-logger`.

---

### 🚀 المثال 3 — القفل الهجين (Hybrid Lock) — **الموصى به**

```php
use Maatify\Common\Lock\HybridLockManager;
use Maatify\Common\Lock\LockModeEnum;

$lock = new HybridLockManager(
    key: 'daily_summary',
    mode: LockModeEnum::QUEUE,
    ttl: 600
);

$lock->run(function () {
    echo "Generating daily summary...\n";
});
```

🔹 يستخدم هذا الوضع Redis تلقائيًا إذا كان متاحًا،
وإلا ينتقل إلى القفل القائم على الملفات لضمان استمرارية التنفيذ دون فشل.

---

### 🧹 المثال 4 — تنظيف الأقفال القديمة (Clean Old Locks)

```php
use Maatify\Common\Lock\LockCleaner;

LockCleaner::cleanOldLocks(sys_get_temp_dir() . '/maatify/locks', 900);
```

يقوم هذا الأمر بحذف جميع ملفات القفل القديمة التي تجاوزت فترة صلاحيتها المحدّدة (900 ثانية هنا).

---

### 🧾 ملاحظات (Notes)

* جميع عمليات القفل تُسجّل بالكامل عبر مكتبة `maatify/psr-logger`.
* مدة انتهاء القفل الافتراضية **(TTL)** هي **300 ثانية (5 دقائق)**.
* في وضع **Hybrid Queue**، يحاول القفل إعادة المحاولة كل **0.5 ثانية** حتى يصبح القفل متاحًا.

---

### 🗂 هيكل المجلد (Lock Module Directory)

```
src/Lock/
├── LockInterface.php
├── LockModeEnum.php
├── FileLockManager.php
├── RedisLockManager.php
├── HybridLockManager.php
└── LockCleaner.php
```

---

## 🕒 نظام قفل المهام المجدولة (Cron Lock System – Legacy Section)

يوفّر هذا القسم آلية قفل بسيطة لكنها قوية لمنع تنفيذ المهام المجدولة (Cron Jobs) بشكل متزامن في الوقت نفسه.

---

### **التطبيقات المتاحة (Available Implementations):**

* `FileCronLock` — قفل محلي خفيف الوزن مخصص للأنظمة ذات الخادم الواحد (Single-Host Environments).
* `RedisCronLock` — قفل موزّع يستخدم Redis أو Predis، ويُعطّل نفسه تلقائيًا إذا لم يكن Redis متاحًا.

---

### **الواجهة (Interface):**

```php
use Maatify\Common\Lock\LockInterface;
````

---

### **مثال على الاستخدام (Example):**

```php
use Maatify\Common\Lock\FileLockManager;

$lock = new FileLockManager('/var/locks/daily_job.lock', 300);

if (! $lock->acquire()) {
    exit("Cron already running...\n");
}

echo "Running job...\n";

// ... منطق تنفيذ المهمة ...

$lock->release();
```

✅ إذا كان Redis أو Predis مثبتًا في النظام، يمكنك استخدام:

```php
use Maatify\Common\Lock\RedisLockManager;

$lock = new RedisLockManager('daily_job');
if ($lock->acquire()) {
    // تنفيذ المهمة
    $lock->release();
}
```

🔸 يقوم إصدار Redis بتسجيل تحذير تلقائيًا (ويُعطّل نفسه بأمان) إذا لم يكن خادم Redis متاحًا.

---

## 🧼 تنظيف المدخلات (Input Sanitization)

استخدم الفئة `Maatify\Common\Security\InputSanitizer` لتنظيف أي مدخلات من المستخدم أو النظام بطريقة آمنة.

```php
use Maatify\Common\Security\InputSanitizer;

echo InputSanitizer::sanitize('<script>alert(1)</script>', 'output');
// الناتج: &lt;script&gt;alert(1)&lt;/script&gt;
````

🧩 تُعد هذه الأداة جزءًا من منظومة الأمن في مكتبة **maatify/common**
حيث تعمل على إزالة أو ترميز المحتوى الخطر (مثل وسوم JavaScript أو HTML الضارة)
مع الحفاظ على النصوص السليمة دون تغيير بنيتها.

---

## ✨ أدوات النصوص والقوالب (Text & Placeholder Utilities)

أدوات قوية لإدارة النصوص والتعامل مع السلاسل النصية بطريقة آمنة وقابلة لإعادة الاستخدام،
تُستخدم عبر جميع مكتبات **Maatify**.

---

### 🔹 PlaceholderRenderer

أداة آمنة لمعالجة واستبدال القوالب النصية المتداخلة داخل النصوص أو الرسائل (Templates).

```php
use Maatify\Common\Text\PlaceholderRenderer;

$template = 'Hello, {{user.name}} ({{user.email}})';
$data = ['user' => ['name' => 'Mohamed', 'email' => 'm@maatify.dev']];

echo PlaceholderRenderer::render($template, $data);
// الناتج: Hello, Mohamed (m@maatify.dev)
````

---

### 🔹 TextFormatter

تُستخدم لتنسيق النصوص وتوحيدها عبر المنصات المختلفة،
مثل إنشاء **Slug**، أو تحويل النص إلى **Title Case**، أو التطبيع **(Normalization)**.

```php
use Maatify\Common\Text\TextFormatter;

TextFormatter::slugify('Hello World!');      // hello-world
TextFormatter::normalize('ÄÖÜß Test');       // aeoeuess-test
TextFormatter::titleCase('maatify common');  // Maatify Common
```

---

### 🔹 RegexHelper

غلاف بسيط وفعّال لتسهيل التعامل مع التعابير النمطية (Regular Expressions).

```php
use Maatify\Common\Text\RegexHelper;

RegexHelper::replace('/\d+/', '#', 'Item123'); // الناتج: Item#
```

---

### 🔹 SecureCompare

مقارنة آمنة للسلاسل النصية لتجنّب هجمات التوقيت (Timing Attacks)،
وتُستخدم عادة للتحقق من الرموز أو التواقيع.

```php
use Maatify\Common\Text\SecureCompare;

if (SecureCompare::equals($provided, $stored)) {
    echo 'Tokens match safely.';
}
```

---

✅ مغطاة بالكامل باختبارات الوحدات (`tests/Text/*`)
✅ تدعم التحويل النصي عبر المنصات المختلفة (Cross-Platform Transliteration)
✅ تُستخدم داخليًا في مكتبات أخرى ضمن **Maatify** لتنسيق النصوص، واستبدال القوالب، والتحقق من التواقيع.

---

### 🗂 هيكل المجلد (Text Utilities Directory)

```
src/Text/
├── PlaceholderRenderer.php
├── TextFormatter.php
├── RegexHelper.php
└── SecureCompare.php
```

---

> 🔧 **معلومة مفيدة (Tip):**
> تُستخدم هذه الأدوات داخليًا في مكتبات
> `maatify/i18n`, `maatify/security`, و`maatify/queue-manager`
> لضمان توحيد تنسيق النصوص واستبدال القوالب والتحقق من الرموز (Token Validation).

---

## 🕒 **أدوات التاريخ والوقت (Date & Time Utilities)**

أدوات قابلة لإعادة الاستخدام لتنسيق التاريخ والوقت،
مع دعم التوطين (Localization) وحساب الفروقات البشرية (Humanized Difference).

```php
use Maatify\Common\Date\DateFormatter;
use Maatify\Common\Date\DateHelper;
use DateTime;
````

---

### 🔹 الفرق الزمني بطريقة بشرية (Humanize Difference)

تحويل الفرق بين توقيتين إلى صيغة طبيعية مفهومة للبشر:

```php
$a = new DateTime('2025-11-09 10:00:00');
$b = new DateTime('2025-11-09 09:00:00');

echo DateFormatter::humanizeDifference($a, $b, 'en'); // "1 hour(s) ago"
echo DateFormatter::humanizeDifference($a, $b, 'ar'); // "منذ 1 ساعة"
```

🧩 هذه الطريقة مفيدة لعرض الفروقات الزمنية بشكل مفهوم للمستخدم
(مثل: "منذ 3 دقائق"، "قبل ساعة"، "قبل يومين"، إلخ).

---

### 🔹 صيغة التاريخ المترجمة (Localized Date String)

تحويل كائن DateTime إلى تمثيل نصي مترجم وفقًا للغة والمنطقة الزمنية المحددة:

```php
$date = new DateTime('2025-11-09 12:00:00');
echo DateHelper::toLocalizedString($date, 'ar', 'Africa/Cairo'); // ٩ نوفمبر ٢٠٢٥، ٢:٠٠ م
echo DateHelper::toLocalizedString($date, 'en', 'America/New_York'); // November 9, 2025, 7:00 AM
```

✅ تدعم اللغات: **الإنجليزية (en)**، **العربية (ar)**، **الفرنسية (fr)**
✅ تتعامل تلقائيًا مع **تحويل المناطق الزمنية** وأسماء الأشهر والأيام حسب اللغة
✅ تعتمد على `IntlDateFormatter` لضمان دقة التوطين
✅ مغطاة بالكامل باختبارات الوحدة (`tests/Date/*`)

---

### 🗂 هيكل المجلد (Date Utilities Directory)

```
src/Date/
├── DateFormatter.php
└── DateHelper.php
```

---


## 🧩 **أدوات التحقق والتنقية (Validation & Filtering Utilities)**

أدوات قابلة لإعادة الاستخدام للتحقق من صحة البيانات وتنقيتها والتعامل مع المصفوفات،
لضمان مدخلات نظيفة ومتناسقة عبر جميع مشاريع **Maatify**.

```php
use Maatify\Common\Validation\Validator;
use Maatify\Common\Validation\Filter;
use Maatify\Common\Validation\ArrayHelper;
````

---

### 🔹 التحقق من القيم (Validation)

إجراء عمليات تحقق سريعة وموثوقة لأنواع مختلفة من المدخلات:

```php
Validator::email('user@maatify.dev');              // ✅ true
Validator::url('https://maatify.dev');             // ✅ true
Validator::ip('192.168.1.1');                      // ✅ true
Validator::uuid('123e4567-e89b-12d3-a456-426614174000'); // ✅ true
Validator::slug('maatify-core');                   // ✅ true
Validator::slugPath('en/gift-card/itunes-10-usd'); // ✅ true
```

---

### 🔹 التحقق العددي والمدى (Numeric & Range Validation)

```php
Validator::integer('42');           // ✅ true
Validator::float('3.14');           // ✅ true
Validator::between(5, 1, 10);       // ✅ true
Validator::phone('+201234567890');  // ✅ true
```

---

### 🔹 الكشف التلقائي عن نوع البيانات (Auto Type Detection)

مساعد ذكي لاكتشاف نوع المدخل تلقائيًا:

```php
Validator::detectType('test@maatify.dev');     // 'email'
Validator::detectType('maatify-core');         // 'slug'
Validator::detectType('en/gift-card/item');    // 'slug_path'
Validator::detectType('42');                   // 'integer'
Validator::detectType('3.14');                 // 'float'
Validator::detectType('unknown-data');         // null
```

✅ يميّز بين `slug` و `slug_path`
✅ مفيد جدًا للتحقق الديناميكي في واجهات الـ API أو لاكتشاف نوع الحقول تلقائيًا في النماذج

---

### 🔹 التنقية (Filtering)

تسهّل عملية تنظيف المصفوفات قبل التحقق أو الحفظ في قاعدة البيانات:

```php
$data = [
    'name' => '  Mohamed  ',
    'email' => ' ',
    'bio' => '<b>Hello</b>',
    'age' => null
];

$clean = Filter::sanitizeArray($data);

// الناتج:
[
    'name' => 'Mohamed',
    'bio'  => '<b>Hello</b>'
]
```

الأساليب المتاحة:

* `Filter::trimArray(array $data)`
* `Filter::removeEmptyValues(array $data)`
* `Filter::sanitizeArray(array $data)`

---

### 🔹 مساعد المصفوفات (Array Helper)

يوفّر أدوات أنيقة للتعامل مع المصفوفات الترابطية (Associative Arrays) بطريقة وظيفية:

```php
$data = [
    'user' => ['id' => 1, 'name' => 'Mohamed'],
    'meta' => ['role' => 'admin', 'active' => true]
];

ArrayHelper::flatten($data);
// ['user.id' => 1, 'user.name' => 'Mohamed', 'meta.role' => 'admin', 'meta.active' => true]

ArrayHelper::only($data, ['user.name']);
// ['user' => ['name' => 'Mohamed']]

ArrayHelper::except($data, ['meta']);
// ['user' => ['id' => 1, 'name' => 'Mohamed']]
```

✅ مغطاة بالكامل باختبارات الوحدة (`tests/Validation/*`)
✅ تحتوي على دعم مدمج لاكتشاف `slugPath` متعددة اللغات
✅ مثالية لتحضير بيانات الطلبات (Request Payloads) أو لتطبيع بيانات الـ DTOs

---

### 🗂 هيكل المجلد (Validation Utilities Directory)

```
src/Validation/
├── Validator.php
├── Filter.php
└── ArrayHelper.php
```

---

## ⚙️ توحيد التعدادات والثوابت (Enums & Constants Standardization)

نظام مركزي لإدارة **التعدادات (Enums)** و**الثوابت (Constants)** المشتركة بين جميع مكتبات **Maatify**،
مما يضمن تكوينًا موحدًا وسلوكًا متوقعًا وسهولة في الصيانة عبر كامل النظام.

---

### 🔹 TextDirectionEnum

يُعرّف اتجاه النص المستخدم في الواجهات (UI) والمنطق الخاص بالتوطين (Localization).

```php
use Maatify\Common\Enums\TextDirectionEnum;

echo TextDirectionEnum::LTR->value; // 'ltr'
````

---

### 🔹 MessageTypeEnum

أنواع الرسائل القياسية المستخدمة في استجابات الـ API والسجلات والتنبيهات.

```php
use Maatify\Common\Enums\MessageTypeEnum;

echo MessageTypeEnum::ERROR->value; // 'error'
```

---

### 🔹 ErrorCodeEnum

يوفر رموز أخطاء موحّدة ومعيارية تُستخدم عبر جميع وحدات **Maatify**.

```php
use Maatify\Common\Enums\ErrorCodeEnum;

throw new Exception('Invalid input', ErrorCodeEnum::INVALID_INPUT->value);
```

---

### 🔹 PlatformEnum & AppEnvironmentEnum

تعدادات مخصصة لتعريف بيئة التشغيل وسياق التنفيذ.

```php
use Maatify\Common\Enums\PlatformEnum;
use Maatify\Common\Enums\AppEnvironmentEnum;

echo PlatformEnum::WEB->value;              // 'web'
echo AppEnvironmentEnum::PRODUCTION->value; // 'production'
```

---

### 🔹 EnumHelper

فئة ذكية موحّدة لإدارة العمليات العامة على التعدادات،
مثل جلب الأسماء والقيم والتحقق من القيم الصالحة.

```php
use Maatify\Common\Enums\EnumHelper;
use Maatify\Common\Enums\MessageTypeEnum;

$names  = EnumHelper::names(MessageTypeEnum::class);
$values = EnumHelper::values(MessageTypeEnum::class);
$isValid = EnumHelper::isValidValue(MessageTypeEnum::class, 'success'); // true
```

---

### 🔹 EnumJsonSerializableTrait

توفر خاصية التسلسل التلقائي (Automatic JSON Serialization) لأي تعداد (Enum).

```php
use Maatify\Common\Enums\Traits\EnumJsonSerializableTrait;
use Maatify\Common\Enums\MessageTypeEnum;

echo json_encode(MessageTypeEnum::SUCCESS); // 'success'
```

---

### 🔹 فئات الثوابت (Constants Classes)

فئات منظَّمة تحتوي على ثوابت النظام العامة.

```php
use Maatify\Common\Constants\CommonPaths;
use Maatify\Common\Constants\Defaults;

echo CommonPaths::LOG_PATH;          // '/storage/logs'
echo Defaults::DEFAULT_TIMEZONE;     // 'Africa/Cairo'
```

---

✅ مغطاة بالكامل باختبارات الوحدة (`tests/Enums/*`)
✅ تم التحقق من ثبات واستقرار EnumHelper وTrait
✅ أسماء وقيم موحدة عبر جميع الوحدات في منظومة Maatify

---

### 🗂 هيكل المجلد (Enums & Constants Directory)

```
src/Enums/
├── TextDirectionEnum.php
├── MessageTypeEnum.php
├── ErrorCodeEnum.php
├── PlatformEnum.php
├── AppEnvironmentEnum.php
├── EnumHelper.php
└── Traits/
    └── EnumJsonSerializableTrait.php

src/Constants/
├── CommonPaths.php
├── CommonLimits.php
├── CommonHeaders.php
└── Defaults.php
```
---

## 🧩 المساعدات (Helpers)

### 🧱 TapHelper

أداة خفيفة وسلسة تُستخدم لتنفيذ دالة (callback) على قيمة أو كائن ثم إرجاع نفس القيمة بدون تغيير —
مفيدة جدًا لجعل عملية تهيئة الكائنات أكثر أناقة وتنظيمًا.

---

#### ⚙️ الكلاس
`Maatify\Common\Helpers\TapHelper`

#### ✅ المميزات
- تنفّذ دالة على كائن أو قيمة يتم تمريرها.
- تُرجع نفس القيمة (سواء كانت كائن، رقم، مصفوفة، إلخ...).
- مثالية لأسلوب البرمجة السلس (Fluent API).
- لا تغيّر القيمة الأصلية إلا إذا غيّرتها الدالة نفسها.

---

#### 🧠 مثال للاستخدام
```php
use Maatify\Common\Helpers\TapHelper;
use Maatify\DataAdapters\Adapters\MongoAdapter;

$config = new EnvironmentConfig(__DIR__ . '/../');

$mongo = TapHelper::tap(new MongoAdapter($config), fn($a) => $a->connect());

// $mongo الآن عبارة عن Adapter متصل فعليًا
$client = $mongo->getConnection();
````

---

#### 🧾 الفلسفة الوظيفية

`TapHelper` يتّبع أسلوبًا بسيطًا وواضحًا مستوحى من البرمجة الوظيفية (Functional Programming):

| المبدأ                               | الوصف                                                    |
|--------------------------------------|----------------------------------------------------------|
| 🧩 **العزل (Isolation)**             | يتم تنفيذ الدالة بمعزل عن السياق الخارجي ولا تُرجع قيمة. |
| 🔁 **الثبات (Immutability)**         | تُعاد القيمة الأصلية بدون تغيير.                         |
| 🧼 **الوضوح (Clarity)**              | يقلل من الأكواد المتكررة أثناء تهيئة الكائنات.           |
| 🧠 **قابلية الاختبار (Testability)** | سهل الفهم والاختبار (راجع `TapHelperTest`).              |

---

#### 🧪 اختبار الوحدة

`tests/Helpers/TapHelperTest.php`

يغطي:

* التحقق من إرجاع نفس الكائن.
* التأكد من تنفيذ الدالة بشكل صحيح.
* دعم القيم المختلفة (كائنات – Scalars – مصفوفات).

```bash
vendor/bin/phpunit --filter TapHelperTest
```

---

#### 🧱 المرجع البرمجي

```php
TapHelper::tap(mixed $value, callable $callback): mixed
```

> ينفّذ `$callback($value)` ثم يُرجع `$value`.

---

#### 🧩 الفوائد المعمارية داخل منظومة Maatify

| الجانب                                                | الفائدة                                                                                                |
|-------------------------------------------------------|--------------------------------------------------------------------------------------------------------|
| ♻️ **تهيئة سلسة (Fluent Initialization)**             | يتيح إنشاء adapters أو الخدمات بسطر واحد أنيق.                                                         |
| 🧠 **اتساق ضمن النظام (Ecosystem Consistency)**       | متوافق في الفلسفة مع أدوات مثل `PathHelper` و `EnumHelper` و `TimeHelper`.                             |
| 🧼 **تقليل التكرار (Reduced Boilerplate)**            | يستبدل عدة أسطر إعداد بسطر واحد واضح وسهل القراءة.                                                     |
| 🧩 **قابلية إعادة الاستخدام (Universal Reusability)** | يعمل بسلاسة مع جميع مكتبات Maatify (`bootstrap`, `data-adapters`, `rate-limiter`, `redis-cache`, ...). |

---

📘 **التوثيق الكامل:** [docs/enums.md](docs/enums.md)

---

## 🗂 هيكل المجلد (Directory Structure)

يمثّل هذا الهيكل التنظيم الكامل لمجلد `src/` داخل مكتبة **maatify/common**،
حيث تم تقسيم الوحدات (Modules) وفقًا لمجالات عملها لضمان الوضوح وسهولة الصيانة.


```
src/
├── Pagination/
│   ├── DTO/
│   │   └── PaginationDTO.php
│   └── Helpers/
│       ├── PaginationHelper.php
│       └── PaginationResultDTO.php
├── Lock/
│   ├── LockInterface.php
│   ├── LockModeEnum.php
│   ├── FileLockManager.php
│   ├── RedisLockManager.php
│   ├── HybridLockManager.php
│   └── LockCleaner.php
├── Security/
│   └── InputSanitizer.php
├── Traits/
│   ├── SingletonTrait.php
│   └── SanitizesInputTrait.php
├── Text/
│   ├── PlaceholderRenderer.php
│   ├── TextFormatter.php
│   ├── RegexHelper.php
│   └── SecureCompare.php
├── Date/
│   ├── DateFormatter.php
│   └── DateHelper.php
└── Validation/
    ├── Validator.php
    ├── Filter.php
    └── ArrayHelper.php
        Enums/
        ├── TextDirectionEnum.php
        ├── MessageTypeEnum.php
        ├── ErrorCodeEnum.php
        ├── PlatformEnum.php
        ├── AppEnvironmentEnum.php
        ├── EnumHelper.php
        └── Traits/
            └── EnumJsonSerializableTrait.php
```
---
📁 **ملاحظات حول البنية:**
- تم تقسيم كل وحدة (Module) في مجلد مستقل لسهولة التتبع والتطوير.
- ملفات الـ `DTO` تُستخدم لنقل البيانات (Data Transfer Objects).
- المجلد `Traits/` يحتوي على خصائص قابلة لإعادة الاستخدام.
- مجلد `Enums/` يضم جميع التعدادات القياسية الخاصة بالمكتبة.
- يعتمد هذا التنظيم على **PSR-4 Autoloading** المتوافق مع Composer.

---

## 📚 المبني عليه (Built Upon)

تقوم مكتبة `maatify/common` على مجموعة من الأسس مفتوحة المصدر الناضجة والمجرَّبة في بيئات الإنتاج،
التي تُعدّ حجر الأساس لعملها واستقرارها.

| المكتبة                                                           | الوصف                                         | الاستخدام داخل المشروع                                                                                      |
|-------------------------------------------------------------------|-----------------------------------------------|-------------------------------------------------------------------------------------------------------------|
| **[ezyang/htmlpurifier](https://github.com/ezyang/htmlpurifier)** | مكتبة فلترة HTML متوافقة مع المعايير القياسية | تُشغِّل فئة `InputSanitizer` لضمان إخراج HTML آمن ضد ثغرات XSS ويدعم الترميز الكامل لـ Unicode.             |
| **[psr/log](https://www.php-fig.org/psr/psr-3/)**                 | واجهة تسجيل معيارية (PSR-3 Logging Interface) | تمكّن من تسجيل الأحداث بطريقة موحدة عبر مكونات التنقية (Sanitization)، والقفل (Lock)، والتحقق (Validation). |
| **[phpunit/phpunit](https://phpunit.de)**                         | إطار عمل لاختبارات الوحدات في PHP             | يوفر نظام اختبار آلي متكامل مع سير عمل GitHub CI/CD لضمان جودة واستقرار المكتبة.                            |

---

> 🙏 **شكر كبير لمجتمع البرمجيات مفتوحة المصدر**
> على مساهماتهم القيمة التي جعلت منظومة **Maatify** أكثر أمانًا، واستقرارًا، وقابليةً للتوسّع. ❤️

---

رائع جدًا 👌
إليك ترجمة قسم **📊 جدول ملخص المراحل (Phase Summary Table)**
بصيغة **Markdown** دقيقة تحافظ على الجدول والتفاصيل كما هي:

---

## 📊 جدول ملخص المراحل (Phase Summary Table)

| المرحلة | العنوان                           | الحالة   | عدد الملفات     | الملاحظات                                                             |
|---------|-----------------------------------|----------|-----------------|-----------------------------------------------------------------------|
| 1       | وحدة التقسيم Pagination Module    | ✅ مكتملة | 3               | كائنات ووسائل المساعدة الخاصة بالتقسيم (Pagination DTOs & Helpers)    |
| 2       | نظام القفل Locking System         | ✅ مكتملة | 6               | مدراء القفل (File / Redis / Hybrid Managers)                          |
| 3       | الأمان وتنظيف المدخلات            | ✅ مكتملة | 3               | تنظيف المدخلات وتكامل HTMLPurifier                                    |
| 3b      | الخصائص الأساسية — نظام Singleton | ✅ مكتملة | 1               | تنفيذ نمط SingletonTrait                                              |
| 4       | أدوات النصوص والعناصر النائبة     | ✅ مكتملة | 8               | PlaceholderRenderer، TextFormatter، RegexHelper، SecureCompare        |
| 5       | أدوات التاريخ والوقت              | ✅ مكتملة | 4               | HumanizeDifference & Localized Date Formatting                        |
| 6       | أدوات التحقق والترشيح             | ✅ مكتملة | 3               | Validator، Filter، وArrayHelper مع اختبارات وحدة كاملة                |
| 7       | توحيد التعدادات والثوابت          | ✅ مكتملة | 10 + 5 اختبارات | Enums وConstants وEnumHelper وTrait مع التوثيق الكامل                 |
| 8       | الاختبار والإصدار                 | ✅ مكتملة | 6               | CHANGELOG.md وCONTRIBUTING.md وVERSION وREADME.full.md ونتائج التغطية |


---

📘 **ملاحظة:**
يُظهر هذا الجدول المراحل الزمنية لتطوير مكتبة **maatify/common**،
حيث تم إنجاز جميع الوحدات بنجاح وصولًا إلى الإصدار المستقر **v1.0.0**.

---
## ✅ نتائج الاختبارات المُتحقَّق منها (Verified Test Results)

> 🧪 **PHPUnit 10.5.58 — PHP 8.4.4**
> • عدد الاختبارات: **66**
> • عدد التأكيدات (Assertions): **150**
> • نسبة التغطية: **≈ 98%**
> • زمن التنفيذ: **0.076 ثانية**
> • استهلاك الذاكرة: **12 ميجابايت**
> • التحذيرات: **1** *(عدم توفر أداة قياس التغطية — يمكن تجاهله بأمان)*

---

📗 **تعليق:**
تم تنفيذ جميع اختبارات الوحدة بنجاح على بيئة **PHP 8.4.4** باستخدام **PHPUnit 10.5.58**،
مع تغطية شبه كاملة لكافة الوحدات، مما يؤكد استقرار المكتبة وجاهزيتها للإصدار الإنتاجي.

---

## 🧾 التحقق من الإصدار (Release Verification)

تم التحقق من جميع الملفات واعتمادها رسميًا ضمن **المرحلة الثامنة (Phase 8)** من الإصدار **v1.0.0 Stable**.

- ✅ `/docs/README.full.md` – دمج التوثيق الكامل لجميع المراحل.
- ✅ `/docs/enums.md` – مرجع التعدادات والثوابت (Enums & Constants Reference).
- ✅ `/docs/phases/README.phase7.md` – توثيق المرحلة السابعة وملاحظات EnumHelper.
- ✅ `CHANGELOG.md` – سجل التغييرات (تاريخ الإصدارات) تم تهيئته.
- ✅ `CONTRIBUTING.md` – إضافة دليل المساهمة للمطورين.
- ✅ `VERSION` – تأكيد الإصدار الحالي **1.0.0**.

---

📘 **ملاحظة:**
يمثل هذا القسم التحقق النهائي من اكتمال جميع الملفات،
وضمان توافقها مع المعايير الخاصة بمكتبة **maatify/common** قبل الإصدار المستقر.

---

## 🪪 الترخيص (License)

**[رخصة MIT](LICENSE)** © [Maatify.dev](https://www.maatify.dev)

يُسمح لك بحرية **استخدام** و**تعديل** و**توزيع** هذه المكتبة،
شريطة **الإشارة إلى المصدر** (Maatify.dev) في أي استخدام أو نشر مستقبلي.

---

📘 **معلومة:**
رخصة **MIT** تُعد من أكثر الرخص البرمجية مرونةً،
وتتيح استخدام الكود في المشاريع المفتوحة أو التجارية دون قيود مع الحفاظ على حقوق النشر الأصلية.


---

## 🚀 خطة الإصدار القادم (Next Version Plan — v1.1.0)

### ✨ التحسينات المخططة للإصدار القادم:
- ⚡ تحسين الأداء في أدوات النصوص والمصفوفات *(String & Array Helpers)*.
- 🌐 توسيع دعم التعدادات *(Enums)* ليشمل بيانات التوطين *(Localization Metadata)*.
- 🧩 تقديم واجهات **Common Cache Adapter** و **Metrics Interfaces**
  لتوحيد إدارة التخزين المؤقت وقياس الأداء في المكتبات المستقبلية.

---

📘 **ملاحظة:**
يهدف هذا الإصدار إلى تعزيز الكفاءة والأداء عبر المنظومة بالكامل،
مع تقديم طبقة قياسية موحدة لإدارة الكاش والمقاييس ضمن مشروع **Maatify Ecosystem**.


---
> 🔗 **التوثيق الكامل وملاحظات الإصدار:**
> راجع الملف [/docs/README.full.md](docs/README.full.md)
---

## 🧱 فريق التطوير والاعتماد (Authors & Credits)

تنتمي هذه المكتبة إلى **المنظومة الأساسية لـ Maatify.dev (Maatify.dev Core Ecosystem)**
وقد تم تصميمها وتطويرها تحت الإشراف التقني لـ:

**👤 Mohamed Abdulalim** — *Backend Lead & Technical Architect*

**المسؤول التقني للبنية التحتية الخلفية لمنظومة Maatify Backend Infrastructure**،
والمسؤول عن التصميم العام للمكتبات الأساسية وتوحيد المعايير التقنية
عبر جميع الوحدات الخلفية ضمن منظومة **Maatify**.
🔗 [www.Maatify.dev](https://www.maatify.dev) | ✉️ [mohamed@maatify.dev](mailto:mohamed@maatify.dev)

---

**🤝 المساهمون (Contributors):**
فريق **Maatify.dev Engineering Team** والمساهمون من مجتمع البرمجيات مفتوحة المصدر
الذين يساهمون باستمرار في تحسين واختبار وتوسيع قدرات هذه المكتبة
عبر العديد من مشاريع **Maatify**.

---

> 🧩 يمثل هذا المشروع جهداً هندسياً موحداً بقيادة **Mohamed Abdulalim**
> بهدف ضمان أن كل مكون من مكونات النظام الخلفي في Maatify
> يعتمد على أساسٍ موحّد، آمن، وسهل الصيانة.

---

<p align="center">
  <sub><span style="color:#777">بُنيَ ❤️ بواسطة <a href="https://www.maatify.dev">Maatify.dev</a> — منظومة موحَّدة لمكتبات PHP الحديثة</span></sub>
</p>

---