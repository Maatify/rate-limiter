# 🤝 Contributing to maatify/data-adapters

Thank you for considering contributing to **maatify/data-adapters**!
This project is part of the **Maatify.dev Ecosystem**, and we welcome contributions that improve reliability, performance, and developer experience.

This document explains how to report issues, request features, and submit pull requests in a clean and professional workflow.

---

# 🐛 1. Reporting Issues

If you encounter a bug or unexpected behavior:

1. Search existing issues first
2. If not found, open a new issue and include:
   - Clear title
   - Steps to reproduce
   - Expected vs actual behavior
   - Your `.env` adapter settings (without sensitive values)
   - PHP version and OS

👉 Create an issue:
https://github.com/Maatify/data-adapters/issues

---

# 🌟 2. Feature Requests

We love ideas that improve the core:

- MySQL connectivity
- Redis auto-detection & failover logic
- MongoDB support
- Diagnostics & telemetry
- Multi-profile enhancements

When submitting a feature request:

1. Explain the problem it solves
2. Suggest possible API or behavior
3. Include code samples if possible

---

# 🔧 3. Development Setup

Clone the repository:

```bash
git clone https://github.com/Maatify/data-adapters.git
cd data-adapters
composer install
````

Copy environment file:

```bash
cp .env.example .env
```

Run tests:

```bash
vendor/bin/phpunit
```

Run coding standards:

```bash
composer run lint
```

---

# 🧪 4. Testing Guidelines

* All new features **must** include tests
* Existing tests **must** pass before submitting a PR
* Avoid hardcoding DB credentials
* Prefer mock tests unless testing DB connectivity

Test locations:

```
tests/
 ├─ Adapters/
 ├─ Core/
 ├─ Diagnostics/
 └─ Integration/
```

---

# 🧱 5. Coding Standards

This project follows:

* **PSR-12**
* **Strict types**
* **Maatify naming conventions**
* **No global state**
* All classes must be `final` unless extension is intentional
* Use dependency injection when possible

Before opening a PR:

```bash
composer run lint-fix
```

---

# 🚀 6. Submitting Pull Requests (PR Guidelines)

### ✔️ PR Checklist

* [ ] Code follows PSR-12
* [ ] Code is fully typed (`declare(strict_types=1)`)
* [ ] No breaking changes unless discussed
* [ ] All tests pass
* [ ] New tests added (if needed)
* [ ] Documentation updated
* [ ] Commits are clean and meaningful

### ✔️ PR Flow

1. Fork the repository
2. Create feature branch:

   ```bash
   git checkout -b feature/my-new-feature
   ```
3. Commit changes with clear messages
4. Push and open a Pull Request
5. Request review from **Mohamed Abdulalim** or Maatify.dev maintainers

---

# 🧩 7. Commit Message Format

Recommended:

```
feat: add multi-profile MySQL support
fix: resolve Redis auto-fallback error
refactor: improve EnvironmentConfig structure
test: add coverage for DatabaseResolver
docs: update telemetry documentation
```

Avoid vague messages like “update”, “fix”, or “stuff”.

---

# 📦 8. Branch Naming Convention

```
feature/<feature-name>
bugfix/<issue-number>
hotfix/<quick-fix>
docs/<documentation-update>
```

Examples:

```
feature/mysql-profiles
bugfix/redis-connection-timeout
docs/improve-readme
```

---

# 🔐 9. Security Issues

If you discover a security vulnerability, **do not** open a public issue.

Email directly:

📧 **[security@maatify.dev](mailto:security@maatify.dev)**

The team will respond within 72 hours.

See `SECURITY.md` for full policy.

---

# ❤️ 10. Thank You

Your help makes this library stronger!
Every contribution improves the Maatify ecosystem and helps developers around the world.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
