# Phase 7 — Fixtures Loader & FakeEnvironment

**Version:** 1.0.3
**Project:** maatify/data-fakes
**Status:** Completed
**Date:** 2025-11-22T08:30:00+02:00

---

## 🎯 Goals

* Implement JSON-driven fixtures loader for MySQL/DBAL, Mongo, and Redis adapters.
* Provide FakeEnvironment for per-test setup/teardown with optional auto-reset.
* Allow loading initial datasets from files or arrays.
* Ensure adapters reset state between tests to keep runs deterministic and isolated.

---

## 📁 Deliverables

```
src/Fixtures/FakeFixturesLoader.php
src/Fixtures/JsonFixtureParser.php
src/Environment/FakeEnvironment.php
src/Environment/ResetState.php
```

### Tests

```
tests/Fixtures/FakeFixturesLoaderTest.php
tests/Environment/FakeEnvironmentTest.php
tests/Fixtures/sample-fixtures.json
```

---

## 🧠 Architecture Summary

### **JsonFixtureParser**

* Reads a JSON fixture file.
* Decodes it into structured associative arrays.
* Throws errors for unreadable or invalid JSON.

### **FakeFixturesLoader**

* Accepts decoded fixture arrays.
* Hydrates:

    * FakeStorageLayer (MySQL / DBAL)
    * FakeMongoAdapter collections
    * FakeRedisAdapter key/value / hash / list structures
* Ensures deterministic population order.

### **FakeEnvironment**

* Combines FakeStorageLayer + Mongo + Redis into one environment.
* Provides:

    * `beforeTest()` → resets storage based on ResetState flag
    * `reset()` → clears MySQL, Mongo, and Redis manually
    * `loadFixtures()` + `loadFixturesFromFile()`

### **ResetState**

* Maintains whether auto-reset is enabled.
* Used internally by FakeEnvironment before each test.

---

# 🔌 **Expanded Integration Details**

## 1️⃣ How Environment Controls All Adapters

FakeEnvironment creates:

* A shared FakeStorageLayer
* FakeMySQLAdapter + FakeMySQLDbalAdapter (both read/write the same layer)
* FakeMongoAdapter
* FakeRedisAdapter

This ensures:

✔ Same dataset visible across MySQL and DBAL
✔ Mongo collections isolated per environment
✔ Redis keys flushed on reset

---

## 2️⃣ Test Lifecycle

* Before each test → call `beforeTest()`
* If auto-reset enabled →

    * MySQL tables wiped
    * DBAL wiped
    * Mongo collections cleared
    * Redis keys cleared
* Then apply fixtures if needed

This guarantees:

**No side effects between tests.
100% deterministic test output.**

---

## 3️⃣ Fixture Ingestion Logic

### SQL (MySQL / DBAL)

```json
{
  "sql": {
    "products": [
      { "id": 1, "name": "Phone" },
      { "id": 2, "name": "Laptop" }
    ]
  }
}
```

### Mongo

```json
{
  "mongo": {
    "users": [
      { "_id": 1, "email": "test@example.com" }
    ]
  }
}
```

### Redis

Supports:

* strings
* hashes
* lists
* counters

Example:

```json
{
  "redis": {
    "cache:key1": "value",
    "session:1": { "type": "hash", "value": { "token": "abc" } }
  }
}
```

---

# 🧪 Tests Summary

### **FakeFixturesLoaderTest**

* Loads fixtures from arrays and files.
* Validates SQL, Mongo, and Redis hydration.
* Confirms correct types (hash / list / string).

### **FakeEnvironmentTest**

* Ensures auto-reset clears all adapters.
* Confirms fixture loading through the environment layer.
* Tests deterministic state after multiple test runs.

Run:

```
composer run-script test
```

---

# 📦 Files Generated

* README.phase7.md
* src/Fixtures/FakeFixturesLoader.php
* src/Fixtures/JsonFixtureParser.php
* src/Environment/FakeEnvironment.php
* src/Environment/ResetState.php
* tests/Fixtures/FakeFixturesLoaderTest.php
* tests/Environment/FakeEnvironmentTest.php
* tests/Fixtures/sample-fixtures.json

---

# 📘 **Usage Examples**

## 1️⃣ Loading fixtures from array

```php
$env = new FakeEnvironment();
$env->beforeTest();

$env->loadFixtures([
    'sql' => [
        'products' => [
            ['id' => 1, 'name' => 'Phone'],
        ]
    ]
]);
```

---

## 2️⃣ Loading fixtures from JSON file

```php
$env = new FakeEnvironment();
$env->beforeTest();

$env->loadFixturesFromFile(__DIR__ . '/Fixtures/sample-fixtures.json');
```

---

## 3️⃣ Using auto-reset in PHPUnit

`tests/bootstrap.php`

```php
$env = new FakeEnvironment();
ResetState::enableAutoReset();

$GLOBALS['env'] = $env;
```

`tests/TestCase.php`

```php
protected function setUp(): void
{
    $GLOBALS['env']->beforeTest();
}
```

---

# ⚙️ **Technical Notes**

### ✔ Deterministic Ordering

Fixtures are always loaded in:

1. SQL
2. Mongo
3. Redis

This prevents state drift between adapters.

### ✔ Duplicate IDs

If SQL fixture rows include duplicate IDs,
**later entries override earlier ones**.

### ✔ Invalid Redis Structures

Unsupported Redis types are ignored silently
(for full compatibility with real Redis fakes).

### ✔ Fixture Merge Strategy

Fixtures **replace** existing data — no merging.

### ✔ MySQL & DBAL Share State

Both adapters use one FakeStorageLayer →
Updates from DBAL are visible in MySQL adapter and vice-versa.

### ✔ Environment Reset = Full Isolation

Reset wipes **all**:

* storage tables
* Mongo collections
* Redis keys

---

# 🎉 Phase 7 Completed

Phase 7 introduces the first complete “test isolation engine” for the fakery system, enabling reliable, reproducible tests across all adapters.

---