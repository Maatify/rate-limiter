# 🤝 Contributing to maatify/psr-logger

Thank you for your interest in contributing to **maatify/psr-logger** —
the unified PSR-3 logging foundation powering all Maatify projects.
Your contributions help maintain consistent, secure, and reliable logging across the entire ecosystem.

> 🧩 **Important:**
> All external or community contributions **require mandatory technical review and approval**
> by the **Technical Architect (Mohamed Abdulalim)** before merging into the main branch.

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
* Maintain consistent logger context formatting:
  lowercase, slash-separated paths like `services/payment`, `api/auth`, etc.
* Avoid hardcoding paths — always use `LoggerFactory` and `LoggerContextTrait` for context handling.

---

## 🧪 Testing

* All new features **must include PHPUnit tests**.
* Run the full suite before submitting any PR:

  ```bash
  vendor/bin/phpunit --testdox
  ```
* Maintain **≥95 %** code coverage across the project.
* Ensure new log behavior (e.g. fallback, auto context) is covered with both functional and unit tests.

---

## 🧱 Documentation

* Every new class, trait, or module must be documented in `/docs/`.
* Include example code in fenced blocks:

  ```php
  $logger = LoggerFactory::create('example');
  $logger->info('Message');
  ```
* Update the **root README.md** whenever usage examples or behaviors change.

---

## 🧾 Changelog

All changes should be reflected in **[CHANGELOG.md](CHANGELOG.md)** under the “Unreleased” or corresponding version section.
Example:

```md
### Added
- LoggerContextTrait now returns LoggerInterface for direct usage.
```

---

## 🪄 Commit Messages

Use clear, structured, and conventional commits:

| Type     | Description                     | Example                                       |
|----------|---------------------------------|-----------------------------------------------|
| `feat:`  | New feature                     | `feat: add logger fallback for trait`         |
| `fix:`   | Bug fix                         | `fix: correct path handling in LoggerFactory` |
| `docs:`  | Documentation changes           | `docs: update README with new usage`          |
| `test:`  | Test additions or modifications | `test: add HybridLockManager tests`           |
| `chore:` | Maintenance or release updates  | `chore(release): prepare v1.0.1`              |

---

## 🧭 Branch Naming Convention

| Branch Type | Prefix Example                |
|-------------|-------------------------------|
| Feature     | `feature/logger-trait-return` |
| Fix         | `fix/context-path-error`      |
| Docs        | `docs/update-readme`          |
| Release     | `release/v1.0.1`              |

---

## 🧩 Pull Request Process

1. Fork the repository and create your branch.
2. Ensure your code passes all **CI checks** and **PHPUnit tests**.
3. Add or update documentation as needed.
4. Update `CHANGELOG.md` and bump the `VERSION` if applicable.
5. Submit a **Pull Request** with a descriptive title and clear summary.
6. Wait for a **mandatory review** and **approval** by the Technical Architect before merging.

---

## 🪪 License & Attribution

By contributing, you agree that your code will be licensed under the **[MIT license](LICENSE)**
and attributed to **[Maatify.dev](https://www.maatify.dev)**.

---

### 💡 Maintainer

**Mohamed Abdulalim** — *Backend Lead & Technical Architect*
📧 [mohamed@maatify.dev](mailto:mohamed@maatify.dev)
🌐 [https://www.maatify.dev](https://www.maatify.dev)

---

> 🚀 **Together, we build consistency.**
> Every contribution helps make Maatify’s logging infrastructure stronger, cleaner, and smarter.

---
