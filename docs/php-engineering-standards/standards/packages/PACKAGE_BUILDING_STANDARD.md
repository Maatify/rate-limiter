# PACKAGE_BUILDING_STANDARD

**Maatify Standalone Composer Package Building Standard**

## Standard Metadata

- **Standard ID:** `std-package-building`
- **Standard Version:** `1.4.0`
- **Standard Version Format:** `MAJOR.MINOR.PATCH`

This document is the law for building any new standalone Composer package in the Maatify ecosystem.
Read it fully before writing a single line of code.

---

## 1. The Package Contract

This standard applies generally to all PHP/Composer reusable libraries in the Maatify ecosystem.

Every package must be:

- **Standalone** — runs isolated from host applications (depends only on explicit Composer/runtime dependencies) with no knowledge of the host project internals
- **Installable** — packaged as a `composer require` library
- **Host-agnostic** — never FKs or JOINs on host tables. Host provides IDs; package trusts them. Host applications wire dependencies themselves.
- **PHPStan max** — zero errors at level max before the package is considered done

### PHP 8.4 Baseline

PHP 8.4 is the minimum baseline for newly created Maatify PHP libraries, reusable modules, and new standalone PHP work. An already published package MUST retain its declared compatibility contract until raising the minimum PHP version is permitted by the package's compatibility/versioning policy.

### Persistence Conditional Applicability

All rules in this Standard concerning PDO, SQL, `schema/`, migrations, transaction handling, Ordering/Pagination, PDO hydration, database integration, and database-focused testing apply only when the package owns SQL persistence or SQL database behavior.

A package without persistence or database behavior is not required to use PDO, provide `schema/`, define migrations, or implement database-specific architecture or tests. When a package does own persistence or database behavior, all applicable persistence requirements in this Standard remain mandatory. For SQL persistence, these requirements include direct PDO usage, no ORM, and no external query builder. Small internal SQL fragment builders are allowed only for repeated package-local query logic.

### Default SQL Persistence Profile

The default SQL persistence profile is MySQL/MariaDB-compatible and uses direct PDO.

A package MAY define a different or additional persistence backend, including non-SQL storage, as part of its documented persistence contract. A claimed backend MUST be explicitly documented, actually implemented, and protected by real persistence verification/tests applicable to that backend.

Additional backends are package-specific and MUST NOT be required of any package.

PDO driver availability alone does not establish database support.

### Scope and Ownership

- This file owns runtime architecture, package structure, and package-specific testing applicability.
- [`TESTING_STANDARD.md`](../testing/TESTING_STANDARD.md) owns the general testing strategy, observable-behavior evidence, regression-protection model, and Consumer Verification Harness contract; this Standard defines Package-readiness applicability and MUST NOT duplicate the Harness's detailed requirements.
- [`COMPOSER_PACKAGE_STANDARD.md`](COMPOSER_PACKAGE_STANDARD.md) owns `composer.json`, dependency declarations, and version constraints.
- [`CI_WORKFLOW_STANDARD.md`](CI_WORKFLOW_STANDARD.md) owns workflow and check execution.
- [`LIBRARY_PRESENTATION_STANDARD.md`](LIBRARY_PRESENTATION_STANDARD.md) owns README structure, badges, and release-facing files.

These standards MUST use cross-references and MUST NOT duplicate each other's detailed rules.

---

## 2. Required Maatify Runtime Dependencies

To maintain standalone Composer package boundaries and a framework-agnostic architecture while ensuring ecosystem consistency, packages must rely on the provided Maatify shared packages rather than defining package-local duplicates.

- **Exceptions:** When a package defines package-owned exception classes, they MUST use the appropriate stable hierarchy from `maatify/exceptions`, available from `v1.0.0`.
  - `MaatifyException` is the abstract root base class.
  - `ApiAwareExceptionInterface` is the general public contract.
  - The package marker interface remains package-owned and extends `\Throwable`.
  Repository: https://github.com/Maatify/exceptions

- **Clock/Date-Time:** A package that requires a clock abstraction MUST depend on `maatify/shared-common` and consume `ClockInterface`, available from `v1.0.0`. `SystemClock` is a production implementation available from `v1.0.0`, but it is not mandatory for every consumer. No shared Frozen/Test Clock is claimed by this Standard.
  Repository: https://github.com/Maatify/SharedCommon

- **Persistence Utilities:** Packages that require reusable PDO row-position/display-order management (available from `v1.0.0`) or pagination capabilities (available from `v1.1.0`) must depend on `maatify/persistence`.
  Repository: https://github.com/Maatify/persistence

**Rule:** Packages MUST NOT define package-local duplicates of a capability that is available through a stable public API in a Maatify shared package. This prohibition includes exception hierarchies, clock abstractions, PDO row-position/display-order mechanics, and PDO pagination mechanics.

A consuming package MUST declare the minimum stable dependency version that exposes every shared API it uses. Proposed architecture documents, unreleased branches, commits, and implementation contracts are not consumable public APIs and MUST NOT be copied into consumer packages.

The declaration, constraint, ordering, and validation rules for these dependencies are governed by [COMPOSER_PACKAGE_STANDARD.md](COMPOSER_PACKAGE_STANDARD.md).

This ensures:
- Explicit Composer/runtime dependencies only
- Public API stability
- Backward compatibility as much as possible
- One authoritative implementation for shared ecosystem capabilities

---

## 3. Required Files

Repository presentation, governance-document identity, release-facing metadata, and visual consistency MUST follow [LIBRARY_PRESENTATION_STANDARD.md](LIBRARY_PRESENTATION_STANDARD.md).
Composer package metadata, dependency declarations, autoloading, scripts, configuration, stability, and lock-file policy MUST follow [COMPOSER_PACKAGE_STANDARD.md](COMPOSER_PACKAGE_STANDARD.md).


