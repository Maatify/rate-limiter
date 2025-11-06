# Maatify Rate Limiter

A PSR-compliant Rate Limiter library supporting Redis, MongoDB, and MySQL.

<!-- PHASE_STATUS_START -->
## ✅ Completed Phases
- [x] Phase 1 – Environment Setup (Local)
<!-- PHASE_STATUS_END -->

## ⚙️ Local Setup

```bash
composer install
cp .env.example .env
````

Then edit `.env` to match your local database configuration.

## 🧠 Description

The Maatify Rate Limiter provides a unified abstraction for distributed rate limiting
with smart backoff algorithms and multiple storage backends.

---
## 📂 Project Structure

```
maatify-rate-limiter/
│
├── .env.example
├── composer.json
├── .github/
│   └── workflows/
│       └── ci.yml
├── src/
│   └── (empty)
├── tests/
│   └── bootstrap.php
├── docs/
│   └── phases/
│       └── README.phase1.md
├── README.md
├── CHANGELOG.md
└── VERSION

```


---




