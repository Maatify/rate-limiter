# Rate Limiter vNext Architecture Direction — Draft

## Status

**Draft — architecture direction only.**

This document defines the intended direction for the next major evolution of `maatify/rate-limiter`.

It is deliberately **not** an implementation plan, migration checklist, release plan, or compatibility promise. Its purpose is to establish the architectural target first, so that the implementation can later be decomposed into explicit stacked phases with clear acceptance criteria.

This Draft is governed by [`RATE_LIMITER_VNEXT_EXECUTION_GOVERNANCE.md`](RATE_LIMITER_VNEXT_EXECUTION_GOVERNANCE.md). Pinned standards adoption preparation/resolution, standards compliance, and Draft-bound execution are mandatory closure conditions before this Draft may merge into `main`; canonical adoption becomes effective when the prepared adoption state reaches the default branch.

---

## 1. Context

The current standalone package already has valuable distribution and integration qualities:

- Composer-first standalone packaging.
- PSR middleware integration.
- Resolver / driver packaging.
- Redis, MongoDB, and MySQL adapters in one package.
- CI, tests, static analysis, and Packagist-oriented distribution structure.

Separately, a **Production-Validated Reference Module** has been exercised inside a real application and has demonstrated a substantially more advanced security model than the current standalone engine.

The objective of vNext is **not** to choose one implementation and discard the other.

The objective is to combine:

> **the stronger security engine of the Production-Validated Reference Module**
>
> with
>
> **the stronger package, integration, and distribution surface of the standalone library**.

The resulting package must become the canonical reusable Rate Limiter capability for the Maatify ecosystem.

---

## 2. Core Decision

`maatify/rate-limiter` remains the **canonical standalone package**.

The Production-Validated Reference Module is treated as the architectural reference for the vNext security engine, not as an application dependency and not as something to copy blindly.

The final package must be reusable independently of the application in which the reference module was proven.

Application-specific policies, wiring, telemetry, routes, authentication flows, and product behavior must remain outside the standalone package.

---

## 3. Architectural Principle: Dependency-Clean, Not Dependency-Free

The package is **not required to avoid dependencies**.

It is required to avoid dependencies that do not provide meaningful architectural value.

Dependencies on foundational Maatify libraries are acceptable — and preferred — when they prevent duplication of important primitives or provide shared ecosystem contracts.

Examples that may be appropriate after contract-level review include:

- `maatify/common` / shared common primitives.
- `maatify/crypto` for privacy-preserving hashing, HMAC, key derivation, or other cryptographic primitives.
- `maatify/persistence` where its abstractions can satisfy the actual atomic persistence semantics required by the Rate Limiter.

The rule is:

> **Reuse ecosystem foundations when they provide a real capability; do not add dependencies for convenience-only helpers.**

A dependency must fall into at least one of these categories:

1. **Foundation dependency** — a cross-ecosystem primitive that should not be reimplemented locally.
2. **Capability dependency** — a library that provides a meaningful capability required by the Rate Limiter.
3. **Integration dependency** — an optional dependency required only for a specific adapter or integration surface.

Convenience-only dependencies should be rejected.

---

## 4. vNext Security Baseline

The Production-Validated Reference Module establishes the desired security baseline for the new engine.

The target architecture should be capable of supporting, where applicable:

- Multi-signal rate limiting rather than a single IP/key counter.
- IP-prefix based signals.
- User-agent correlated signals.
- Device-aware signals.
- Account-aware signals.
- Account + device correlation.
- Credential-spray resistance.
- Distributed account-attack resistance.
- Device / fingerprint dilution resistance.
- NAT, VPN, mobile-network, and shared-IP false-positive reduction.
- Hierarchical IPv6 aggregation.
- Progressive penalties.
- Penalty decay.
- Account-wide persistence where policy requires it.
- Fixed budget epochs.
- Hard budget caps.
- Anti-equilibrium / near-threshold abuse resistance.
- Bounded state and key-explosion resistance.
- Explicit storage-failure semantics.
- Circuit-breaker behavior.
- Local fallback protection where configured.
- Privacy-aware identifier construction.
- Deterministic and auditable policy evaluation.