Every package must contain these files at its package root (the repository root for a standalone package; for an in-project Base Module, the Artifact Root defined in [`MODULE_BUILDING_STANDARD.md`](../modules/MODULE_BUILDING_STANDARD.md)):

```
├── README.md                          ← installation, quick examples, what it does / does not
├── CHANGELOG.md                       ← Keep a Changelog; release history begins at [1.0.0]
├── {PACKAGE_NAME}_PACKAGE_REFERENCE.md ← canonical stable package contract (e.g. EXAMPLE_PACKAGE_REFERENCE.md)
├── composer.json                      ← governed by COMPOSER_PACKAGE_STANDARD.md
├── phpstan.neon                       ← governed by Section 21
├── src/                               ← all PHP source code
├── tests/                             ← if applicable
├── schema/                            ← if applicable (only when the package has SQL persistence or SQL database behavior)
└── docs/                              ← detailed architecture, integration, roadmap, and audit documents
```

### Canonical Package Reference Location

Every standalone Maatify package MUST maintain exactly one canonical Package Reference at the repository root.

The canonical path is:

```text
/{PACKAGE_NAME}_PACKAGE_REFERENCE.md
```

The root Package Reference owns:

- the complete stable package contract
- the public Runtime API inventory
- stable behavior and exception guarantees
- package boundaries and non-goals
- links to detailed supporting documentation

Detailed architecture decisions, proposed implementation contracts, integration guides, roadmaps, deferred scope, and audits belong under `docs/`.

Supporting documents under `docs/` MAY provide deeper detail, but they MUST NOT become a second competing Package Reference. They SHOULD link back to the root Package Reference when they define or explain part of the stable package contract.

The root Package Reference SHOULD index the relevant detailed documents under `docs/`.

A multi-domain package still has one canonical root Package Reference unless a domain is extracted into a separate Composer package with its own repository and package contract.

---

## 4. Namespace

Pattern: `Maatify\{PackageName}\`

```
Maatify\LibraryExample\
Maatify\{NextPackage}\
```

*Note: Host app namespaces such as `App\`, `HostApp`, or `OtherApp` are strictly forbidden.*

### Type Naming Convention

Public and internal type names MUST make the declared artifact type immediately clear to implementers and reviewers.

The required suffixes are:

- every interface MUST end with `Interface`
- every enum MUST end with `Enum`
- every Data Transfer Object MUST end with `DTO`
- every exception class MUST end with `Exception`

Exception marker interfaces are not exempt from the interface rule. A new package marker MUST therefore use:

```text
{PackageName}ExceptionInterface
```

The PHP filename MUST match the declared type name.

New packages and unpublished APIs MUST NOT introduce alternative names that omit the required suffix. Package-specific compatibility exceptions are permitted only under the legacy-public-API rule in Section 7.

---

## 5. Directory Structure Inside `src/`

The default organizing principle is:

```text
Domain → Capability → Layer
```

Organize first around meaningful domain boundaries, then around capabilities within each domain, and introduce layers inside a capability when its size or responsibilities need them. This is a design direction, not a required directory template: small packages MUST NOT add empty or ceremonial folders just to match a diagram.

`Common/` is appropriate only for genuinely shared, framework-neutral primitives. The Host owns application bootstrap, container bindings, and framework-specific providers; conditional construction entry points are governed by Section 18.

Packages MUST NOT impose `Admin/Customer` directories where those are not real domain boundaries. Package runtime exclusions are defined in Section 15.

---

## 6. Schema Rules

These rules, including `schema/`, database structure, database tests, and migrations, are conditionally applicable **only** when the package has SQL persistence or SQL database behavior.

- Table prefix: `maa_{package_short_name}_` (e.g. `maa_library_example_`)
- Every table needs: `PRIMARY KEY (id)`, proper indexes, meaningful COMMENTs on columns.
- All policies (soft delete, display order, FK behavior, uniqueness) documented in the SQL header.
- Domain-local schema files are allowed (e.g. `src/{Domain}/Database/`).
- Package-level `schema/README.md` may index domain-local SQL files.
- **No generic shared `logs` or `event_logs` tables**; use strict domain-isolated tables.
- No FK constraints or JOINs to host app tables — use `COMMENT 'Host-provided ID. No FK.'`.

---

## 7. Exception Rules

### Standard Exception Types

1. Every package-defined exception MUST use the appropriate `maatify/exceptions` hierarchy.
2. Every package MUST expose a package marker interface extending `\Throwable`.
3. Stable, package-owned failure classifications SHOULD use named package exceptions.
4. A known domain or storage condition MAY be converted to a named package exception when the package owns a stable semantic classification.
5. Unknown or external `PDOException` / `Throwable` instances MAY propagate unchanged when preserving the original diagnostic contract is intentional.
6. A package MUST document whether it wraps or propagates infrastructure errors.
7. Blind catch-all wrapping is forbidden.
8. Swallowing errors is forbidden.
9. When wrapping, preserve the original throwable as `previous` where supported.
10. Transaction catch blocks must rollback owned transactions and rethrow the original throwable unless an explicitly documented semantic conversion is performed.
11. Rethrowing the original throwable after rollback is valid and is not a violation of named-exception rules.
12. Packages MUST NOT be universally required to convert every external infrastructure failure into a package-defined exception. The chosen wrapping or propagation contract must follow the package-owned semantic boundary and be documented.

### Interface

```php
interface {PackageName}ExceptionInterface extends \Throwable {}
```

The canonical package marker location is:

```text
Maatify\{PackageName}\Exception\{PackageName}ExceptionInterface
```

Every package-defined exception MUST implement this marker directly or indirectly. External `PDOException` or other propagated infrastructure throwables MUST NOT be forced to implement the package marker.

### Legacy Published Marker Names

A package that already published a stable marker interface without the required `Interface` suffix MAY retain that name when renaming it would require an otherwise unnecessary major release.

This exception:

- MUST be documented explicitly in the root Package Reference
- MUST identify the affected major release line
- MUST remain internally consistent throughout that major line
- MUST require every package-defined exception added during that major line to implement the retained marker directly or indirectly
- MUST NOT introduce a parallel marker solely to normalize naming
- MUST remain package-specific and MUST NOT be copied into new packages
- MUST be reconsidered only during a separately approved, meaningful future major release

Release-version selection, including whether a legacy marker rename warrants a Major release, is governed by [`LIBRARY_PRESENTATION_STANDARD.md`](LIBRARY_PRESENTATION_STANDARD.md).

### Example: Package-Defined Storage Exception

```php
final class {Domain}DatabaseException extends \Maatify\Exceptions\Exception\System\SystemMaatifyException
    implements {PackageName}ExceptionInterface
{
    // Implementation uses Maatify codes
}
```

Named constructors SHOULD be used when a stable semantic constructor exists.

Direct construction MAY be used when no suitable named constructor exists and the package's documented exception contract permits it.

Call sites MUST NOT construct generic or semantically misleading exceptions merely to avoid defining an appropriate package-owned classification.

### Fail-Open / Fail-Closed Behavior

Behavior must stay domain-specific:
- **Authoritative Domains** (e.g. `{AuthoritativeDomain}`) are fail-closed where required by the domain.
- **Non-Authoritative Domains** may fail-open only at an explicitly documented boundary.
- **Repositories and Read Queries** must never silently swallow storage failures.

### What the package catches and converts

- `PDOException::getCode()` represents the SQLSTATE, not a Driver-specific error code.
- SQLSTATE Class `23` is a broad category for Integrity Constraint Violations (e.g., Duplicate Key, Foreign Key, Explicit NULL), not just duplicates alone.
- It is forbidden to blindly map all `23xxx` errors to an `AlreadyExists` or Duplicate exception.
- Semantic conversion is permitted only when there is documented Driver-specific evidence proving a Duplicate Key violation.
- `PDOException::$errorInfo` might be `null`, so you must check for the existence of `[1]` safely before using it.
- The MySQL/MariaDB driver uses code `1062` to identify a Duplicate Key. Any other Driver or DBMS must use its own official documented code and must not blindly copy `1062`.
- Any error not explicitly classified by a documented evidence must propagate as-is.
- Retain the `previous` exception when your wrapping exception contract supports it, but do not invent a new named constructor signature solely for that purpose.

*Note: The following example is Driver-specific to MySQL/MariaDB. It is not a general rule for all PDO drivers.*

```php
} catch (\PDOException $e) {
    $driverCode = isset($e->errorInfo[1])
        ? (int) $e->errorInfo[1]
        : null;

    // MySQL/MariaDB duplicate-key driver code.
    if ($driverCode === 1062) {
        throw {Domain}CodeAlreadyExistsException::withCode($command->code);
    }

    throw $e;
}
```

### Transaction Pattern

In any method that uses `beginTransaction()`:

```php
        $this->pdo->beginTransaction();

        try {
            // ...
            $this->pdo->commit();
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            // Optional documented semantic conversion may occur here.

            throw $e;
        }
