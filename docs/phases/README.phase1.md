# 🧩 Phase 1 – Environment Setup (Local Mode)

[![Maatify Rate Limiter](https://img.shields.io/badge/Maatify-Rate--Limiter-blue?style=for-the-badge)](https://github.com/Maatify/rate-limiter)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

## 🎯 Goal
Prepare a complete local development environment (without Docker)  
for Redis, MongoDB, and MySQL based on XAMPP/MAMP/LAMP stack.

## ✅ Tasks Completed
- Composer and autoload configured
- Local `.env.example` file created
- GitHub Actions workflow added
- CHANGELOG and VERSION files created

## 💡 Usage
```bash
composer install
cp .env.example .env
vendor/bin/phpunit
````