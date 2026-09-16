# Rate Limiter vNext Execution Governance

## Status

**Mandatory governance for the Rate Limiter vNext Draft.**

This document is part of the vNext Draft integration boundary. It governs how the architecture direction in `RATE_LIMITER_VNEXT_ARCHITECTURE_DRAFT.md` is prepared, implemented, verified, and eventually merged into `main`.

This is not optional process guidance. The requirements below are merge gates for the vNext Draft.

---

## 1. Standards Adoption Must Be Prepared, Resolved, Pinned, and Validated Before Draft Closure

Before the vNext Draft may be merged into `main`, `maatify/rate-limiter` must contain a complete, reviewable, and valid Maatify standards adoption set sourced from the canonical standards repository:

```text
Maatify/php-engineering-standards
```

The adoption mechanism is defined by:

```text
Standard ID:      std-standards-adoption
Current baseline: v2.0.0
Canonical file:   standards/STANDARDS_ADOPTION_STANDARD_AR.md
```

The repository must use **Selective Pinned Adoption**. It must not depend on a floating standards branch and must not copy the entire standards repository "just in case".

Before Draft closure, the Draft must already contain and validate:

- the required Pinned Adoption Control Set,
- the active Profile manifests and required inherited Profile manifests,
- the final Resolved Applicable Standards Set for every activated scope,
- the local `STANDARDS_MANIFEST.md`,
- the exact upstream standards commit used for the adoption set,
- and repository reconciliation against that resolved set.

The canonical adoption standard defines adoption as becoming effective when the adoption state is merged into the repository's default branch. Therefore, this Draft must **prepare, resolve, pin, validate, and reconcile** the adoption state before closure; the final owner-approved merge of the Draft into `main` is what makes that adoption effective in the default branch.

This governance document must not claim that standards are already formally adopted merely because the pinned adoption files exist on the Draft branch.

---

## 2. Required Profile Activations for This vNext Effort

Profile activation must remain scope-aware and must follow the canonical adoption mechanism. For the current standards baseline and the actual nature of this repository, the vNext adoption must include, at minimum:

### 2.1 Composer package engineering scope

`maatify/rate-limiter` is a standalone reusable Composer package. The canonical package profile must therefore be activated for the package scope:

```text
Profile ID: composer-package
Canonical file: standards/profiles/COMPOSER_PACKAGE_PROFILE.md
```

This profile resolves the package engineering standards that govern package structure, Composer behavior, CI, library presentation, testing, and applicable conditional persistence/database requirements.

### 2.2 Repository governance scope

This vNext effort explicitly uses the Maatify repository governance and phase execution model. The canonical repository governance profile must therefore be activated for the repository governance scope:

```text
Profile ID: repository-governance
Canonical file: standards/profiles/REPOSITORY_GOVERNANCE_PROFILE.md
```

This activation is required so the GitHub Phase Stack Workflow and AI Collaboration Workflow enter the adoption set through the canonical Profile composition rather than being referenced informally outside the adoption graph.

The exact local scope expressions must be recorded in `STANDARDS_MANIFEST.md` and must match repository reality. Additional Profiles or Standards may be activated only when justified by actual applicability and resolved through the canonical adoption mechanism.

If the pinned upstream standards commit changes Profile IDs, composition, or applicability from the baseline documented here, the pinned canonical definitions take precedence and this governance file must be reconciled before Draft closure.

---

## 3. Compliance Is a Pre-Merge Gate for Every Activated Scope

Preparing the adoption files is not enough.

Before:

```text
draft/rate-limiter-vnext-architecture -> main
```

is allowed, the resulting repository state must comply with the **Final Resolved Applicable Standards Set for every activated scope**.

Compliance must not be narrowed informally to only files described as "vNext files" when an activated Profile applies to a broader scope such as `/`.

If a Profile is activated for the repository root, every repository concern governed by its resolved applicable standards is part of the Draft closure gate unless the canonical standard itself defines narrower applicability or a valid documented exception exists.

The Draft must not be merged while known unresolved violations remain inside any activated scope.

Where the standards require evidence, CI, static analysis, tests, repository metadata, architectural boundaries, package rules, documentation rules, or other gates, those requirements become part of Draft closure.

A standards file being present in the repository is not proof of compliance. Compliance must be demonstrated against the actual final repository state.

---

## 4. GitHub Phase Stack Workflow Is Required Through Canonical Adoption

The vNext work must follow the canonical Maatify GitHub phase/execution workflow defined by:

```text
Standard ID:      std-github-phase-stack-workflow
Current baseline: v2.2.0
Canonical file:   standards/GITHUB_PHASE_STACK_WORKFLOW_AR.md
```

For the current baseline, this workflow is brought into the repository through the required `repository-governance` Profile activation defined above.

The exact adopted version is determined by the pinned standards adoption commit. The version above documents the baseline used when this governance file was created; it is not a replacement for pinned adoption.

The workflow must be applied according to its canonical rules, including its dependency-aware model and the rule:

```text
Phase != Branch != PR
```

This Draft does **not** impose artificial one-branch-per-numbered-phase ceremony where the standard permits a safer and clearer Execution Batch.

---

## 5. The vNext Draft Is the Integration Boundary

For the vNext effort, the branch:

