# 🤝 Contributing to **maatify/bootstrap**

Thank you for your interest in contributing to **maatify/bootstrap** —
the unified initialization, environment loading, and diagnostics layer powering the entire **Maatify ecosystem**.

Your contributions help ensure that all Maatify projects start consistently, securely, and predictably across local, testing, and production environments.

---

## 🧩 Development Standards

Please follow the standards below to keep the library clean, stable, and production-ready.

---

## ⚙️ Code Style

* Follow **[PSR-12](https://www.php-fig.org/psr/psr-12/)** standards.
* All PHP files must include:

  ```php
  declare(strict_types=1);
  ```
* All new classes should be declared **final** unless extensibility is explicitly required.
* Add clear and consistent **DocBlocks** for:

    * Classes
    * Methods
    * Properties
    * Exceptions
* Avoid introducing global state — use dependency injection where possible.
* Keep the bootstrap logic **idempotent** (initializes once per runtime).

---

## 🧪 Testing

Bootstrap controls the startup of the entire Maatify stack —
therefore, **every change must be covered with PHPUnit tests**.

* Run the full test suite before submitting PRs:

  ```bash
  vendor/bin/phpunit
  ```
* Required coverage: **≥95%**.
* Tests must cover:

    * Environment loading priority
    * No-override safety
    * Timezone fallback behavior
    * Safe Mode detection
    * Bootstrap initialization idempotency

If your PR introduces environment-sensitive logic, include tests for:

* When `.env.local` is present
* When `.env.testing` is present
* When no `.env` file exists
* When variables are pre-loaded via `putenv()` / `$_ENV`

---

## 📚 Documentation

* All new features require documentation under `/docs/`.
* If a core phase is changed, update the corresponding
  `docs/README.phaseX.md` file (e.g., phase for diagnostics, loader, helpers).
* Major changes must be reflected in:

    * `README.md` (public summary)
    * `docs/README.full.md` (developer documentation)

Use fenced code blocks for examples:

```php
Bootstrap::init();
```

---

## 🧾 Changelog Rules

Every PR must update **[CHANGELOG.md](CHANGELOG.md)** under:

* `### Added`
* `### Changed`
* `### Fixed`
* `### Removed`

Example:

```md
### Fixed
- Prevented `.env.testing` from loading during production bootstrap.
```

Versioning follows **Semantic Versioning (SemVer)**:

* `MAJOR`: Breaking changes
* `MINOR`: New features (no breaking changes)
* `PATCH`: Fixes or improvements

---

## 🪄 Commit Message Format

Follow conventional commits:

| Type     | Meaning                    | Example                                |
| -------- | -------------------------- | -------------------------------------- |
| `feat:`  | New feature                | `feat: add SafeMode diagnostics`       |
| `fix:`   | Bug fix                    | `fix: prevent .env override in loader` |
| `docs:`  | Documentation update       | `docs: improve bootstrap examples`     |
| `test:`  | Adding or updating tests   | `test: cover timezone fallback`        |
| `chore:` | Maintenance, CI, versions… | `chore(release): prepare v1.0.2`       |

---

## 🧭 Branch Naming Guidelines

| Purpose | Prefix Example               |
| ------- | ---------------------------- |
| Feature | `feature/env-loader-upgrade` |
| Fix     | `fix/timezone-override`      |
| Docs    | `docs/add-diagnostics-guide` |
| Release | `release/v1.0.2`             |

---

## 🔀 Pull Request Process

1. Fork the repository.
2. Create a branch with a proper prefix.
3. Add/update tests for all new logic.
4. Update `/docs/` and `README.md` if needed.
5. Update `CHANGELOG.md`.
6. Ensure all CI checks pass.
7. Submit your PR with:

    * Clear summary
    * Explanation of what changed and why
    * Screenshots/logs if relevant

PRs without tests or documentation will be requested for revision.

---

## 🪪 License & Attribution

By contributing, you agree that your code will be licensed under the **MIT License** and attributed to **Maatify.dev**.

---

## 👤 Maintainer

**Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))**
Backend Lead & Technical Architect
📧 [mohamed@maatify.dev](mailto:mohamed@maatify.dev)
🌐 [https://www.maatify.dev](https://www.maatify.dev)

**© 2025 Maatify.dev — All Rights Reserved**
Engineered with precision & consistency for the entire Maatify ecosystem.

📘 Full documentation & source code:
🔗 [https://github.com/Maatify/bootstrap](https://github.com/Maatify/bootstrap)

---

> 🚀 **Consistency starts here.**
> Your contributions help bootstrap the entire Maatify ecosystem.

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
