# Repository Governance Profile

## Profile Metadata

- **Profile ID:** `repository-governance`
- **Profile Version:** `1.0.0`
- **Purpose / Applicability:** إدارة التعاون، دورة Phase، والحوكمة التشغيلية في Repository تتبع Maatify engineering workflow.
- **Extends:** `None`

## Required Standards

هذه هي الـ Standards المباشرة لهذا Profile:

- [AI Collaboration Workflow](../ai/AI_COLLABORATION_WORKFLOW_AR.md)
- [GitHub Phase Stack Workflow](../GITHUB_PHASE_STACK_WORKFLOW_AR.md)

لا يضم هذا Profile Package أو Module standards.

## Conditional Applicability

تطبق الـ Standards المطلوبة على Scope الذي يفعّل فيه المشروع Profile. تفاصيل الأدوار والـ Phase lifecycle تظل مملوكة للملفين المشار إليهما.

## Resolved Dependency Behavior

لا يرث هذا Profile Profile آخر. يكون الـ Resolved Set له هو Required Standards المذكورة أعلاه، مع أي Additional Standards مصرح بها صراحةً في Manifest المشروع.

عند تفعيل هذا Profile، يجب تثبيت ملفه محليًا ضمن `Pinned Adoption Control Set`؛ وتثبت كذلك ملفات Profiles الموروثة اللازمة للحل، بينما لا تُنسخ Profiles غير المفعلة.

## Scope Notes

يجب أن يسجل المشروع Scope صريحًا لكل Activation، مثل `/` أو مسارات الحوكمة التي يشملها العقد. يمكن تفعيله إلى جانب Profiles أخرى في Repository نفسها.

## Precedence Notes

هذا الملف composition manifest فقط. تطبق قواعد adoption من [STANDARDS_ADOPTION_STANDARD_AR.md](../STANDARDS_ADOPTION_STANDARD_AR.md)، وتظل قواعد التعاون ودورة Phase مملوكة للـ Standards المطلوبة.
