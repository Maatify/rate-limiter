# 🛡️ Security Policy

**Project:** `maatify/data-fakes`
**Maintained by:** Maatify.dev

---

## 📅 Supported Versions

Security updates are provided only for the latest stable version:

| Version | Supported |
|---------|-----------|
| 1.0.x   | ✅ Yes     |
| < 1.0   | ❌ No      |

Older versions will not receive security patches.

---

## ⚠️ Reporting a Vulnerability

If you discover a security issue, **please DO NOT open a public GitHub issue**.
Instead, send a private report directly to:

📧 **[security@maatify.dev](mailto:security@maatify.dev)**
🔐 PGP (optional): *[Provide your key if you use PGP]*

Please include:

* A clear description of the vulnerability
* Steps to reproduce
* Potential impact
* Suggested fixes (if any)
* Your environment (PHP version, OS, adapter version)

We will respond within **48 hours**.

---

## 🔒 Handling Procedure

Once we receive a security report:

1. The issue is immediately triaged and verified.
2. A patch is developed in a private branch.
3. Maintainers test the fix across:

    * PHP 8.x versions
    * CI (PHPUnit + PHPStan)
4. A patched release is shipped (example: `1.0.1`).
5. A public advisory is published if the issue is high-severity.

You will be credited **unless you request anonymity**.

---

## 🧪 Security Expectations

Although this project is designed primarily for **testing and development**, we still follow:

* Strong typing (`declare(strict_types=1)`)
* Zero mixed types (PHPStan level 6)
* No dynamic eval operations
* No runtime code injection
* Deterministic memory-only behavior
* Strict validation in adapters and storage engine

No real database or connection is ever established by this library.

---

## 🤝 Responsible Disclosure

We kindly ask all security researchers to respect responsible disclosure:

* Do not publish vulnerabilities before a patch is released.
* Do not test against production Maatify services without authorization.
* Keep proof-of-concept exploits private.

We appreciate your contribution to the security and stability of the Maatify ecosystem.

---

## 🪄 Contact

For general questions or clarifications regarding this policy,
please contact **[security@maatify.dev](mailto:security@maatify.dev)**
or visit [https://www.maatify.dev/security](https://www.maatify.dev/security).

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-fakes

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