These capabilities define the **direction of the engine**, not a requirement that every consumer enable every capability.

The library should support policy composition so simple consumers can remain simple while security-sensitive consumers can enable the advanced model.

---

## 5. Separation of Concerns

The package should be divided conceptually into the following layers.

### 5.1 Core contracts and DTOs

Framework-independent contracts and immutable data structures.

Examples of responsibilities:

- Rate-limit request/context representation.
- Policy definition.
- Decision/result representation.
- Retry metadata.
- Storage contracts.
- Clock dependency.
- Signal identity contracts.
- Capability declaration.

### 5.2 Engine

The engine owns deterministic evaluation and orchestration.

It should coordinate:

- Signal construction.
- Policy evaluation.
- Budget evaluation.
- Correlation.
- Penalty calculation.
- Storage interaction.
- Failure semantics.
- Final decision construction.

The engine must not depend on HTTP framework objects.

### 5.3 Signal / identity layer

Responsible for normalized, privacy-aware rate-limit identities such as:

- IP prefix.
- IP prefix + user agent.
- IP prefix + device fingerprint.
- Account identifier.
- Account identifier + device fingerprint.

Cryptographic transformation of sensitive identifiers should be delegated to an appropriate shared crypto capability rather than implemented ad hoc when a suitable ecosystem primitive exists.

### 5.4 Correlation and penalty layer

Responsible for higher-order rate-limit behavior such as:

- Cross-signal correlation.
- Progressive penalties.
- Decay.
- Budget exhaustion.
- Near-threshold / anti-equilibrium handling.

This remains part of rate limiting when it directly contributes to deterministic throttling decisions.

Higher-level product risk intelligence, challenge orchestration, fraud scoring, or broader abuse-protection workflows remain outside this package.

### 5.5 Failure semantics

Storage failure must be a first-class architectural concern.

The engine should be able to represent explicit modes such as:

- `FAIL_CLOSED`
- `FAIL_OPEN`
- `DEGRADED_MODE`

Circuit-breaking and local fallback behavior may participate in these modes, but the resulting behavior must remain explicit and testable.

### 5.6 Middleware and integration layer

PSR middleware remains a package feature, but it must sit **above** the framework-independent engine.

The middleware is an adapter from HTTP request/response concerns into the Rate Limiter engine; it is not the architecture of the engine itself.

---

## 6. PSR Middleware Must Remain

vNext should preserve and improve the package's PSR integration surface.

The target remains:

- PSR-7 compatible request/response handling where relevant.
- PSR-15 middleware integration.
- No direct framework coupling inside the core engine.
- Application-provided context extraction where domain-specific identifiers are required.

A framework integration should be replaceable without changing the underlying rate-limit engine.

---

## 7. Resolver / Driver Packaging Must Remain

The current package's resolver / driver usability is valuable and should be preserved conceptually.

However, vNext should avoid treating all storage backends as equivalent merely because they share method names.

The resolver should select a compatible storage implementation based on configuration and declared capabilities.

The package must prefer **truthful capability modeling** over a lowest-common-denominator abstraction.

Possible capability boundaries may include concepts such as:

- Atomic counter storage.
- Fixed-window / epoch budget storage.
- Temporary block storage.
- Health-aware storage.
- Compare-and-update / conditional mutation.
- Native expiry support.

The final contracts must be decided only after auditing the actual semantics available through the supported backends and shared persistence libraries.

---

## 8. Redis, MongoDB, and MySQL Adapters Must Remain Available

The standalone package should continue to provide first-class adapters for:

- Redis.
- MongoDB.
- MySQL / PDO-compatible relational persistence where appropriate.

Keeping them in the same package is acceptable.

What is **not** acceptable is pretending that all backends provide identical guarantees when they do not.

