# Contributing to maatify/data-fakes

Thank you for choosing to contribute to **Maatify Data Fakes**!
We welcome improvements, bugfixes, documentation updates, and new feature proposals.

This document explains how to structure contributions, coding standards, testing requirements, and the review process.

---

# 🧱 Development Requirements

Before contributing, ensure your environment meets the following:

* **PHP:** 8.2+
* **Composer:** latest
* **PHPUnit:** via composer
* **PHPStan:** level 6 (required to pass)
* **PSR-12** code style
* **Strict types:** always `declare(strict_types=1);`
* **Git:** clean commit history, atomic commits

---

# 🧹 Coding Standards

All code **must** follow:

### ✔ PSR-12 Standard

Use consistent formatting throughout all files.

### ✔ Strict Types

Every file starts with:

```php
declare(strict_types=1);
```

### ✔ Fully Typed Code

* No mixed types
* No dynamic properties
* Use typed arrays (`array<string, mixed>`)
* Use type hints for all parameters & return values

### ✔ PHPStan Level 6

Your contribution must pass:

```bash
composer run analyse
```

If PHPStan complains, fix it before submitting a PR.

### ✔ Project Header Required

Each PHP file MUST include:

```php
/**
 * @copyright   ©2025 Maatify.dev
 * @Library     maatify/data-fakes
 * @Project     maatify:data-fakes
 * @author      Your Name
 * @since       YYYY-MM-DD HH:MM
 */
```

Use your actual name and timestamp.

---

# 🧪 Tests

All contributions must include **unit tests**.

### Test Requirements:

* PHPUnit is required
* Tests must not require real MySQL/Redis/Mongo
* Only Fake Adapters and FakeStorageLayer must be used
* Ensure deterministic behavior (no randomness)

Run tests:

```bash
composer run test
```

---

# 📦 Pull Request Process

1. Fork the repository
2. Create a new branch:

   ```
   git checkout -b feature/my-new-feature
   ```
3. Write your code
4. Add/Update tests
5. Ensure **PHPStan passes**
6. Ensure **tests pass**
7. Ensure **composer.json** and **README.md** still reflect accurate information
8. Commit using clean messages:

   ```
   feat: add FakeMongo advanced operators
   fix: correct TTL logic in FakeRedisAdapter
   docs: update README.full.md structure
   ```
9. Push your branch
10. Create a Pull Request on GitHub

A maintainer will review your contribution.

---

# 📝 Commit Message Guidelines

Follow **Conventional Commits**:

| Type     | Purpose                  |
|----------|--------------------------|
| feat     | New features             |
| fix      | Bug fixes                |
| docs     | Documentation changes    |
| refactor | Code restructuring       |
| test     | Adding tests             |
| perf     | Performance improvements |
| chore    | Build/maintenance        |

Examples:

```
feat: implement regex filtering in FakeMySQLAdapter
fix: prevent mixed return type in FakeMongoAdapter::findOne
docs: enhance readme with badge updates
```

---

# 🛡️ Security Contributions

For security-related issues, **do not open a public issue**.
Please read `SECURITY.md` and report privately.

---

# 💬 Questions & Discussion

If you have ideas or need help before contributing:

Email: **[dev@maatify.dev](mailto:dev@maatify.dev)**
Official site: **[https://www.maatify.dev](https://www.maatify.dev)**

---

# 🤝 Thank You

Your contributions help improve the stability and power of the entire Maatify ecosystem.
We appreciate every PR, issue, and suggestion!
