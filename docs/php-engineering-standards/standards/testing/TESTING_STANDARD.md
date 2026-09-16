# TESTING_STANDARD

**Maatify Testing Architecture and Regression Protection Standard**

## Standard Metadata

- **Standard ID:** `std-testing`
- **Standard Version:** `1.1.0`
- **Standard Version Format:** `MAJOR.MINOR.PATCH`

This document establishes the canonical, repository-wide Testing Standard for the Maatify ecosystem. Its primary purpose is to protect implemented behavior from regressions and unintended damage during future development.

---

## 1. Core Principle

> Every externally observable behavior, critical workflow, integration boundary, and resolved regression MUST be protected by an appropriate end-to-end or system-level test. A change MUST NOT be considered complete when its correctness depends solely on unit-level verification.

This is a mandatory engineering rule, not a suggestion.

---

## 2. Normative Language

The key words **MUST**, **MUST NOT**, **REQUIRED**, **SHALL**, **SHALL NOT**, **SHOULD**, **SHOULD NOT**, **RECOMMENDED**, **MAY**, and **OPTIONAL** in this document are to be interpreted as described in RFC 2119.

---

## 3. Testing Layers

The testing strategy MUST distinguish between the following layers:

### 3.1. Unit Tests

Unit tests verify isolated logic and components.

Their purpose includes:
- Logic correctness.
- Edge case handling.
- Deterministic isolated behavior.
- Fast developer feedback.

Unit tests alone MUST NOT be treated as sufficient proof for externally observable workflows where integration or system behavior matters.

### 3.2. Integration Tests

Integration tests cover actual collaboration between components or infrastructure boundaries.

Examples include:
- Service + Repository collaboration.
- Persistence behavior.
- Database interactions.
- Adapters and infrastructure.
- Serialization/deserialization.
- Framework integration.

While mocks or fakes MAY be useful at some test levels, a mocked dependency chain MUST NOT be described as end-to-end or system-level verification.

### 3.3. End-to-End (E2E) / System-Level Tests

System-level and E2E tests are defined by **behavioral boundary**, not by technology. E2E does NOT automatically mean browser testing.

The test MUST exercise the system through an externally meaningful or public entry point and verify the final observable result across the relevant real execution chain.

Examples:
- **API/Module:** `HTTP Request → Route → Handler → Service → Repository → Persistence → Response`
- **Standalone Library:** `Public API → Internal Implementation → Required Integration Boundary → Observable Result`
- **CLI Application:** `CLI Command → Application/Service Layer → Infrastructure → Exit Code/Output/State`
- **Admin/Web UI:** `Browser/User Action → Frontend → HTTP → Backend → Persistence/State → Final User-Visible Result`

Browser automation is REQUIRED only when the browser or UI itself is part of the behavior being protected.

### 3.4. Consumer Verification Harness

Every standalone reusable Package, and every Base Module intended to be extractable as a Package, MUST have a reproducible Consumer Verification Harness.

The Harness is external-consumer evidence, not merely another test suite run with the Package repository as the root project. It MUST:

- use a Composer root separate from the Package root or Base Module Artifact Root, and resolve and use the artifact as a Composer dependency
- exercise the artifact through its production PSR-4 autoload and documented public API or public contracts
- complete a realistic consumer workflow from input through the public API and applicable integration boundary to an observable result
- run successfully at least twice from clean consumer states; each run MUST be repeatable without relying on prior `vendor/`, generated state, database state, or other leftover environment state

A Harness MAY be a fixed consumer project, a fixture/template, or a deterministic script that creates a clean consumer project. Regardless of its form, it MUST NOT bypass Composer with direct `require`/`include` of `src/` files, depend on the artifact's `autoload-dev`, test namespace, test bootstrap, internal test fixtures, Host namespace, or Host autoload configuration, or access internal implementation details instead of documented public contracts. For a Base Module, the Harness MUST consume the Module's Artifact Root itself as the dependency; the Host root is not a substitute.

The proof MUST include Composer installation/resolution, production autoload, public API usage, the realistic workflow, its observable result, and the absence of hidden Host dependencies. Observable results are domain-specific and MAY include a returned public result/DTO, persisted state, a public effect, or a documented exception/failure contract; no single result type is required for every Package.

Persistence, schema/install assets, transactions, Clock, external services, cleanup, and other integration boundaries MUST be covered when they apply to the artifact's behavior, and MUST NOT be imposed on artifacts that do not need them. When the artifact owns persistence, the Harness MUST prove the relevant real persistence boundary; unit mocks alone are insufficient. When the domain has race-prone invariants and concurrent access is realistic, suitable concurrency verification MUST be included; unit mocks alone are insufficient for that proof.

The Harness is an additional external-consumer proof layer. It MUST NOT replace applicable Unit, Integration, or System/E2E coverage. System/E2E boundaries remain behavior-based as defined in Section 3.3, so browser automation is required only when browser/UI behavior is part of the contract. A Consumer Verification Harness also does not replace Real Host Validation or define release eligibility.

---

## 4. Regression Protection Rules

Future development MUST NOT rely only on implementation-level tests.

### 4.1. Existing Behavior Protection
A refactor or new feature MUST NOT silently break already-supported observable behavior. Existing system/E2E tests SHOULD act as regression contracts.

### 4.2. New Observable Behavior
New externally observable behavior MUST receive appropriate system/E2E protection before the work is considered complete.

### 4.3. Critical Workflows
Critical workflows MUST have system-level coverage even when their internal components already have unit tests.

### 4.4. Integration Boundaries
Where correctness depends on multiple components working together, an appropriate integration test MAY complement the coverage, but it MUST NOT replace required system/E2E protection where the Testing Standard requires it.

### 4.5. Resolved Bugs/Regressions
A fixed regression MUST be accompanied by a test capable of detecting recurrence. Prefer the narrowest useful regression test, but when the defect affected an externally observable or critical workflow, ensure the relevant system/E2E path is protected as well.

### 4.6. Internal-Only Refactors
If an internal refactor does not create or change externally observable behavior, and existing E2E/system coverage already protects the behavior, that existing coverage MAY be sufficient. The rule is about behavioral protection, not test-count inflation. You are NOT required to write a new E2E test for every trivial internal change.

---

## 5. Completion and Readiness Semantics

Implementation completion REQUIRES the appropriate combination of testing layers.

A task, part, or phase MUST NOT be considered technically complete merely because:
- Unit tests pass.
- Static analysis passes.
- Individual classes are tested.
- Mocks reproduce the expected calls.

Where the feature depends on a real workflow or integration chain, appropriate system-level verification MUST also pass.

For artifacts covered by Section 3.4, completion and readiness also require the Consumer Verification Harness evidence defined there.

---

## 6. Adoption Status
يصبح معتمدًا عند دمجه في الفرع الافتراضي للمشروع.
