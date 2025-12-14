# 🧾 CHANGELOG — Maatify PSR-Logger

All notable changes to this project will be documented in this file.
This project follows **[Semantic Versioning (SemVer)](https://semver.org/)**.

---

## 🚀 v1.0.2 — 2025-11-10

### 🧩 Feature: Add StaticLoggerTrait for Static PSR-3 Logging Support

#### ✨ Added

* Introduced **`StaticLoggerTrait`** to provide static classes with direct access to PSR-3 loggers.
  Ideal for use in `Bootstrap`, utility, or facade-style classes.
* Added **comprehensive PHPUnit coverage**:

    * `tests/Mocks/MockStaticLogger.php` — reusable mock class for trait testing.
    * `tests/Unit/StaticLoggerTraitTest.php` — validates PSR compliance, context isolation, and stability.
* Ensures parity with **`LoggerContextTrait`** for instance-based logging consistency.

#### 🔧 Improved

* Unified documentation format and metadata headers across all trait files.
* Enhanced consistency between static and instance-based logging workflows.

#### 🧪 QA & Testing

* Verified with PHP 8.4 and Maatify Common v1.0.1.
* 100% test coverage maintained across `traits/` module.

---

## 🏁 v1.0.1 — 2025-11-10

### ⚙️ Enhancement: Return LoggerInterface from initLogger() for Direct Use

#### ✨ Added

* `LoggerContextTrait::initLogger()` now returns the created `LoggerInterface` instance.
* Allows direct inline usage:

  ```php
  $logger = $this->initLogger('services/payment');
  $logger->debug('Inline logger usage');
  ```

#### 🔧 Improved

* Enhanced `LoggerContextTrait` documentation and examples.
* Fully backward compatible with all previous usage patterns.
* Verified compatibility with PHP 8.4 and Maatify Common v1.0.1.

---

## 🚀 v1.0.0 — 2025-11-09

### 🧩 Initial Stable Release

#### ✅ Core Features

* PSR-3 compliant logging foundation built on **Monolog**.
* `LoggerFactory` for unified contextual logger creation.
* `LoggerContextTrait` for auto-injected class-based logging.
* Hierarchical file structure:

  ```
  storage/logs/YYYY/MM/DD/HH/context.log
  ```
* Supports context-based names (e.g., `api/auth`, `services/payment`).
* Fully tested on PHP 8.4.

#### 🧪 QA & Testing

* 100% PHPUnit coverage across all logger factory and trait components.
* Verified cross-library integration with `maatify/common` and `maatify/bootstrap`.

---

**MIT License** © [Maatify.dev](https://www.maatify.dev)
Maintained by **Mohamed Abdulalim (@megyptm)**

---