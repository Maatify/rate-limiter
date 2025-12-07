# Contributing to maatify/rate-limiter

[![Maatify Repository](https://img.shields.io/badge/Maatify-Repository-blue?style=for-the-badge)](README.md)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)

Thank you for considering contributing to **maatify/rate-limiter**!  
This project follows strict architectural, security, and coding standards to ensure high performance, correctness, and long-term stability across the entire Maatify ecosystem.

Please read the following guidelines carefully before submitting issues, feature proposals, or pull requests.

---

# 📌 Ways to Contribute

You can contribute by:

- Reporting bugs
- Proposing new rate-limiting strategies or drivers
- Improving documentation
- Submitting pull requests (bug fixes, optimizations, refactoring)
- Writing or improving test coverage
- Improving middleware or framework integrations

---

# 🧱 Project Structure

```

src/              Main library source
tests/            PHPUnit test suite
docs/             Documentation & phase history
examples/         Practical usage examples

````

Refer to the main documentation:

👉 [`README.md`](README.md)  
👉 [`examples/Examples.md`](examples/Examples.md)

---

# 🧪 Running Tests

Before submitting any pull request, make sure all tests pass:

```bash
composer install
composer test
````

To run static analysis:

```bash
composer run analyse
```

Minimum requirements:

* PHPStan level: **MAX** (no errors allowed)
* PHPUnit: all tests must pass
* Coverage: no regression allowed
* Drivers consistency: Redis / MongoDB / MySQL behaviors must remain aligned

---

# 🧹 Code Style

This project follows:

* **PSR-12** coding standards
* **Strict Types** (`declare(strict_types=1)`)
* **Semantic & consistent naming**
* **No unused imports or dead code**
* **No mixed types unless well documented**
* **DTO immutability where applicable**
* **Resolver & Driver contracts MUST be respected**

Before pushing your changes:

```bash
composer run lint
composer run format
```

---

# 🧬 Commit Messages

Use clear, descriptive commit messages.

Recommended format:

```
type(scope): short description

Longer explanation (optional)
```

Examples:

* `fix(redis-driver): correct ttl expiration logic`
* `feat(backoff): add adaptive global limiter`
* `docs: improve usage examples`
* `refactor(resolver): simplify driver detection`

---

# 🌱 Branching Model

We use the following branching workflow:

* `main` → stable releases
* `dev` → active development
* feature branches:
  `feature/<short-name>`
* bugfix branches:
  `fix/<short-name>`

---

# 🔀 Pull Request Guidelines

Before opening a PR:

1. Ensure code passes **all tests & static analysis**
2. Follow **PSR-12 + project architectural rules**
3. Update or add tests for any behavioral change
4. Update documentation if your change affects usage
5. Keep PRs **focused and minimal**
6. Reference related issues when applicable
7. Add a clear PR description explaining:

    * What changed
    * Why the change is needed
    * How it was tested
    * Any backward compatibility considerations

PRs that fail CI, reduce coverage, or violate architectural rules may be rejected.

---

# 🧩 Architectural Rules

All contributors must follow the internal architecture principles of `maatify/rate-limiter`, including:

* Unified `attempt() / status() / reset()` API across drivers
* Consistent behavior between Redis / MongoDB / MySQL
* Exponential backoff logic must remain deterministic and bounded
* DTOs must be immutable and serializable
* Middleware must remain **PSR-15 compliant**
* No framework coupling inside the core library

---

# 🗂 Versioning

We follow **Semantic Versioning (SemVer)**:

```
MAJOR.MINOR.PATCH
```

* **PATCH** → Bug fixes & internal improvements
* **MINOR** → Backward-compatible features
* **MAJOR** → Breaking API changes

Every release must be documented in:

👉 `CHANGELOG.md`

---

# 🔒 Security Vulnerabilities

To report a security issue, do **NOT** open a public GitHub issue.

Instead, contact the Maatify security team at:

📧 **[security@maatify.dev](mailto:security@maatify.dev)**

Also see:

👉 [`SECURITY.md`](SECURITY.md)

---

# 🙏 Thank You!

Your contributions help make the Maatify ecosystem more secure, scalable, and reliable.
We deeply appreciate your time, effort, and commitment to clean architecture.

<p align="center">
  <sub>Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</sub>
</p>