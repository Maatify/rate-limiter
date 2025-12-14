# 🛡️ Security Policy

[![Maatify Repository](https://img.shields.io/badge/Maatify-Repository-blue?style=for-the-badge)](README.md)
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)


This document describes the security procedures and policies for **maatify/common**.
We take the security of our ecosystem seriously, and we appreciate any responsible disclosure that helps keep our libraries, users, and integrations safe.

---

## 📬 Reporting a Vulnerability

If you discover a security vulnerability, **please DO NOT open a public issue**.

Instead, contact us directly via the secure channels:

* **Email (Preferred):** [security@maatify.dev](mailto:security@maatify.dev)
* **Backup Email:** [mohamed@maatify.dev](mailto:mohamed@maatify.dev)
* **PGP Key (optional):** *Available upon request*

When reporting a vulnerability, please include:

1. A clear description of the issue
2. Steps to reproduce
3. Affected versions or components
4. Potential impact
5. Possible mitigation ideas (if any)

We aim to acknowledge all reports within **48 hours**.

---

## 🔒 Supported Versions

Only the latest major/minor versions receive security updates.

| Version      | Status                     |
|--------------|----------------------------|
| 1.x          | 🟢 Active security support |
| 0.x (legacy) | 🔴 No longer supported     |

If you depend on an unsupported version, please upgrade as soon as possible.

---

## 🚨 Severity Levels

We classify security issues using four levels:

| Level        | Description                                                                |
|--------------|----------------------------------------------------------------------------|
| **Critical** | Allows remote code execution, credential leaks, or severe data exposure    |
| **High**     | Authentication bypass, privilege escalation, or data corruption            |
| **Medium**   | Information disclosure, insufficient validation, partial denial-of-service |
| **Low**      | Minor bugs with limited or no practical impact                             |

---

## 🛠️ Handling Process

Once a vulnerability is reported:

1. **Initial review** — We investigate and confirm the issue.
2. **Internal tracking** — The issue is logged privately.
3. **Patch development** — A secure fix is prepared and tested.
4. **Coordinated release** — A patched version is published.
5. **Disclosure** — A security advisory (GHSA) is published on GitHub, if applicable.

We do **not** reveal reporter identity unless explicitly permitted.

---

## 🔐 Security Best Practices for Users

To keep your integration secure:

* Always use the **latest stable version** of the library.
* Never expose `.env` files or configuration data.
* Use secure DSNs with strong passwords.
* Follow PSR-12 and Maatify best practices for token handling.
* Validate and sanitize all user input before passing to your app.
* Review your CI/CD configuration for secret leaks.

---

## 🤝 Responsible Disclosure

We fully support and encourage **responsible vulnerability disclosure**.
If you follow the guidelines above, you will always receive fair, respectful, and prompt communication from the maintainers.

---

## 🏛️ Legal

* Do not perform tests that violate applicable laws.
* Do not perform actions that could disrupt production services.
* Do not access data that does not belong to you.

---

---

> 🧩 *maatify/common — Core Utilities, DTOs & Standards for the Maatify Ecosystem*
> © 2025 Maatify.dev • Maintained by Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
