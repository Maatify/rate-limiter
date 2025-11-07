# 🧩 Phase 1 – Environment Setup (Local Mode)

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