```

Only package-owned transactions are rolled back by this pattern.
Rollback must be attempted only while the transaction is active.
The original throwable is rethrown unless an explicitly documented semantic conversion is performed.
Swallowing the throwable is forbidden.
If a semantic conversion wraps the original throwable, the original should be retained as `previous` where supported.

## 8. Command Rules

For public contracts accepting IDs as raw `int|string` input, accept only a canonical positive integer representation. Reject floats, booleans, null, signs, whitespace, leading zeroes, decimal notation, and scientific notation; check bounds before casting to `int`.

For strict, known-format date-only input, use `\DateTimeImmutable::createFromFormat()` and require an exact round-trip string match so normalization or overflow is rejected. Inspecting `getLastErrors()` MAY provide an additional check. This does not prohibit general free-form date-time parsing with `\DateTimeImmutable`.

Commands are self-validating value objects:

```php
final readonly class CreateSomethingCommand
{
    public function __construct(
        public string  $code,
        public string  $name,
        public bool    $isActive,
        public ?string $notes,
    ) {
        if (trim($code) === '') {
            throw SomethingInvalidArgumentException::emptyField('code');
        }
        if (trim($name) === '') {
            throw SomethingInvalidArgumentException::emptyField('name');
        }
    }
}
```

Rules:
- `final readonly` — always
- A Command represents mutation or action intent. It validates its input contract in the constructor and MUST NOT perform business orchestration.
- Query, search, and list filters use a `Criteria` or another explicit query contract; result or data snapshots use DTOs. An object representing execution intent MUST NOT be named a DTO.
- A small operation with one or two typed inputs MAY use typed parameters directly when a Command would add no meaningful contract.
- Display-order inputs MUST follow Section 16; `CreateCommand` and `UpdateCommand` do not accept `display_order`
- Host IDs (e.g. `methodId`, `currencyId`) MUST follow the canonical positive-ID contract in this section.
- Image/media inputs MUST follow the conditional ownership and assignment contract in Section 17; this Standard does not require every domain to have an image field or a dedicated image operation.
- Monetary and fixed-precision decimal inputs MUST follow Section 19
- General/free-form date-time strings MUST be validated with `new \DateTimeImmutable($value)` in a try/catch; strict known-format date-only inputs follow the round-trip rule defined earlier in this section.

---

## 9. DTO Rules

DTOs represent data snapshots or results and MUST NOT be used as substitutes for Commands or other execution-intent contracts. Query filters belong in a `Criteria` or another explicit query contract, not in a result DTO.

```php
final readonly class SomethingDTO implements \JsonSerializable
{
    public function __construct(
        public int    $id,
        public string $name,
    ) {}

    public function jsonSerialize(): mixed
    {
        return ['id' => $this->id, 'name' => $this->name];
    }
}
```

- `final readonly` — always
- Implements `\JsonSerializable`
- Collection DTOs implement `\IteratorAggregate` + `\JsonSerializable`:

```php
/** @implements \IteratorAggregate<int, SomethingDTO> */
final readonly class SomethingCollectionDTO implements \IteratorAggregate, \JsonSerializable
{
    /** @var list<SomethingDTO> */
    private array $items;

    /** @param list<SomethingDTO> $items */
    public function __construct(array $items) { $this->items = $items; }

    /** @return \ArrayIterator<int, SomethingDTO> */
    public function getIterator(): \ArrayIterator { return new \ArrayIterator($this->items); }

    public function jsonSerialize(): mixed { return $this->items; }
}
```

---

## 10. Repository Rules

### Command Repository Return Types

| Operation | Returns | Why |
|---|---|---|
| `create()` | `int` | `lastInsertId()` |
| `update()` | `bool` | `rowCount() > 0` |
| `updateStatus()` | `bool` | `rowCount() > 0` |
| `updateDisplayOrder()` | `bool` | delegated to `Maatify\Persistence\Pdo\Ordering\ScopedOrderingManager` from `maatify/persistence` |
| `updateImage()` | `bool` | `rowCount() > 0` |
| `softDelete()` | `bool` | `rowCount() > 0` |
| `hardDelete()` | `bool` | `rowCount() > 0` after transaction |
| `findById()` | `?DTO` | `null` if not found — Service decides whether to throw |

This table defines Repository-level return contracts. A Service MAY expose `updateDisplayOrder(...): void`, call the Repository operation, and convert `false` into the approved not-found exception according to Section 13. The Repository and Service signatures belong to different layers and MUST NOT be treated as competing alternatives.

### `findById` Pattern

```php
// Repository — returns null, never throws for not-found
public function findById(int $id): ?SomeDTO
{
    $stmt = $this->pdo->prepare('SELECT ... FROM maa_something WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);

    /** @var array<string, mixed>|false $row */
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        return null;
    }

    return $this->hydrateDetail($row);
}

