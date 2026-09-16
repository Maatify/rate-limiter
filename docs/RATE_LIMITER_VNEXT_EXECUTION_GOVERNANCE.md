# Rate Limiter vNext Execution Governance

## Status

**Mandatory governance for the Rate Limiter vNext Draft.**

This document is part of the vNext Draft integration boundary. It governs how the architecture direction in `RATE_LIMITER_VNEXT_ARCHITECTURE_DRAFT.md` is adopted, implemented, verified, and eventually merged into `main`.

This is not optional process guidance. The requirements below are merge gates for the vNext Draft.

---

## 1. Standards Adoption Is Required Before Draft Closure

Before the vNext Draft may be merged into `main`, `maatify/rate-limiter` must formally adopt the applicable Maatify engineering standards from the canonical standards repository:

```text
Maatify/php-engineering-standards
```

Adoption must follow the canonical adoption mechanism defined by:

```text
Standard ID:      std-standards-adoption
Current baseline: v2.0.0
Canonical file:   standards/STANDARDS_ADOPTION_STANDARD_AR.md
```

The repository must use **Selective Pinned Adoption**. It must not depend on a floating standards branch and must not copy the entire standards repository "just in case".

The adopted control set, active Profile manifests, applicable engineering standards, and local `STANDARDS_MANIFEST.md` must be resolved and pinned to an exact upstream standards commit according to the canonical adoption standard.

The applicable Profile(s) and standards must be selected from repository reality and artifact scope. They must not be guessed or hard-coded by this draft before the adoption resolution is performed.

---

## 2. Compliance Is a Pre-Merge Gate

Formal adoption alone is not enough.

Before:

```text
draft/rate-limiter-vnext-architecture -> main
```

is allowed, the resulting repository state must comply with the final resolved applicable standards for the affected scopes.

The Draft must not be merged while known unresolved standards violations remain in the vNext scope.

Where the standards require evidence, CI, static analysis, tests, repository metadata, architectural boundaries, package rules, or other gates, those requirements become part of Draft closure.

A standards file being present in the repository is not proof of compliance. Compliance must be demonstrated against the actual final repository state.

---

## 3. GitHub Phase Stack Workflow Is Required

The vNext work must follow the canonical Maatify GitHub phase/execution workflow defined by:

```text
Standard ID:      std-github-phase-stack-workflow
Current baseline: v2.2.0
Canonical file:   standards/GITHUB_PHASE_STACK_WORKFLOW_AR.md
```

The exact adopted version is determined by the pinned standards adoption commit. The versions above document the baseline used when this governance file was created; they are not a replacement for pinned adoption.

The workflow must be applied according to its canonical rules, including its dependency-aware model. This Draft does **not** impose artificial one-branch-per-numbered-phase ceremony where the standard permits a safer and clearer Execution Batch.

---

## 4. The vNext Draft Is the Integration Boundary

For the vNext effort, the branch:

```text
draft/rate-limiter-vnext-architecture
```

is the designated integration boundary before `main`.

The intended topology is:

```text
main
└── draft/rate-limiter-vnext-architecture
    ├── standards adoption / repository reconciliation
    ├── gap analysis and migration blueprint
    ├── execution batches / work branches
    ├── required fixes
    └── final integration and verification gates
```

All repository changes that belong to the vNext effort must be developed **under this Draft boundary**.

This means:

1. New vNext Work Branches / Execution Batches must be based on the latest approved state of the Draft, or on an approved descendant branch inside the same dependency train when required by the adopted workflow.
2. vNext Work Branches must integrate back into the Draft boundary, not directly into `main`.
3. A vNext implementation PR must not bypass the Draft and target `main` directly.
4. Required fixes discovered during vNext verification remain part of the same Draft integration train.
5. Only the completed Draft integration boundary may be proposed for final merge into `main`.

If the adopted phase-stack standard allows a Work Branch to serve directly as an Execution Batch boundary, that optimization is allowed **inside** this Draft train; it does not permit bypassing the Draft-to-`main` boundary established here for this vNext effort.

---

## 5. No Implementation Before the Blueprint Gate

The architecture draft alone is not sufficient authorization to implement vNext behavior.

The sequence is:

```text
Architecture Direction
        ↓
Standards Adoption / Applicability Resolution
        ↓
Repository Standards Reconciliation
        ↓
Gap Analysis + Migration Blueprint
        ↓
Approved Execution Boundaries
        ↓
Implementation Work Under the Draft
        ↓
Verification / Conformance / CI Gates
        ↓
Draft Closure Review
        ↓
Draft -> main
```

The Gap Analysis and Migration Blueprint must determine the actual contract/file/capability migration and the dependency-aware execution structure before implementation begins.

---

## 6. Standards Apply to the Work, Not Only the Final Merge

Once the applicable standards have been resolved and pinned, every subsequent vNext task must use them as an active engineering input.

Agents, contributors, and reviewers working on vNext must inspect the locally adopted standards applicable to their task scope before changing code or documentation.

A later final compliance sweep does not justify knowingly implementing work that conflicts with already-adopted standards.

If standards are upgraded during the Draft, the upgrade must itself be reviewed and the affected vNext work must be reconciled against the newly pinned applicable set before Draft closure.

---

## 7. Draft Closure Conditions

The vNext Draft is not ready to merge into `main` until all of the following are true:

- The Maatify standards adoption mechanism is present and valid.
- Adoption is pinned to an exact canonical upstream commit.
- Active Profiles and applicable standards are resolved from repository reality.
- `STANDARDS_MANIFEST.md` accurately records the resolved adoption state.
- The repository changes within the vNext scope comply with the resolved applicable standards.
- The Rate Limiter vNext Gap Analysis and Migration Blueprint has been completed and reconciled with the actual implementation.
- All approved vNext execution work has integrated into the Draft boundary.
- No vNext Work Branch is intended to bypass the Draft and merge independently into `main`.
- Required tests, static analysis, CI, adapter conformance, and architectural verification gates are green or otherwise satisfied exactly as required by the adopted standards and approved blueprint.
- Documentation claims match the final repository reality.
- The final Draft state has received closure review as one integrated change set against current `main`.

Only after these conditions are satisfied may the Draft be considered for merge into `main`.

---

## 8. Authority and Precedence

This file does not redefine the canonical Maatify standards.

For standards adoption mechanics, the canonical authority remains:

```text
Maatify/php-engineering-standards
standards/STANDARDS_ADOPTION_STANDARD_AR.md
```

For phase/execution GitHub workflow rules, the canonical authority remains:

```text
Maatify/php-engineering-standards
standards/GITHUB_PHASE_STACK_WORKFLOW_AR.md
```

This file only establishes two project-specific decisions for Rate Limiter vNext:

1. **standards adoption and compliance are mandatory Draft closure gates**, and
2. **the vNext Draft is the integration boundary through which all vNext repository work must pass before `main`.**