Each adapter must either:

1. satisfy the contract's required semantics, or
2. explicitly declare that a capability is unsupported.

The engine/resolver must be able to reject an invalid policy/backend combination rather than silently weaken the security model.

---

## 9. Atomicity Is a Security Contract

For vNext, atomic storage behavior is not an implementation detail.

Where the engine requires operations such as:

- increment and create-if-absent,
- TTL assignment only on initial creation,
- fixed epoch creation,
- budget consumption,
- conditional block creation,
- counter + expiry mutation,

those operations must have well-defined atomic semantics.

For Redis, this may require Lua scripts or an equivalent atomic primitive rather than separate commands.

For MongoDB and MySQL, the implementation must use backend-native atomic/transactional primitives that preserve the same contract, or declare the unsupported capability.

Backend conformance must be tested against semantic behavior, not merely against common interface method names.

---

## 10. Current Package Strengths to Preserve

The vNext effort must not regress the existing package's useful distribution characteristics.

The target package should retain or improve:

- Composer installation.
- Clear public API.
- Resolver-based setup where useful.
- PSR middleware integration.
- Redis adapter.
- MongoDB adapter.
- MySQL adapter.
- PHPUnit coverage.
- PHPStan at the project's maximum accepted level.
- CI gates.
- Composer validation.
- Packagist readiness.
- SemVer release discipline.
- Documentation and usage examples.

The vNext redesign is therefore a **security-engine upgrade**, not a retreat from standalone-library usability.

---

## 11. Adapter Conformance Testing

A dedicated backend conformance suite should become a release-level requirement.

The same semantic scenarios should be executed against every backend that claims the corresponding capability.

Examples include:

- First increment creates a window with the correct expiry.
- Subsequent increments do not accidentally extend a fixed window.
- Concurrent increments cannot lose updates.
- Budget epochs do not drift.
- Temporary blocks expire correctly.
- Retry-after values remain consistent with stored state.
- Reset behavior is deterministic.
- Storage failures are surfaced into the configured failure mode.

This prevents a common interface from hiding materially different backend behavior.

---

## 12. Package Shape — Directional Only

A likely conceptual structure is:

```text
src/
├── Contract/
├── DTO/
├── Engine/
├── Policy/
├── Signal/
├── Device/
├── Correlation/
├── Penalty/
├── Budget/
├── CircuitBreaker/
├── FailureSemantics/
├── Middleware/
├── Resolver/
└── Adapter/
    ├── Redis/
    ├── MongoDB/
    └── MySQL/
```

This is a directional architecture map, **not a locked namespace/file plan**.

The implementation plan must be derived from the actual repository and dependency contracts before namespaces are finalized.

---

## 13. Application Boundary

The standalone package must not absorb application-specific behavior.

The following belong to the consuming application unless generalized through a real reusable contract:

- Login-specific route wiring.
- OTP-specific route wiring.
- Authentication-domain configuration.
- Application permission logic.
- Product-specific telemetry.
- Application event names.
- Application container definitions.
- Framework-specific bootstrapping beyond optional adapters.

The package should provide the primitives required to express these policies without knowing the application that uses them.

---

## 14. Relationship with Abuse Protection

Rate limiting and abuse protection may collaborate, but they are not the same capability.

The Rate Limiter owns deterministic throttling-related concerns, including correlation or penalty logic that is necessary to produce a rate-limit decision.

A broader abuse-protection capability may own concerns such as:

- Challenge selection.
- Risk scoring beyond rate-limit state.
- Fraud intelligence.
- Behavioral anomaly models.
- Cross-domain enforcement workflows.

The vNext package must not grow into a general-purpose abuse platform.

---

## 15. Migration Philosophy

The eventual migration should be based on **capabilities and contracts**, not bulk file copying.

Every part of the Production-Validated Reference Module should be classified as one of:

