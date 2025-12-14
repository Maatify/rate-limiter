# 🤝 Contributing to maatify/common

[![Maatify Repository](https://img.shields.io/badge/Maatify-Repository-blue?style=for-the-badge)](README.md)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

Thank you for your interest in contributing to **maatify/common** — the core foundational library powering the entire Maatify ecosystem.
Your contributions help improve code quality, reliability, and scalability across all Maatify projects.

---

## 🧩 Development Standards

Please adhere to the following conventions when contributing:

### ⚙️ Code Style

* Follow **[PSR-12](https://www.php-fig.org/psr/psr-12/)** coding standards.
* Use **strict typing** in all PHP files:

  ```php
  declare(strict_types=1);
  ```
* Include clear **DocBlocks** (`@param`, `@return`, `@throws`, etc.) for every class, method, and property.
* Keep classes **final** unless inheritance is explicitly required.

---

### 🧪 Testing

* All new features **must include PHPUnit tests**.
* Run the test suite before submitting any PR:

  ```bash
  vendor/bin/phpunit
  ```
* Maintain **≥95%** code coverage across the project.

---

### 🧱 Documentation

* Every new class, trait, or module must be documented in `/docs/`.
* Add code examples inside fenced code blocks (` ```php ... ``` `).
* Update the relevant `README.phaseX.md` when completing a project phase.
* Keep the **root README.md** in sync with new features.

---

### 🧾 Changelog

All changes should be reflected in **[CHANGELOG.md](CHANGELOG.md)** under the “Unreleased” or corresponding version section. Example:

```md
### Added
- New EnumHelper methods for enhanced enum management.
```

---

### 🪄 Commit Messages

Use clear, structured, and conventional commits:

| Type     | Description                     | Example                                   |
|----------|---------------------------------|-------------------------------------------|
| `feat:`  | New feature                     | `feat: add EnumHelper for enum utilities` |
| `fix:`   | Bug fix                         | `fix: correct regex in TextFormatter`     |
| `docs:`  | Documentation changes           | `docs: update README with usage examples` |
| `test:`  | Test additions or modifications | `test: add EnumHelper unit tests`         |
| `chore:` | Maintenance or release updates  | `chore(release): prepare v1.0.0`          |

---

### 🧭 Branch Naming Convention

| Branch Type | Prefix Example              |
|-------------|-----------------------------|
| Feature     | `feature/enum-helper`       |
| Fix         | `fix/validator-email-check` |
| Docs        | `docs/update-enums-readme`  |
| Release     | `release/v1.0.0`            |

---

### 🧩 Pull Request Process

1. Fork the repository and create your branch.
2. Ensure your code passes all **CI checks**.
3. Add or update tests and documentation as necessary.
4. Update the `CHANGELOG.md` and increment the version if applicable.
5. Submit a **Pull Request** with a descriptive title and summary.

---

### 🪪 License & Attribution

By contributing, you agree that your code will be licensed under the **[MIT license](LICENSE)** and attributed to **[Maatify.dev](https://www.maatify.dev)**.

---

### 💡 Maintainer

**Mohamed Abdulalim** — *Backend Lead & Technical Architect*
📧 [mohamed@maatify.dev](mailto:mohamed@maatify.dev)
🌐 [https://www.maatify.dev](https://www.maatify.dev)

---

> 🚀 **Together, we build consistency.**
> Every contribution makes the Maatify ecosystem stronger, cleaner, and smarter.