// Service — throws NotFoundException when null
public function getById(int $id): SomeDTO
{
    $dto = $this->queryReader->findById($id);

    if ($dto === null) {
        throw SomethingNotFoundException::withId($id);
    }

    return $dto;
}
```

### Hydration — never cast `mixed` directly

```php
// ❌ PHPStan max rejects this
isActive:     (bool) ($row['is_active']     ?? false),
displayOrder: (int)  ($row['display_order'] ?? 0),

// ✅ extract first, check type, then cast
$isActive     = $row['is_active']     ?? null;
$displayOrder = $row['display_order'] ?? null;

isActive:     (is_int($isActive)     || is_string($isActive)) && (int) $isActive === 1,
displayOrder: (is_int($displayOrder) || is_string($displayOrder)) ? (int) $displayOrder : 0,
```

---

## 11. Shared Ordering and Pagination Utilities

This section governs how standalone Maatify packages consume shared PDO ordering and pagination capabilities. It is a dependency and integration policy, not an implementation blueprint.

The authoritative owner is:

```text
Package:    maatify/persistence
Repository: https://github.com/Maatify/persistence
```

### Domain Query and Pagination Ownership

The Package owns its domain query semantics, including searchable fields, filters, matching and escaping behavior, count semantics, and the public result contract. `maatify/persistence` owns only the reusable pagination and ordering mechanics exposed by its stable public API, as detailed below.

Management list/search collections that do not have a clearly bounded small maximum MUST provide pagination from the start. A Host MUST NOT load an unbounded result set and paginate it after retrieval. The Package owns the alignment of count and data queries and the meaning of each page; shared mechanics remain delegated to the stable `maatify/persistence` API.

### Non-Duplication Rule

A consumer package MUST NOT create a local replacement for stable ordering or pagination behavior provided by `maatify/persistence`.

Forbidden duplication includes:

- ordering managers, position-shifting algorithms, scope-locking logic, and ordering transaction behavior
- page and per-page normalization
- total and filtered count orchestration
- pagination offset calculation
- whitelist-based sorting and deterministic tie-breaker behavior
- canonical pagination result objects and metadata
- package-local copies of persistence exceptions or public value objects

A consumer package MUST NOT copy Runtime code, tests, internal algorithms, or documentation-only contracts from `maatify/persistence`.

### Capability Availability and Composer Dependency

A package may consume only an API that exists in a stable published release of `maatify/persistence`.

The consumer MUST:

- declare `maatify/persistence` as a direct Composer dependency when its public API is referenced
- require the minimum stable version that contains the needed capability
- confirm availability from the published package reference and stable release documentation
- avoid depending on an unreleased branch, commit, proposed contract, or target version as though it were an available Runtime API

A documentation-only architecture contract inside `maatify/persistence` establishes ownership and future implementation rules. It does not authorize another package to implement or consume the proposed API before a stable release publishes it.

### Ordering Integration

In this section, `Ordering` means reusable row-position and display-order management exposed by `Maatify\Persistence\Pdo\Ordering`. It does not include ordinary consumer-owned read-query sorting expressed through `ORDER BY`.

Reusable row-position and display-order operations covered by the stable `maatify/persistence` Ordering API MUST use:

```text
Maatify\Persistence\Pdo\Ordering
```

The consumer Repository / Service may:

- provide the PDO connection
- construct trusted ordering configuration from its own table and column identifiers
- pass domain ids, scope values, and requested positions
- translate the stable persistence result into its own existing public contract through a thin adapter

The consumer MUST NOT reproduce ordering SQL shifts, transaction ownership, scope locking, identifier validation, or persistence exception behavior locally.

**Integration Boundaries:**
- `getNextPosition()` does not start a transaction or acquire a lock. When concurrency correctness is required, the caller owns both the transaction and the appropriate locking strategy. It alone returns `MAX + 1`.
- `moveWithinScope()` owns a transaction and refuses to run in an active PDO transaction.
- Non-positive movement values are rejected before clamping. Clamping is to the maximum existing position.
- Soft-delete filtering is supported when `deletedAtColumn` is configured and disabled when it is `null`.
- Do not duplicate full public signatures; refer to `maatify/persistence`.

### Pagination Integration

Paginated Repository / QueryReader operations MUST delegate reusable pagination mechanics to the stable public API under:

```text
Maatify\Persistence\Pdo\Pagination
```

`PdoPaginator` does not own a transaction and can run inside a caller-owned transaction without modifying its state.

The consumer remains responsible for domain-owned concerns:

- mandatory security, tenant, ownership, visibility, and soft-delete constraints
- optional search and filter construction
- JOINs and selected columns
- trusted SQL and matching parameter values
- semantic alignment between count and data queries
- row mapping into the consumer's array or DTO
- preserving its own existing public response contract

`maatify/persistence` owns the reusable mechanics exposed by its stable API, including normalization, count execution, deterministic sorting, limit/offset handling, mapper invocation, and canonical pagination metadata.

Adopting the shared paginator MUST NOT silently change an existing endpoint, DTO, or package return shape. A thin consumer-owned adapter MAY preserve an established public contract.

### Thin Adapters

A consumer-owned adapter is permitted only when it:

- translates domain inputs into the stable `maatify/persistence` API
- delegates the shared operation without reimplementing its algorithms
- maps the returned result into an already approved consumer contract
- adds no competing exception hierarchy, pagination engine, or ordering engine

An adapter MUST NOT become a fork of the shared capability.

### Missing Capability

When a required reusable behavior is absent from the stable public API, the default action is to propose and review the capability in `maatify/persistence`.

A package-local alternative is forbidden unless an explicit owner-approved architectural decision proves that the behavior is domain-specific and outside the shared package's scope.

Temporary copying of an unpublished persistence contract is not an acceptable workaround.

### Source of Truth

Consumer packages MUST follow the stable public API, package reference, and published integration documentation in `maatify/persistence`.

Exact class signatures, internal formulas, SQL assembly rules, exception classifications, and verification requirements belong in the owning repository. They MUST NOT be duplicated in this general package-building standard or independently redefined by consumer packages.

---

## 12. Translation Pattern

Translation support is **CONDITIONAL**, not a universal Package requirement. A Package MUST implement translation only when translation is part of its actual domain contract. Packages without a translation requirement MUST NOT add translation APIs, fields, persistence, joins, or fallback behavior solely to conform to this Standard.

### Domain-Owned Translation Contract

When translation is part of the domain, the Package Reference and Architecture for that Package are authoritative for:

- ownership of localized fields
- translation identity
- fallback semantics
- mutation semantics
- query contracts, including result cardinality, filtering, and search behavior

The general Package Standard must not force a package to invent localized base fields, fallback behavior, actor-specific query shapes, or mutation semantics that are not part of its domain contract.

Accordingly, this Standard does not require any particular translation implementation, including:

- a base localized value
- `listWithoutTranslation()`
- `language_id` specifically
- `COALESCE(translated, base)` fallback
- Upsert as the only mutation
- fixed Admin/Customer query shapes
- fixed search fields
- comparison with a base name

### Unified Same-Shape Content Pattern

Translation remains optional. When localized and non-localized values are the same concept with the same shape, a unified content relation that supports `language_code = NULL` and non-NULL language codes is the preferred pattern over a base relation plus a structurally duplicate translations relation.

`NULL` represents the exact NULL-language scope; it MUST NOT mean fallback to or from a specific language unless the domain contract says so explicitly. A separate translation relation remains appropriate when structure, lifecycle, identity, or cardinality materially differs. This preference MUST NOT create a translation capability in a domain that does not need one.

### Host-Owned Language Identity

If language or locale identity is owned by the Host, the Package MUST NOT create a foreign key to, or a JOIN with, Host tables, in accordance with the Package isolation rules. The Package must use only the identity and integration contract exposed to it without coupling its persistence to Host table structure.

### Translation Query Cardinality

Any query whose contract promises one row per entity MUST constrain the translation relation to one logical translation identity before joining, or avoid joining translations. An unrestricted JOIN that can return multiple translations for one entity is not permitted for a single-row-per-entity contract. A query may return multiple translations only when its contract explicitly defines a translation collection and its result shape supports that cardinality.

### Translation Persistence Invariants

If the Package owns persistence for translations:

- the logical translation identity MUST be documented in the Package Reference or Architecture
- the persistence schema MUST enforce that identity with uniqueness constraints appropriate to the domain contract
- the mutation behavior MUST follow the domain contract; this Standard does not prescribe Upsert or any other single mutation strategy

---

## 13. Service Rules

Responsibility:
- **Business orchestration** lives in Services
- **Input validation** lives in Commands; query filters are validated by their `Criteria` or query contract
- **SQL** lives in Repositories or package-local/domain-local SQL support builders
- **Display formatting** does not belong in Repositories or query layers; those layers return raw domain/database values for the Host's presentation layer.

```php
// Command service — throws NotFoundException when repo returns false
public function update(UpdateSomethingCommand $command): void
{
    $updated = $this->commandRepo->update($command);
    if (! $updated) {
        throw SomethingNotFoundException::withId($command->id);
    }
}

