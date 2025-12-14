# 🧱 Phase 6 — Advanced Integration & Release

> **Project:** maatify:bootstrap
> **Phase Objective:** Integrate Continuous Integration (CI/CD), containerized testing, and automated release validation for the Maatify Bootstrap core.

---

## 🚀 Overview

This phase focuses on ensuring that **Maatify Bootstrap** can be reliably built, tested, and validated in both local and cloud environments.

It introduces:
- GitHub Actions CI pipeline for testing and documentation validation.
- Docker and Docker Compose setup for local and isolated test environments.
- Automated verification of environment loading, timezone, and Safe Mode logic.
- Preparation for stable release publication to **Packagist** and GitHub.

---

## 🧩 CI/CD Integration

The CI/CD workflow is defined in:
```

.github/workflows/tests.yml

```

### ⚙️ Workflow Summary
| Stage                  | Description                                             | Outcome                                        |
|------------------------|---------------------------------------------------------|------------------------------------------------|
| 🧰 **Setup**           | Install dependencies, PHP 8.4, Composer packages        | Ensures consistent build environment           |
| 🧪 **Tests**           | Run PHPUnit with environment validation                 | Confirms code integrity and Safe Mode behavior |
| 📚 **Docs Validation** | Checks existence of `README.full.md` and `CHANGELOG.md` | Ensures release docs are complete              |
| 🐳 **Docker Build**    | Builds and tests maatify/bootstrap Docker image         | Validates container reproducibility            |

### 🧪 Run Sequence
1. Triggered on push or pull request to `main`, `master`, or `develop`.
2. Spins up Redis, MySQL, and MongoDB service containers.
3. Executes the full PHPUnit suite under `CI=true`.
4. Confirms documentation and Docker build validity.

---

## 🐳 Docker Integration

Local containerization enables consistent testing and environment parity with CI.

### 🗂 Files
```

docker/Dockerfile
docker/docker-compose.yml

````

### 🧰 Usage

#### 🔨 Build & Run Tests
```bash
docker compose up --build
````

This builds the container, installs dependencies, and runs:

```bash
composer run-script test
```

#### 🧭 Manual Commands

After the container starts:

```bash
docker compose exec bootstrap composer run-script test
```

This re-runs the test suite interactively inside the container.

---

## ⚙️ Environment Rules Recap

| Priority | File           | Purpose                                          |
|----------|----------------|--------------------------------------------------|
| 1️⃣      | `.env.local`   | Developer private overrides (local machine only) |
| 2️⃣      | `.env.testing` | CI and test pipeline configuration               |
| 3️⃣      | `.env`         | Production and staging deployment                |
| 4️⃣      | `.env.example` | Always included fallback for boot validation     |

* **CI environments** always use `.env.testing` with `CI=true`.
* **Developers** use `.env.local` to override private machine settings.
* **Production servers** load `.env` only.
* `.env.example` acts as a **safe fallback**, ensuring bootstrap never fails if no `.env` file exists.

---

## 🧪 Validation Goals

During CI and local Docker runs:

* ✅ `EnvironmentLoader` must load `.env.example` if no other env is present.
* ✅ `BootstrapDiagnostics` correctly identifies Safe Mode only when applicable.
* ✅ Tests must pass under PHP 8.4.
* ✅ Docs are verified for completeness.
* ✅ Docker build succeeds without warnings.

---

## 📦 Release Preparation

After successful CI validation:

1. **Tag version:**

   ```bash
   git tag -a v1.0.0 -m "Initial release — Maatify Bootstrap"
   git push origin v1.0.0
   ```

2. **Packagist Sync:**

    * Repository: [https://packagist.org/packages/maatify/bootstrap](https://packagist.org/packages/maatify/bootstrap)
    * Ensure `composer.json` has:

      ```json
      {
        "name": "maatify/bootstrap",
        "type": "library",
        "license": "MIT"
      }
      ```

3. **Final Verification:**

    * ✅ All PHPUnit tests green.
    * ✅ Safe Mode behavior correct.
    * ✅ CI logs show successful workflow.
    * ✅ Docker image builds cleanly.

---

## 🏁 Outcome

| Category                    | Result                                                              |
|-----------------------------|---------------------------------------------------------------------|
| 🧠 **Reliability**          | Verified across PHP 8.4, Redis, MySQL, and MongoDB                  |
| ⚙️ **Automation**           | Fully automated test + build pipeline                               |
| 🧰 **Developer Experience** | Local Docker mirrors CI setup                                       |
| 🧩 **Release Readiness**    | Ready for tagging, Packagist publish, and multi-library integration |

---

## 🔗 Next Phase (Phase 7 — Release & Docs Merge)

Phase 7 will merge all partial READMEs into a single **`README.full.md`**,
auto-generate badges (Packagist, CI status, PHP version), and prepare the **v1.0.0 public release**.

---

**© 2025 Maatify.dev — All rights reserved.**