```text
draft/rate-limiter-vnext-architecture
```

is the designated integration boundary before `main`.

The intended topology is:

```text
main
└── draft/rate-limiter-vnext-architecture
    ├── standards adoption preparation / repository reconciliation
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
5. Verification or documentation work that produces repository changes remains inside the same Draft train.
6. Only the completed Draft integration boundary may be proposed for final merge into `main`.

If the adopted phase-stack standard allows a Work Branch to serve directly as an Execution Batch boundary, that optimization is allowed **inside** this Draft train; it does not permit bypassing the Draft-to-`main` boundary established here for this vNext effort.

---

## 6. No Implementation Before the Blueprint Gate

The architecture draft alone is not sufficient authorization to implement vNext behavior.

The required sequence is:

```text
Architecture Direction
        ↓
Pinned Adoption Preparation / Applicability Resolution
        ↓
Repository Standards Reconciliation
        ↓
Gap Analysis + Migration Blueprint
        ↓
Approved Dependency-Aware Execution Boundaries
        ↓
Implementation Work Under the Draft
        ↓
Verification / Conformance / CI Gates
        ↓
Draft Closure Review
        ↓
Owner-Only GitHub Squash Merge: Draft -> main
```

The Gap Analysis and Migration Blueprint must determine the actual contract/file/capability migration and the dependency-aware execution structure before implementation begins.

---

## 7. Standards Apply During the Draft, Not Only After Merge

Although canonical adoption becomes effective in the default branch only when the adoption state is merged into `main`, the pinned standards set prepared on this Draft is the required engineering input for all subsequent vNext work once it has been resolved and approved inside the Draft train.

Agents, contributors, and reviewers working on vNext must inspect the locally pinned standards applicable to their task scope before changing code or documentation.

A later final compliance sweep does not justify knowingly implementing work that conflicts with the already-resolved Draft adoption set.

If the pinned standards commit is upgraded during the Draft, the upgrade must itself be reviewed, Profiles and applicability must be re-resolved, `STANDARDS_MANIFEST.md` must be updated, and affected vNext work must be reconciled before Draft closure.

---

## 8. Draft Closure Conditions

The vNext Draft is not ready to merge into `main` until all of the following are true:

- The canonical Maatify standards adoption mechanism has been correctly prepared on the Draft.
- The adoption set is pinned to an exact canonical upstream commit.
- Required Profile activations include the standalone Composer package scope and repository governance scope as defined by the pinned canonical standards.
- Active and inherited Profiles are structurally resolved.
- Applicable Standards are resolved through canonical applicability for every activated scope.
- `STANDARDS_MANIFEST.md` accurately records the final resolved Draft adoption state.
- The complete repository state complies with the Final Resolved Applicable Standards Set for every activated scope.
- The Rate Limiter vNext Gap Analysis and Migration Blueprint has been completed and reconciled with the actual implementation.
- All approved vNext execution work has integrated into the Draft boundary.
- No vNext Work Branch is intended to bypass the Draft and merge independently into `main`.
- Required tests, static analysis, CI, adapter conformance, and architectural verification gates are green or otherwise satisfied exactly as required by the pinned applicable standards and approved blueprint.
- Documentation claims match the final repository reality.
- The final Draft state has received closure review as one integrated change set against current `main`.
- No known unresolved standards violation remains within any activated scope.

Only after these conditions are satisfied may the Draft be considered for final owner merge into `main`.

---

## 9. Final Merge Rule

The final integration of this vNext effort is:

```text
draft/rate-limiter-vnext-architecture -> main
```

and is explicitly:

- **owner-only**,
- performed using **GitHub Squash Merge**,
- allowed only after all Draft closure gates are satisfied,
- and performed against the current `main` state after final reconciliation and review.

No agent, automation, Work Branch, execution batch, verification branch, documentation branch, or fix branch may independently perform or bypass this final merge boundary.

The final Squash Merge is also the point at which the prepared pinned standards adoption becomes effective in the repository's default branch under the canonical adoption standard.

---

## 10. Authority and Precedence

This file does not redefine the canonical Maatify standards.

For standards adoption mechanics, the canonical authority remains:

```text
Maatify/php-engineering-standards
standards/STANDARDS_ADOPTION_STANDARD_AR.md
```

For Profile composition, the canonical authority remains the Profile manifests from the same pinned upstream standards commit, including the package and repository governance Profiles required for this effort.

For phase/execution GitHub workflow rules, the canonical authority remains:

```text
Maatify/php-engineering-standards
standards/GITHUB_PHASE_STACK_WORKFLOW_AR.md
```

If this governance document conflicts with a pinned canonical Standard or Profile, the canonical pinned source wins and this document must be corrected before Draft closure.

This file establishes the following project-specific decisions for Rate Limiter vNext:

1. **the canonical standards adoption set must be prepared, resolved, pinned, validated, and reconciled before Draft closure;**
2. **`composer-package` and `repository-governance` are required Profile activations for their applicable scopes under the current baseline;**
3. **compliance is evaluated against every activated scope, not an informal subset called the vNext scope;**
4. **the vNext Draft is the integration boundary through which all vNext repository work must pass before `main`;** and
5. **the final Draft -> `main` integration is owner-only GitHub Squash Merge after all closure gates pass.**