// Query service — throws NotFoundException when repo returns null
public function getById(int $id): SomethingDTO
{
    $dto = $this->queryReader->findById($id);
    if ($dto === null) {
        throw SomethingNotFoundException::withId($id);
    }
    return $dto;
}
```

Services **never**:
- Contain raw SQL
- Catch exceptions (let them propagate)
- Instantiate repositories directly (use constructor injection)

---

## 14. Admin vs Customer Separation

Some business modules may choose actor-specific namespaces (e.g., `Admin\` vs `Customer\`).

However, infrastructure packages such as `{PACKAGE_SLUG}` should use their actual package/domain boundaries instead. For example, `{DomainOne}` and `{DomainTwo}` may serve as public architectural boundaries rather than arbitrary Admin/Customer folders.

---

## 15. Read / Admin Query API Rules

Packages that have persisted data intended to be viewed, searched, audited, monitored, or reported by host applications should expose framework-agnostic PHP read/query contracts where applicable.

When management and consumption are distinct domain use cases, the Package MUST expose a Management API and a Consumer API with separate contracts. Management APIs may support administrative search, filters, statuses, deleted-state visibility, and pagination. Consumer APIs expose only records valid under the Package's domain visibility invariants; the Host MUST NOT be left to reproduce those invariants through generic filters. If the two uses are not meaningfully distinct, a Package MUST NOT create duplicate APIs merely to satisfy this pattern.

**Important:** This refers strictly to **PHP-level APIs** (e.g., PHP interfaces and DTOs), not HTTP APIs.

These contracts may cover (where applicable and appropriate for the package's domain):
- Admin listing
- Search
- Dashboard summaries
- Reporting summaries

**Explicitly forbidden inside the package:**
- HTTP controllers
- Routes
- Middleware
- Permissions
- UI dashboards
- CSV/PDF/Excel exports
- Host-specific actor/name resolution
- JOINs/FKs on host tables

Remember to uphold the core principles: maintain a standalone, framework-agnostic, and host-agnostic architecture, prioritize domain boundaries over mandatory Admin/Customer folder structures, and ensure all public query capabilities have matching PHP contracts/interfaces.

---

## 16. display_order Rules

When persisted entities expose mutable row-position or display-order behavior, the package MUST consume the stable public Ordering API from `maatify/persistence`.

The package-level integration contract is:

- `display_order` MUST NOT be accepted by `CreateCommand` or `UpdateCommand`
- creation-time position assignment MUST delegate to the stable Ordering API when automatic assignment applies
- movement MUST be exposed through a dedicated package operation rather than a generic update command
- the Command Repository operation MUST return `bool` to report whether the target row existed and was moved
- a Service MAY expose a `void` operation and convert a Repository `false` result into the approved not-found exception
- shifting, clamping, scope locking, transaction ownership, identifier validation, and persistence exception behavior MUST remain delegated to `maatify/persistence`
- a consumer MUST NOT reproduce or fork the Ordering engine locally

Exact class names, method signatures, transaction behavior, and Runtime semantics are owned by the stable `maatify/persistence` public API and [PERSISTENCE_PACKAGE_REFERENCE.md](https://github.com/Maatify/persistence/blob/main/PERSISTENCE_PACKAGE_REFERENCE.md).

### Deferred Hard-Delete Ordering Compaction

The stable `maatify/persistence` Ordering API currently does not expose a hard-delete compaction operation.

The package-specific decision to preserve scoped ordering compaction as a future candidate is recorded in [ADR 0002 — Ordering Hard-Delete Compaction](https://github.com/Maatify/persistence/blob/main/docs/adr/0002-ordering-hard-delete-compaction.md). Its status is `Accepted — Deferred`. Compaction is not implemented, has no stable API or release target, and is not part of Pagination `v1.1.0`.

Until a stable Runtime API is separately approved, implemented, released, and recorded in [PERSISTENCE_PACKAGE_REFERENCE.md](https://github.com/Maatify/persistence/blob/main/PERSISTENCE_PACKAGE_REFERENCE.md):

- consuming projects retain ownership of entity deletion and project-specific hard-delete orchestration
- consumers MUST NOT claim or depend on an unreleased Persistence compaction API
- this Standard does not prescribe a method signature, locking strategy, transaction-participation contract, or release target for the deferred capability
- the deferred decision MUST NOT be treated as an expansion of the `v1.1.0` Pagination scope

---

## 17. Image and Media Rules

Image and media support is conditional. A domain MUST NOT be required to introduce an image field or Media subsystem merely to comply with this Standard.

### Simple Image Value

A scalar path or URL is appropriate only when the domain treats the image as a simple value and does not require media lifecycle, roles, scopes, ordering, a default assignment, processing, or storage ownership. In that case the Package Reference defines the value and mutation semantics. This Standard does not impose `VARCHAR(255)`, null-clearing behavior, or a dedicated operation as universal rules.

### External Media Asset and Domain Assignment

When the domain requires media lifecycle, roles, scopes, ordering, default assignments, processing, or explicit storage ownership, it MUST use an external Media Asset and domain-assignment boundary. The Host/Media system owns upload, storage, processing, and media lifecycle. The Package stores only a stable external media identity (for example, `media_asset_id`) and MUST NOT add Host foreign keys, joins, repositories, or module dependencies to resolve it. The Package owns the assignment semantics it needs, such as role, exact scope, ordering, default, and assignment lifecycle.

Assignment identity fields MUST remain stable under generic updates. A role registry is Package-owned only when role identity or its lifecycle/status is part of the Package's domain invariants. Nullable assignment scopes follow the exact-scope rule in Section 24; `NULL` MUST NOT imply a wildcard or fallback.

---

## 18. Bootstrap / DI Rules

**Composer packages must not require host-specific bindings.**

Rules:
- No Slim/Laravel/Symfony/PHP-DI bindings as package requirements.
- A framework-neutral Factory or Builder is appropriate when internal wiring is non-trivial; a thin public Facade/API may be appropriate when multiple public capabilities make discovery or construction materially clearer.
- These construction patterns are conditional, not boilerplate. A Factory MUST NOT become a Service Locator, and neither a Factory nor a Facade may depend on a framework container.
- Optional Providers, when useful, must be strictly framework-agnostic.
- Host applications are fully responsible for wiring dependencies through their own container or runtime environment.

---

## 19. Decimal / Financial Rules

- All monetary values stored as `string` (DECIMAL precision — never `float`)
- All arithmetic uses `bcmath` — never native PHP arithmetic on monetary values
- Validate decimal format **before** any `bcmath` call:

```php
if (! preg_match('/^\d+(?:\.\d{1,4})?$/', $value)) {
    throw SomethingInvalidArgumentException::invalidDecimal($field, $value);
}
```

- Always pass explicit scale: `bcadd($a, $b, 4)`, `bcmul($a, $b, 4)`, `bcdiv($a, $b, 8)`

---

## 20. PDO Named Placeholder Rule

PDO does not reliably support the same named placeholder more than once per statement.
Every placeholder must appear exactly once per SQL string.

When the same value is needed in multiple subqueries:

```php
// ❌ WRONG — same placeholder used twice
AND gc.country_code = :country_code  -- subquery 1
AND gc.country_code = :country_code  -- subquery 2