- Generic capability that belongs in `maatify/rate-limiter`.
- Generic capability that should instead be supplied by another Maatify foundation library.
- Application integration that must remain outside the package.
- Implementation detail that should be redesigned rather than migrated.
- Behavior already provided correctly by the current standalone package and worth retaining.

The existing standalone implementation should receive the same classification.

This allows vNext to become a deliberate synthesis rather than a code transplant.

---

## 16. What This Draft Does Not Decide

This document intentionally does **not** yet lock:

- Exact public API signatures.
- Exact namespace layout.
- Exact Composer dependency list.
- Whether every adapter remains mandatory or becomes optional by Composer suggestion / extra package.
- Exact persistence contracts.
- Backward-compatibility strategy.
- Major version number.
- Migration sequence.
- Deprecation schedule.
- Final policy configuration format.

Those decisions require a repository-level gap analysis first.

---

## 17. Required Standards and Execution Governance

Before this Draft may merge into `main`, the repository must prepare, resolve, pin, validate, and reconcile a Selective Pinned Adoption set from `Maatify/php-engineering-standards`, and the final repository state must comply with the Final Resolved Applicable Standards Set for every activated scope.

Canonical adoption becomes effective when the prepared adoption state is merged into the repository's default branch; therefore the Draft branch prepares and validates the adoption state, while the final owner-approved Draft merge makes it effective on `main`.

Under the current standards baseline, this vNext effort requires at minimum:

- the `composer-package` Profile for the standalone package engineering scope, and
- the `repository-governance` Profile for the repository governance scope.

The canonical adoption mechanism is defined by `std-standards-adoption`. The repository governance Profile brings the canonical `std-github-phase-stack-workflow` into the adoption graph rather than treating the workflow as an informal external rule. All standards and Profiles must come from the same exact pinned upstream commit unless a canonical, explicitly documented exception applies.

The project-specific mandatory rules are defined in [`RATE_LIMITER_VNEXT_EXECUTION_GOVERNANCE.md`](RATE_LIMITER_VNEXT_EXECUTION_GOVERNANCE.md).

For this vNext effort:

- Pinned adoption preparation, applicability resolution, repository reconciliation, and standards compliance are Draft closure gates.
- Compliance applies to every activated scope, not only to an informal subset of files described as vNext changes.
- All vNext repository work must be performed under the `draft/rate-limiter-vnext-architecture` integration boundary.
- vNext Work Branches / Execution Batches must integrate back into the Draft rather than bypass it and target `main` directly.
- Only the completed, standards-compliant Draft integration state may be proposed for final merge into `main`.
- Final `draft/rate-limiter-vnext-architecture -> main` integration is owner-only and must use GitHub Squash Merge after all closure gates pass.

---

## 18. Required Next Step

Before implementation begins, produce a formal **Rate Limiter vNext Gap Analysis and Migration Blueprint** covering both:

1. the current `maatify/rate-limiter` package, and
2. the Production-Validated Reference Module.

The analysis should identify, at contract/file/capability level:

- What should be retained from the standalone package.
- What should be adopted from the Production-Validated Reference Module.
- What should be redesigned.
- What should remain application-specific.
- Which Maatify ecosystem libraries should be reused and why.
- Which storage semantics are required.
- Which adapters satisfy which capabilities today.
- Which compatibility breaks are unavoidable.
- How the work should be split into dependency-aware stacked implementation phases / execution batches under the Draft boundary.

No implementation should be started from this draft alone.

---

## 19. Target Outcome

The desired end state is a standalone Rate Limiter that combines:

> **production-validated security architecture**
>
> +
>
> **clean Maatify ecosystem reuse**
>
> +
>
> **PSR integration and package ergonomics**
>
> +
>
> **truthful multi-backend storage support**
>
> +
>
> **strong CI and conformance guarantees**.

The package should be suitable both for straightforward throttling use cases and for security-sensitive applications that require multi-signal, account-aware, device-aware, failure-aware rate limiting without coupling the core library to any single application or framework.
