# STANDARDS_MANIFEST.md

**Manifest الاعتماد والتوزيع لمعايير هندسة Maatify — Maatify/rate-limiter**

هذا الملف هو inventory وresolver record وفق [STANDARDS_ADOPTION_STANDARD_AR.md](standards/STANDARDS_ADOPTION_STANDARD_AR.md) §9. يسجّل حقائق الاعتماد فقط، ولا يحمل قواعد هندسية؛ تُملك القواعد للملفات المرتبطة أدناه.

## 1. Upstream Repository
- **Upstream Repository:** `Maatify/php-engineering-standards`
- **Adoption Commit (Pinned):** `d1946d800ba508d30b8906daae3db54afca7924a`
- **Adoption Date:** `2026-09-16`
- **Pinned Source of Truth:** المستودع المركزي فقط، مثبّت عند الـCommit أعلاه. لا يُستخدم `main` العائم ولا snapshot كامل.

## 2. Pinned Adoption Control Set
الـControl Set المثبّتة محليًا (Adoption Standard + Profile Manifest المُفعَّلة/Mوروثة). لا تُدرج Candidate غير منطبقة.

| Role | Local File | ID | Version |
|---|---|---|---|
| Adoption Standard | [STANDARDS_ADOPTION_STANDARD_AR.md](standards/STANDARDS_ADOPTION_STANDARD_AR.md) | `std-standards-adoption` | `2.0.0` |
| Active Profile | [profiles/COMPOSER_PACKAGE_PROFILE.md](standards/profiles/COMPOSER_PACKAGE_PROFILE.md) | `composer-package` (Profile) | `1.0.0` |
| Active Profile | [profiles/REPOSITORY_GOVERNANCE_PROFILE.md](standards/profiles/REPOSITORY_GOVERNANCE_PROFILE.md) | `repository-governance` (Profile) | `1.0.0` |

لا توجد Profiles موروثة (`Extends: None` لكلا الـProfile المُفعَّلَين)، فلم تُنسخ أي Profile إضافية.

## 3. Active Profiles وActivation Scope
| Profile Activation | Scope | Profile Version | Inherited Profiles |
|---|---|---|---|
| `composer-package` | `/` | `1.0.0` | `None` |
| `repository-governance` | `/` | `1.0.0` | `None` |

ملفا الـManifest أعلاه هما الإدخال المعتمد والمراجعة لكل Activation. لا يُعاد بناء Resolution من upstream في المهام العادية (§10.1).

## 4. Resolved Applicable Standards Set (final only)
مجموعة الـStandards المنطبقة النهائية بعد Resolution من الـControl Set أعلاه؛ يُعرض الناتج النهائي فقط دون Candidates غير المنطبقة.

| Standard | Local File | Standard ID | Standard Version | Source (Profile) |
|---|---|---|---|---|
| تحميل الحزم والبناء | [packages/PACKAGE_BUILDING_STANDARD.md](standards/packages/PACKAGE_BUILDING_STANDARD.md) | `std-package-building` | `1.4.0` | composer-package |
| Composer Package | [packages/COMPOSER_PACKAGE_STANDARD.md](standards/packages/COMPOSER_PACKAGE_STANDARD.md) | `std-composer-package` | `1.2.0` | composer-package |
| CI Workflow | [packages/CI_WORKFLOW_STANDARD.md](standards/packages/CI_WORKFLOW_STANDARD.md) | `std-ci-workflow` | `1.1.0` | composer-package |
| Library Presentation | [packages/LIBRARY_PRESENTATION_STANDARD.md](standards/packages/LIBRARY_PRESENTATION_STANDARD.md) | `std-library-presentation` | `1.0.1` | composer-package |
| Testing | [testing/TESTING_STANDARD.md](standards/testing/TESTING_STANDARD.md) | `std-testing` | `1.1.0` | composer-package |
| GitHub Phase Stack Workflow | [GITHUB_PHASE_STACK_WORKFLOW_AR.md](standards/GITHUB_PHASE_STACK_WORKFLOW_AR.md) | `std-github-phase-stack-workflow` | `2.2.0` | repository-governance |
| AI Collaboration Workflow | [ai/AI_COLLABORATION_WORKFLOW_AR.md](standards/ai/AI_COLLABORATION_WORKFLOW_AR.md) | `std-ai-collaboration-workflow` | `6.0.0` | repository-governance |

لم يُنسخ من الـControl Set: `governance/` (مستبعدة بنيويًا من الاعتماد؛ تبقى في المركزية)، `modules/` (غير منطبقة على مكتبة Composer مستقلة)، والـProfiles غير المُفعَّلة (`slim-module`، `project-aware-slim-module`، `base-module`) والـStandards غير المنطبقة منها، وaudits/decisions التاريخية.

## 5. Explicit Additional Standards
لا توجد Standards إضافية صريحة.

## 6. Explicit Exceptions / Overrides
لا توجد استثناءات أو تجاوزات معتمدة.

## 7. Adoption Proof
- كل الملفات أعلاه مثبتة محليًا عند `d1946d800ba508d30b8906daae3db54afca7924a` ومطابقة بايتًا-ببايت لنسخة الـCommit المذكور من المستودع المركزي `Maatify/php-engineering-standards`.
- يطلب الـManifest من القرّاء الاعتماد على الروابط أعلاه وملفات الـProfile المثبتة للقواعد الكاملة؛ لا يسرد هنا التفاصيل الهندسية.