// ✅ CORRECT — unique names, same value
AND gc_al.country_code = :country_code_allow   -- allowlist subquery
AND gc_bl.country_code = :country_code_block   -- blocklist subquery

$params['country_code_allow'] = $countryCode;
$params['country_code_block'] = $countryCode;
```

---

## 21. PHPStan and Testing Rules

### `phpstan.neon`

```neon
parameters:
    level: max
    paths:
        - src
```

The `src` path is mandatory.

When a `tests/` directory exists, it MUST also be included in `parameters.paths`:

```neon
parameters:
    paths:
        - src
        - tests
```

Repositories MUST configure PHPStan from their actual package-owned paths and MUST NOT reference a non-existent path merely to copy this example. Existing package-owned tests MUST NOT be excluded from static analysis.
No PHPStan baseline, `ignoreErrors`, or inline suppression is permitted merely to make CI green.

### Final Classes and Test Doubles

- Test Doubles MUST target interfaces or replaceable contracts by default, not concrete `final` classes.
- A `final` keyword MUST NOT be removed from a class solely to facilitate testing.
- When testing a class that depends on a concrete final class:
  1. Use a real instance if it is deterministic and suitable for testing.
  2. Or test via a Mock/Fake for the collaborators or external boundaries that it depends on.
  3. Create an interface only when replaceability is a true runtime requirement, not just to satisfy a single test.
- Production design must not be weakened solely for testing; `final` and `readonly` must not be removed merely to make mocking easier.
- Interfaces must not be invented solely for a one-off test when runtime replaceability is not a real architectural requirement.
- Prefer testing through real deterministic instances or proper replaceable collaborators/contracts.
- The package `dg/bypass-finals` is NOT a Central Baseline and NOT a general requirement for Maatify projects.
- `dg/bypass-finals` MAY be used as a development-only test tool when a repository has a legitimate documented need to test/mock concrete final classes and that choice is consistent with its test architecture.
- Its use must never justify PHPStan suppressions, baselines, or weakening production architecture.

### Testing Strategy

- The core testing strategy and regression-protection rules are exclusively governed by the canonical [Testing Standard](../testing/TESTING_STANDARD.md).
- For code with testable behavior, appropriate automated tests are required.
- Packages that own persistence, database, or external-service behavior MUST define appropriate Integration coverage. Unit and Regression suites remain required where applicable.
- Package-owned test behavior, fixtures, and suite responsibilities belong to the package architecture and reference documentation.
- CI execution requirements — including real-service provisioning, MySQL/SQLite enforcement, PHP matrices, cleanup/repeatability checks, and example syntax validation — are governed exclusively by [`CI_WORKFLOW_STANDARD.md`](CI_WORKFLOW_STANDARD.md).
- Package readiness requires the complete maintained and applicable test suite to be covered by the appropriate CI quality gate, using the repository's actual maintained test runner and tooling. CI execution and failure enforcement are governed by [`CI_WORKFLOW_STANDARD.md`](CI_WORKFLOW_STANDARD.md).

### PDO fetch results — always annotate

```php
/** @var array<string, mixed>|false $row */
$row = $stmt->fetch(PDO::FETCH_ASSOC);

