# 🧩 Phase 4.1 – Continuous Integration (Docker + GitHub Actions)

[![Maatify Rate Limiter](https://img.shields.io/badge/Maatify-Rate--Limiter-blue?style=for-the-badge)](https://github.com/Maatify/rate-limiter)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

## 📘 Overview

This phase introduces a **fully automated Continuous Integration (CI)** environment for the **Maatify Rate Limiter** library.
It ensures that every commit and pull request is automatically tested across all critical dependencies
(**Redis 7**, **MySQL 8**, and **MongoDB 7**) within a Docker-based environment and reported directly in the GitHub Actions logs.

---

## 🎯 Objectives

* ✅ Migrate CI tests from host-based to **Docker Compose** environment.
* ✅ Enable full **service stack isolation** (Redis / MySQL / Mongo).
* ✅ Display **live PHPUnit output** directly in GitHub Actions logs (not hidden inside container logs).
* ✅ Automate `.env` injection and dependency caching.
* ✅ Provide consistent results for all contributors regardless of local setup.

---

## ⚙️ Technical Implementation

### 🧩 1. Docker Compose Integration

A dedicated file `docker-compose.ci.yml` defines all required services:

```yaml
services:
  php:
    build: .
    container_name: maatify-ci-php
    depends_on:
      - redis
      - mysql
      - mongo
    volumes:
      - .:/app
    working_dir: /app
    command: >
      bash -c "
        composer install --no-interaction --prefer-dist --no-progress &&
        composer dump-autoload -o &&
        vendor/bin/phpunit --configuration phpunit.xml --colors=always
      "

  redis:
    image: redis:7
    container_name: maatify-ci-redis
    ports: [ "6379:6379" ]

  mysql:
    image: mysql:8.0
    container_name: maatify-ci-mysql
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: maatify_test
    ports: [ "3306:3306" ]

  mongo:
    image: mongo:7
    container_name: maatify-ci-mongo
    ports: [ "27017:27017" ]
```

---

### ⚙️ 2. GitHub Actions Workflow

File: `.github/workflows/ci.yml`

```yaml
name: 🚀 CI – Maatify Rate Limiter

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  test:
    name: 🧪 Run PHPUnit Tests
    runs-on: ubuntu-latest

    steps:
      - name: 📦 Checkout Repository
        uses: actions/checkout@v4

      - name: 🧰 Cache Composer
        uses: actions/cache@v4
        with:
          path: ~/.composer/cache
          key: ${{ runner.os }}-composer-${{ hashFiles('**/composer.lock') }}
          restore-keys: |
            ${{ runner.os }}-composer-

      - name: ⚙️ Build Docker environment
        run: |
          echo "🏗️ Building CI Docker environment..."
          docker compose -f docker-compose.ci.yml build

      - name: 🧾 Prepare .env configuration
        run: |
          echo "REDIS_HOST=redis" >> .env
          echo "REDIS_PORT=6379" >> .env
          echo "MYSQL_DSN=mysql:host=mysql;dbname=maatify_test" >> .env
          echo "MYSQL_USER=root" >> .env
          echo "MYSQL_PASS=root" >> .env
          echo "MONGO_URI=mongodb://mongo:27017" >> .env
          echo "MONGO_DB=maatify_test" >> .env

      - name: 🧪 Run PHPUnit Tests (live)
        run: |
          echo "🚀 Starting full integration test environment..."
          docker compose -f docker-compose.ci.yml run --rm php

      - name: 📤 Upload Test Results
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: 🧪 phpunit-results
          path: tests/_output/
```

---

## 🧠 Key Features

| Feature                            | Description                                                |
|------------------------------------|------------------------------------------------------------|
| 🐳 **Docker Compose Isolation**    | All services run in clean, reproducible containers.        |
| 🧪 **Live PHPUnit Output**         | Real-time test logs appear in GitHub Actions console.      |
| ⚡ **Composer Caching**             | Speeds up dependency installation across runs.             |
| 📄 **.env Auto-Generation**        | Injects all necessary environment variables automatically. |
| 📤 **Artifact Export**             | Optional upload of test logs and reports.                  |
| 🔄 **Cross-Service Health Checks** | Ensures Redis/MySQL/Mongo are ready before tests.          |

---

## 🧱 Folder Structure (after Phase 4.1)

```
maatify-rate-limiter/
│
├── .github/
│   └── workflows/
│       └── ci.yml
├── docker-compose.ci.yml
├── .env.example
├── composer.json
├── tests/
│   ├── MiddlewareTest.php
│   └── _output/
├── src/
│   └── Resolver/
│       └── RateLimiterResolver.php
└── docs/
    └── phases/
        ├── README.phase4.1.md   ← (this file)
```

---

## ✅ Outcome

* CI now **builds**, **tests**, and **reports** automatically per commit.
* Test logs appear **live in GitHub Actions console** instead of being hidden inside Docker logs.
* Phase 4.1 officially completes the **Continuous Integration foundation** of the Maatify Rate Limiter project.

---

## 🧩 Version

```
1.0.0-alpha-phase4.1
```
