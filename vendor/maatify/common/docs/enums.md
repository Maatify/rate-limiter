# 📘 Enums & Constants Reference — maatify:common

This document provides an overview of all **Enums** and **Constants** defined in the `maatify/common` library.
These foundational definitions ensure a consistent standard across all Maatify components.

---

## 🧩 Enums Overview

| Enum                   | Description                                     | Example Usage                                   |
|------------------------|-------------------------------------------------|-------------------------------------------------|
| **TextDirectionEnum**  | Defines text layout direction                   | `TextDirectionEnum::LTR` → `ltr`                |
| **MessageTypeEnum**    | Standard system message types                   | `MessageTypeEnum::SUCCESS` → `success`          |
| **ErrorCodeEnum**      | Global error identifiers used in exceptions     | `ErrorCodeEnum::NOT_FOUND` → `E_NOT_FOUND`      |
| **PlatformEnum**       | Defines request source (e.g., Web, API, Mobile) | `PlatformEnum::WEB` → `web`                     |
| **AppEnvironmentEnum** | Indicates the current app environment           | `AppEnvironmentEnum::PRODUCTION` → `production` |

---

## 🧠 EnumHelper
A utility class that provides consistent operations for handling any PHP Enum.

### Available Methods
| Method                                  | Description                       | Example                                      |
|-----------------------------------------|-----------------------------------|----------------------------------------------|
| `names(string $enumClass): array`       | Get all enum case names           | `EnumHelper::names(MessageTypeEnum::class)`  |
| `values(string $enumClass): array`      | Get all enum values               | `EnumHelper::values(MessageTypeEnum::class)` |
| `isValidValue(string $enumClass, string | int $value): bool`                | Validate if a value is part of enum          | `EnumHelper::isValidValue(TextDirectionEnum::class, 'ltr')` |
| `toArray(string $enumClass): array`     | Convert enum to associative array | `EnumHelper::toArray(ErrorCodeEnum::class)`  |

---

## 🧩 Traits
| Trait                         | Purpose                                        | Example                                               |
|-------------------------------|------------------------------------------------|-------------------------------------------------------|
| **EnumJsonSerializableTrait** | Enables automatic JSON serialization for Enums | `json_encode(MessageTypeEnum::SUCCESS)` → `'success'` |

---

## ⚙️ Constants Overview

| Class             | Constant                                                       | Description                              |
|-------------------|----------------------------------------------------------------|------------------------------------------|
| **CommonPaths**   | `LOG_PATH`, `CACHE_PATH`, `TEMP_PATH`, `CONFIG_PATH`           | Defines core system directories          |
| **CommonLimits**  | `MAX_PAGE_SIZE`, `MIN_PASSWORD_LENGTH`, `TOKEN_EXPIRY_SECONDS` | Standard application limits              |
| **CommonHeaders** | `X_REQUEST_ID`, `X_API_VERSION`                                | Default API header keys                  |
| **Defaults**      | `DEFAULT_TIMEZONE`, `DEFAULT_LOCALE`, `DEFAULT_CHARSET`        | System-wide default configuration values |

---

## 🧪 Example Usage
```php
use Maatify\Common\Enums\MessageTypeEnum;
use Maatify\Common\Enums\EnumHelper;
use Maatify\Common\Constants\Defaults;

// Access enum value
echo MessageTypeEnum::ERROR->value; // 'error'

// Get all values
print_r(EnumHelper::values(MessageTypeEnum::class));

// Validate an enum value
if (EnumHelper::isValidValue(MessageTypeEnum::class, 'success')) {
    echo 'Valid message type';
}

// Use constants
echo Defaults::DEFAULT_TIMEZONE; // 'Africa/Cairo'
```

---

## 📄 Notes
- All enums implement PSR-12 and are namespaced under `Maatify\Common\Enums`.
- All constants are namespaced under `Maatify\Common\Constants`.
- Enums and constants are tested via PHPUnit under `/tests/Enums/`.

---

✅ **Last Updated:** 2025-11-10
🧩 **Maintained by:** Maatify.dev