/** @var list<array<string, mixed>> $rows */
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
```

### DTO and Hydration Annotations

Collection generics and list annotations are governed by Section 9. PDO-row hydration and mixed-value extraction rules are governed by Section 10. This section MUST NOT redefine those contracts.

### LIMIT / OFFSET — always PDO::PARAM_INT

```php
// ❌ PDO binds as string by default
$stmt->bindValue(':limit', $limit);

// ✅ explicit type
$stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
```

### Private method annotations

```php
/** @param array<string, mixed> $row */
private function hydrateListItem(array $row): ?SomeListItemDTO

/** @param array<string, mixed> $row */
private function hydrateDetail(array $row): ?SomeDTO

/**
 * @param  list<array<string, mixed>>  $rows
 * @return list<SomeDTO>
 */
private function hydrateAll(array $rows): array

/** @return list<string> */
private function fetchRelatedItems(int $parentId): array

/** @return array<string, mixed>|null */
private function findRawById(int $id): ?array
```

---

## 22. CI Workflow Rules

Every new standalone Composer package in the Maatify ecosystem must adhere to the CI workflow standards defined in [`CI_WORKFLOW_STANDARD.md`](CI_WORKFLOW_STANDARD.md).

`CI_WORKFLOW_STANDARD.md` is the single detailed source of truth for workflow architecture, path relevance, dependency resolution, PHP compatibility matrices, quality checks, real-service Integration execution, security audit, workflow linting, permissions, immutable action pinning, execution reliability, and stable required gates.

Compliance requires the repository's CI to pass the current Compliance Checklist defined by that Standard. This Package Building Standard MUST NOT independently redefine those CI mechanics.

---

## 23. The Package Is NOT Done Until

- [ ] CI workflows exist, pass, and satisfy the current Compliance Checklist in `CI_WORKFLOW_STANDARD.md`
- [ ] Package-owned runtime and test architecture is represented in CI where applicable
- [ ] `README.md`, `CHANGELOG.md`, and other release-facing files comply with `LIBRARY_PRESENTATION_STANDARD.md`.
- [ ] `{PACKAGE}_PACKAGE_REFERENCE.md` complete — full API, design rules, extension guide
- [ ] `composer.json` complies with [COMPOSER_PACKAGE_STANDARD.md](COMPOSER_PACKAGE_STANDARD.md).
- [ ] The consumer workflow and examples meet the requirements in Section 25.
- [ ] The standalone Package has the reproducible Consumer Verification Harness required by [TESTING_STANDARD.md](../testing/TESTING_STANDARD.md).
- [ ] Every public service/repository capability intended for infrastructure substitution has a matching contract (interface)
- [ ] Domain-specific failure semantics are documented
- [ ] Transaction catch blocks rethrow the original `\Throwable` after rollback — never swallow
- [ ] Business orchestration lives in Services, validation in Commands/filters, SQL in Repositories
- [ ] Schema/persistence documentation aligns with the package's declared persistence backend and domain-owned storage structures, with no generic `logs` or `event_logs` storage
- [ ] Framework-agnostic boundaries preserved: no host app namespaces, no framework bindings required
- [ ] No generic logger, recorder, or repository
- [ ] Docs reflect current exception rules, package-defined exceptions use `maatify/exceptions`, and any clock/date-time contract uses `maatify/shared-common` instead of a local duplicate

## 24. Domain Ownership, External References, and Lifecycle

The Package Reference or Architecture MUST make ownership explicit for each domain capability and persisted concept:

- what the Package owns and enforces as a domain invariant
- what the Host owns
- which values are stored only as stable external identities
- which external concepts the Package does not interpret or manage the lifecycle of

A Package MUST create an internal Registry only when the identity, lifecycle, status, or validity of that concept is part of the Package's own invariants. A Host-owned concept that the Package does not own is represented by a stable external ID or key; the Package MUST NOT acquire ownership merely because it stores or receives that value.

Standalone Packages and Base Modules MUST NOT depend on Host foreign keys, Host-owned table joins, Host repositories, or Host modules. Where the Package does not own semantic validation of an external identity, that validation remains with the Host.

Nullable scope fields use exact-scope semantics: `NULL` means only the exact NULL scope. It MUST NOT mean wildcard, fallback, all, default, any language, or any platform unless the domain contract explicitly defines that meaning.

For each entity or assignment, the Package Reference MUST distinguish stable identity fields from mutable business fields, lifecycle fields, and ordering fields. Generic updates MUST NOT change stable identity. Soft Delete is optional and SHOULD be used only when domain history, restore behavior, or identity invariants require it; it is not a universal capability. When a lifecycle is used, its identity, uniqueness, restore, and deletion semantics MUST be documented.

## 25. Runtime Workflow, Transactions, Concurrency, and Clock

Each reusable Package MUST document a realistic consumer workflow in its Package Reference, Architecture, or practical Usage Guide. The documented workflow MUST show the consumer path:

```text
Host Input → Public API → Domain Service → Integration Boundary → Observable Result
```

Examples MUST demonstrate supported construction/wiring, a basic workflow, public API use, and the applicable integration boundaries. They illustrate the contract; the Package Reference and this Standard remain the sources of normative rules.

When a domain invariant spans multiple operations, the Package MUST identify the transaction owner and the required transaction, locking, and concurrency boundaries. It MUST state whether an outer transaction is supported when that affects callers. Race-prone invariants—such as ordering, hierarchy, unique defaults, or lifecycle transitions—require concurrency verification when concurrent access is realistic for the domain. The Testing Standard owns the general testing evidence model.

Packages MUST NOT change the global timezone. When the Package needs a clock abstraction, it MUST use the established Clock contract described in Section 2. The Host owns timezone policy unless the domain contract explicitly assigns a different policy to the Package. Repositories MUST NOT silently reinterpret timestamps.

## 26. Extensible Content Fields Pattern

Extensible content fields are conditional and are appropriate only when a domain needs variable content fields alongside its stable core fields. They MUST NOT be added merely to anticipate possible future extension.

When this pattern applies, the domain contract may define a stable `field_key`, a value and typed format, exact optional scopes, deterministic ordering, lifecycle, and uniqueness according to actual domain invariants. Identity fields and scopes that define assignment identity MUST remain stable under generic updates.

The Host owns the meaning and administration of `field_key`, including editor configuration, sanitization, rendering, and fallback, unless the Package's own domain explicitly owns those semantics. The Package MUST NOT create a Field Definition Registry solely because it stores a `field_key`; the Registry rule in Section 24 still applies.
