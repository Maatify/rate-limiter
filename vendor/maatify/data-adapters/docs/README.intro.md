# 📘 **Maatify Data-Adapters — Clear Intro Guide (English Version)**

![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

# 📦 **Maatify Data-Adapters**

A unified, clean, and consistent PHP connectivity layer for MySQL, Redis, and MongoDB — designed for all Maatify ecosystem projects.

This document explains the library **in a simple and friendly way**, suitable for any new developer.

---

# 🚀 What is this library?

`maatify/data-adapters` is a PHP package responsible for providing a **standardized, safe, and reusable connection layer** for:

* **MySQL** (PDO + Doctrine DBAL)
* **Redis** (phpredis + predis fallback)
* **MongoDB**

Instead of writing repeated connection logic in every project,
**this library centralizes all database connectivity in one place.**

---

# 🧩 How does it work?

The library consists of three main components:

---

## 1️⃣ EnvironmentConfig

Handles reading environment variables.

### Key rules:

* It **does NOT load `.env` files**.
* The **host project** loads `.env`.
* It simply reads values from `$_ENV`.
* Supports MySQL profile-style variables:

```
MYSQL_MAIN_HOST
MYSQL_LOGS_HOST
MYSQL_ANALYTICS_HOST
```

---

## 2️⃣ DatabaseResolver

This is the **core brain** of the library.

It selects the correct adapter based on input:

```php
$resolver->resolve("mysql.main");
$resolver->resolve("mysql.logs");
$resolver->resolve("redis");
$resolver->resolve("mongo");
```

### Responsibilities:

* Instantiate the correct adapter
* Choose the correct driver (PDO / DBAL)
* Choose Redis driver (phpredis / predis)
* Handle MySQL multi-profile configuration

---

## 3️⃣ Adapters (Drivers)

Each backend system has its own adapter:

### ✔️ MySQL

* `MySQLAdapter` (PDO)
* `MySQLDbalAdapter` (Doctrine DBAL)

### ✔️ Redis

* `RedisAdapter` (phpredis)
* `PredisAdapter` (fallback)

### ✔️ Mongo

* `MongoAdapter`

---

# 🔥 Development Methodology (Phases)

The entire project is built using a **Phase-based system**.
Each Phase includes:

* A clear goal
* A list of tasks
* Actual file changes
* PHPUnit tests
* Documentation under `docs/phases/`
* Updates to README and CHANGELOG

This creates a clean, maintainable, and transparent development workflow.

---

# 📊 Project Status Overview

## ✔️ Version 1.0.0 (Completed)

Includes:

* Full adapter implementations
* Diagnostics & health checks
* Logging & metrics
* Integration tests
* Detailed documentation
* Cleanup and stabilization
* Removal of outdated fallback systems

## ✔️ Version 1.1.0 (In Progress)

### Phase 10 — Multi-Profile MySQL

Adds support for:

```
mysql.main
mysql.logs
mysql.analytics
```

Each profile loads its own environment variables.

### Phase 11 — (Optional / likely cancelled)

Dynamic registry via JSON/YAML.

---

# ❌ What the library does NOT do

* It does **not load `.env`**
* It does **not retry connections**
* It does **not auto-reconnect**
* It does **not manage fallback queues or recovery**
  *(these were removed in v1.1.0)*
* It does **not make environmental decisions automatically**
  — the host project controls everything

---

# 🛠️ Usage Example

```php
$config = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($config);

// Main database
$db = $resolver->resolve("mysql.main");
$db->connect();

// Logs database
$logs = $resolver->resolve("mysql.logs");
$logs->connect();

// Redis
$redis = $resolver->resolve("redis");
$redis->connect();
```

---

# 🎯 Who should read this?

This guide is made for:

* New developers joining the project
* Developers integrating the library into their own applications
* Anyone starting a new Phase
* Anyone trying to understand the project architecture in 3 minutes

---

# 🔑 Core Principles

* **No magic** — everything is explicit
* **Environment is controlled by the host project**
* **PSR-12 + strict typing everywhere**
* **Resolver is always the entry point**
* **Documentation lives in `docs/phases/`**
* **Adapters remain stable and predictable**

---

# ✨ Ready for inclusion

This Markdown file is clean, documented, and perfect for:

* `docs/README.intro.md`
* Pull Requests
* Developer onboarding
* External documentation

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
