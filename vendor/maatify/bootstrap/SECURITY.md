# 🛡️ Security Policy

**Library:** `maatify/bootstrap`
**Maintainer:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))
**Organization:** [Maatify.dev](https://www.maatify.dev)
**License:** MIT
**Version:** 1.0.2
**Last Updated:** 2025-11-13

---

## 🔐 Supported Versions

| Version | Supported | Notes                                       |
|---------|-----------|---------------------------------------------|
| 1.0.x   | ✅         | Security patches + environment safety fixes |
| <1.0.0  | ❌         | Unsupported                                 |

`maatify/bootstrap` is a foundational package — only the latest version receives updates.

---

## ⚠️ Reporting a Vulnerability

If you discover a security vulnerability, please **do NOT open a public GitHub issue.**
Instead, report it privately through:

### 📮 Secure Channels

* Email: **[security@maatify.dev](mailto:security@maatify.dev)**
* GitHub Security Advisory:
  [https://github.com/Maatify/bootstrap/security/advisories/new](https://github.com/Maatify/bootstrap/security/advisories/new)

Provide:

* Description + severity
* Steps to reproduce
* PHP version + OS
* Affected versions
* Suggested mitigation (optional)

---

## 🕐 Response Process

| Step | Action                                | Target Time           |
|------|---------------------------------------|-----------------------|
| 1️⃣  | Acknowledge report                    | ≤ 24 hours            |
| 2️⃣  | Verify & reproduce                    | 2–5 business days     |
| 3️⃣  | Patch + internal review               | 5–7 days              |
| 4️⃣  | Coordinate disclosure & release patch | After fix is deployed |

---

## 🧠 Security Considerations for `bootstrap`

Because this package handles **environment loading**, **startup logic**, and **safe mode**, its security demands are strict:

### 🔒 Environment Handling Safety

* No `.env` file may override pre-existing system variables.
* `.env.local` and `.env.testing` must **never** load in production.
* Test environments must load via immutable snapshots only.
* Sensitive environment values are never logged.

### 🧱 Initialization Integrity

* `Bootstrap::init()` must never cause side effects outside its scope.
* Timezone auto-setup must use safe, validated values.
* Safe Mode protects production environments from accidental misconfiguration.

### ⚙️ Error Handling

* Exception traces sanitized before logging.
* No leaking of sensitive environment paths.
* Consistent behavior across CLI, web, and CI environments.

### 🚫 What Must Never Happen

* Overwriting CI credentials
* Overwriting PHPUnit test variables
* Loading `.env.testing` in production
* Logging actual `.env` secrets

---

## 🔐 Security Best Practices for Users

* Protect `.env.local`, `.env.testing`, `.env` files from public access.
* On production servers:

    * Disable file browsing
    * Ensure `.env` is outside document root if possible
    * Use environment variables from systemd, Docker, or CI
* Do not store credentials inside repository
* Review Safe Mode warnings in your CI pipeline

---

## 🪄 Contact

For questions regarding security:

📧 **[security@maatify.dev](mailto:security@maatify.dev)**
🌐 [https://www.maatify.dev/security](https://www.maatify.dev/security)

---

<p align="center">
  <sub>© 2025 <a href="https://www.maatify.dev">Maatify.dev</a> — Maintained by <a href="https://github.com/megyptm">@megyptm</a><br>
  Unified Bootstrap & Environment Loader for all Maatify PHP Libraries</sub>
</p>

---
