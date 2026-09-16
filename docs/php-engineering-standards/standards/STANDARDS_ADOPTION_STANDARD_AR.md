# معيار اعتماد وتوزيع معايير Maatify

## بيانات المعيار

- **Standard ID:** `std-standards-adoption`
- **Standard Version:** `2.0.0`
- **Standard Version Format:** `MAJOR.MINOR.PATCH`
- **اللغة المعتمدة:** العربية.
- **حالة الاعتماد:** يصبح معتمدًا عند دمجه في الفرع الافتراضي للمشروع.
- **النطاق:** آلية اختيار المعايير وتثبيتها وتوزيعها وتفعيلها داخل المشاريع التابعة لمنظومة Maatify.
- **الهدف:** استبدال نسخ المستودع الكامل باعتماد انتقائي مثبت وقابل للتتبع، مع الحفاظ على المصدر المركزي وسلامة الروابط والترقية القابلة للمراجعة.

هذا الملف هو **المصدر الوحيد للحقيقة لآلية Adoption**. لا يملك قواعد هندسية تخص PHP أو Composer أو Modules أو CI أو Testing؛ تلك القواعد تظل مملوكة للـ Standards المشار إليها في ملفات Profiles.

## 1. المصدر المركزي وحدود الملكية

المستودع:

```text
Maatify/php-engineering-standards
```

هو المصدر المركزي الوحيد لتأليف المعايير وProfiles. المشروع التابع يحتفظ بنسخ محلية لأغراض الاعتماد والتثبيت، لكنه لا يجعل النسخة المحلية أو ملف الـ Manifest مصدرًا منافسًا للقواعد الأصلية.

يجب أن يملك كل موضوع معياري ملفًا واحدًا. Profile هو **composition manifest** يحدد ما يجتمع وما ينطبق، ولا يعيد كتابة القواعد المملوكة للـ Standard.

## 2. منع Full Repository Snapshot

يجب ألا يعتمد أي مشروع على نسخ مجلد:

```text
standards/
```

بالكامل كإجراء افتراضي أو احتياطي. لا يجوز استخدام نمط `all standards just in case`، ولا يجعل وجود Standard في المستودع المركزي تلك الـ Standard منطبقة على كل مشروع.

يجب أن يحتوي المشروع فقط على **Pinned Adoption Files**: الـ Pinned Adoption Control Set الإلزامية، والـ Pinned Applicable Standards Set الناتجة عن Profiles المفعلة وdependencies الخاصة بها، إضافة إلى Additional Standards المصرح بها صراحةً. لا تدخل ملفات أخرى لمجرد وجودها في المستودع المركزي.

## 3. طبقات الاعتماد: Control Plane وApplicable Engineering Standards

يستخدم كل consuming repository يعتمد هذا النظام طبقتين مختلفتين من الملفات المنسوخة من upstream، وسجلًا محليًا للنتيجة. لا تجعل هذه الطبقات Adoption Standard جزءًا من Required Standards الهندسية لأي Profile.

### A. Pinned Adoption Control Set

هذا الـ Control Set **إلزامي** لكل consuming repository يستخدم نظام Selective Adoption. يجب أن يحتوي، من upstream pinned commit، على:

```text
standards/STANDARDS_ADOPTION_STANDARD_AR.md
active Profile manifests
all inherited Profile manifests required to resolve those active Profiles
```

يجب ألا يحتوي Control Set على Profile غير مستخدمة أو غير موروثة من Profile مفعلة.

إذا كان الـ Active Profile هو:

```text
project-aware-slim-module
```

فإن Profile manifests المحلية المطلوبة هي:

```text
PROJECT_AWARE_SLIM_MODULE_PROFILE.md
SLIM_MODULE_PROFILE.md
BASE_MODULE_PROFILE.md
COMPOSER_PACKAGE_PROFILE.md
```

ولا يلزم نسخ:

```text
REPOSITORY_GOVERNANCE_PROFILE.md
```

إلا إذا كان مفعّلًا أيضًا أو دخل في inheritance مطلوبة.

وجود Adoption Standard وProfile manifests في Control Set لا يجعل Adoption Standard capability هندسية، ولا يضيفها إلى `Required Standards` لأي Profile.

### B. Pinned Applicable Standards Set

هو المجموعة النهائية من الـ canonical Engineering Standards التي تنطبق على Activation/Scope وفق applicability المملوكة لكل Standard. لا تساوي هذه المجموعة مجرد union للـ Required Standards المشار إليها في Profiles. ينتجها الحل على مرحلتين إلزاميتين موضحتين في §7:

```text
Structural / Transitive Resolution
→ Candidate Standard References
→ Canonical Standard Applicability
→ Final Resolved Applicable Standards Set
```

تشمل مجموعة المرشحين المراجع المباشرة والموروثة من Profiles وأي `Explicit Additional Standards` صالحة بنيويًا. ولا تصبح أي Standard جزءًا من هذه المجموعة النهائية إلا بعد تقييم Activation Scope وحقائق الـartifact مقابل canonical Applicability وConditional Applicability المملوكة لتلك Standard. هذه المجموعة النهائية وحدها تحدد القواعد الهندسية المطلوب قراءتها وتطبيقها على Scope المهمة.

### C. Local Resolver Record

```text
STANDARDS_MANIFEST.md
```

هو ملف محلي يولده المشروع ويسجل نتيجة adoption والعلاقة بين Control Set وApplicable Standards Set. ليس ملفًا منسوخًا من upstream، وليس Standard أو Profile، ولا يدخل في Required Standards أو في Pinned Adoption Control Set.

يسجل Manifest المجموعة النهائية فقط بوصفها `Resolved Applicable Standards Set`؛ ولا يسجل Candidate Standard غير منطبقة على أنها Applicable Standard. تظل Profile references اللازمة للحل البنيوي ظاهرة في ملفات Profiles المثبتة داخل Control Set، وتظل مدخلات ونتائج resolution قابلة للمراجعة وفق الحقول القائمة.

وعليه:

```text
Pinned Adoption Files
=
Pinned Adoption Control Set
+
Pinned Applicable Standards Set

Local metadata
=
STANDARDS_MANIFEST.md
```

## 4. Selective Pinned Adoption

عند إنشاء أو تحديث Adoption Set:

1. تكون Adoption Standard وProfile manifests والـ Applicable Engineering Standards نسخًا محلية ومثبتة على Adoption Commit محدد.
2. تأتي جميع الملفات المنسوخة من upstream افتراضيًا من **نفس exact upstream commit** حفاظًا على consistency بين Control Plane والروابط والقواعد.
3. يمنع الاعتماد على floating `main` أو أي مرجع متحرك بدل commit محدد.
4. يسجل المشروع في Manifest المستودع upstream والـ commit والـ Profiles والـ Scopes والـ Control Set والـ Applicable Standards وإصداراتها المرتبطة بها.
5. تتم الترقية عبر تغيير reviewed يعيد حل Profiles المستخدمة فقط.
6. لا تنسخ الترقية Standards غير المنطبقة على Scopes المشروع.

لا يجوز جمع Standards من commits مختلفة إلا بقرار صريح موثق من مالك المشروع، ويجب تسجيل هذا الاستثناء في Manifest مع سببه ونطاقه.

## 5. Profile Composition Manifests

كل Profile مستقل ويبدأ افتراضيًا بالإصدار `1.0.0`. يجب أن يوضح ملف Profile، على الأقل:

- `Profile ID` فريدًا.
- `Profile Version` منفصلًا عن إصدارات Standards.
- `Purpose / Applicability`.
- `Extends`، أو `None` إذا لم يرث Profile آخر.
- `Required Standards` المباشرة التي يضيفها Profile.
- `Conditional Applicability`، مع الإحالة إلى Standard المالكة للشرط.
- `Resolved dependency behavior`.
- `Scope notes`.
- `Precedence notes`.

Profiles لا تنقل القواعد الهندسية إلى ملفاتها ولا تنشئ مصدرًا موازيًا للحقيقة. كل Profile يعلن الاعتمادات المباشرة فقط؛ أما الاعتمادات الموروثة فتدخل في الحل عبر Transitive Resolution.

### 5.1 Profile Versioning

يُفصل إصدار Profile عن إصدار كل Standard:

- **Patch:** تغيير صياغة أو metadata لا يغير composition.
- **Minor:** إضافة قدرة اختيارية أو شرطية متوافقة لا تغير الالتزامات القائمة.
- **Major:** تغيير Required Standards أو inheritance أو composition بما يغير عقد الاعتماد.

ملف Profile المفعّل، وكل ملف Profile موروث لازم لحل inheritance، جزء إلزامي من `Pinned Adoption Control Set` ويجب تثبيته محليًا. Profiles غير المفعلة وغير الموروثة لا تُنسخ. لا يعني ذلك إضافة Adoption Standard إلى `Required Standards`؛ فـ Required Standards تظل خاصة بالـ engineering applicability.

يملك هذا القسم Profile Versioning حصريًا. أما `Standard ID` و`Standard Version` و`Standard Version Format` وانتقالات إصدارات Standards فتخضع للسياسة المركزية [STANDARD_VERSIONING_POLICY_AR.md](governance/STANDARD_VERSIONING_POLICY_AR.md). لا تدخل هذه السياسة في `Pinned Adoption Control Set` أو `Resolved Applicable Standards Set`، ولا يحتاج Consumer إلى نسخها لمجرد Adoption.

## 6. Scope-Aware Profile Activation

كل Profile Activation في المشروع يجب أن تحدد Scope صريحًا. يمكن أن يكون Scope:

```text
/
Modules/*
Modules/*Slim
Modules/SpecificFeature
```

أو قائمة paths محددة. Scope هو نطاق التطبيق، وليس نوعًا حصريًا للمستودع.

يجوز للمشروع تفعيل عدة Profiles في Repository واحدة، كما يجوز أن تنطبق عدة Activations على الملف نفسه. لا يعني Scope الأكثر تحديدًا إلغاء Scope أوسع تلقائيًا؛ بل تُجمع Profiles المنطبقة، ما لم يوجد استثناء مشروع موثق بسلطة مناسبة.

عند تنفيذ مهمة، يجب على الوكيل:

1. تحديد الملفات والمسارات المتأثرة وحقائق الـartifact ذات الصلة، مثل كونه مكتبة مستقلة أو موديولًا قابلًا للاستخراج أو ميزة مرتبطة بـHost.
2. مطابقة المسارات مع Profile Activations وتحديد كل Activation/Scope على حدة.
3. تنفيذ Structural / Transitive Resolution لكل Activation: حل Profiles النشطة والموروثة، وجمع كل مراجع Required Standards المباشرة والموروثة وأي Explicit Additional Standards، والتحقق من سلامة graph والروابط والـmetadata قبل أي تصفية بسبب applicability.
4. بعد نجاح الحل البنيوي، تقييم كل Candidate Standard Reference مقابل Scope وحقائق الـartifact وcanonical Applicability أو Conditional Applicability التي تملكها تلك Standard.
5. أخذ Union للـStandards المنطبقة فقط لتكوين Final Resolved Applicable Standards Set عبر الـActivations المنطبقة.
6. قراءة وتطبيق Standards الموجودة في المجموعة النهائية فقط.
7. تطبيق التعليمات المحلية والاستثناءات الموثقة وفق precedence المشروع.

إذا عبرت المهمة أكثر من Scope، ينفذ الحل البنيوي وتقييم applicability لكل Activation/Scope بصورة مستقلة، ثم يستخدم Union للـ Final Applicable Standards الناتجة. لا تحذف خصوصية Scope أوسع أو أضيق Profile موروثة من مجموعة المرشحين؛ تظل applicability لكل Standard هي الحاكمة للنتيجة النهائية.

## 7. Transitive Profile Resolution

يجب أن يكون inheritance صريحًا وقابلًا للحل دون دورات. إذا كان:

```text
project-aware-slim-module
    extends slim-module
slim-module
    extends base-module
base-module
    extends composer-package
```

تتكون عملية الحل من مرحلتين منفصلتين:

### Stage 1 — Structural / Transitive Resolution

ينتج الحل البنيوي مجموعة `Candidate Standard References` من Required Standards المباشرة لكل Profile في سلسلة inheritance، بترتيب dependency، ومن Explicit Additional Standards المصرح بها. هذه مجموعة مرشحين مرحلية وليست Final Resolved Applicable Standards Set، ولا تلزم المشروع بإدراج Required Standards الموروثة مكررًا.

يجب التحقق من كل مرجع وكل metadata بنيوي مطلوب في هذه المرحلة، قبل تقييم applicability. يظل missing Standard أو Profile أو inherited Profile أو reference أو mandatory metadata، وكذلك inheritance cycle أو structural Manifest mismatch، `Structural Invalidity` حتى لو كانت applicability الخاصة بالـStandard ستستبعده لاحقًا من المجموعة النهائية. لا يجوز استخدام applicability لإخفاء graph أو reference مكسور.

### Stage 2 — Canonical Standard Applicability

بعد نجاح Stage 1، تقيم كل Candidate Standard مستقلةً مقابل Activation Scope وحقائق الـartifact الفعلية، وفق canonical Applicability وConditional Applicability المملوكة لتلك Standard. لا يملك Profile أو Manifest توسيع applicability الخاصة بالـStandard أو تضييقها. تدخل الـStandard في `Final Resolved Applicable Standards Set` فقط إذا انطبقت عليها قواعدها canonical.

لا يجوز إسقاط Candidate Standard موروثة من المرحلة البنيوية لمجرد أن Profile أكثر تحديدًا مفعّل أو مفضل. ويجوز استبعادها من المجموعة النهائية فقط إذا كانت canonical applicability التي تملكها هي تستبعد Scope/الـartifact الفعلي بصورة deterministic. وبهذا لا تتجاوز عبارات Profile مثل “inherited Standards enter the Resolved Set” مرحلة المرشحين أو applicability المملوكة لكل Standard.

يجب على الـ resolver المفاهيمي أو عملية المراجعة التحقق من:

- وجود كل Profile مذكور في `Extends`.
- عدم وجود inheritance cycle.
- وجود كل Required Standard المشار إليها فعليًا، حتى إذا لم تدخل لاحقًا المجموعة النهائية.
- وجود كل Explicit Additional Standard reference معلنة وصحتها بنيويًا.
- بقاء كل Standard موروثة ضمن مجموعة المرشحين مهما كانت خصوصية Profile مفعّلة.
- تكوين المجموعة النهائية بتطبيق canonical applicability المملوكة لكل Standard على Scope وحقائق الـartifact.

إذا استبعدت applicability المملوكة للـStandard مرشحًا بصورة deterministic، فهذا حكم applicability وليس Exception أو deviation: لا يحتاج إلى Exception، ولا يجعل النتيجة `INVALID` أو `OWNER DECISION REQUIRED` بذاته. أما إذا لم تسمح قواعد الـStandard وحقائق الـScope بحسم applicability، فتطبق Resolution Status القائمة في §16 دون افتراض الانطباق. ولا يغير Profile القاعدة التي تملكها Standard أخرى؛ وأي deviation أو override لا يسمح به عقد الـStandard لا يصبح تطبيقًا صحيحًا بمجرد طلبه أو تسجيله في Profile أو Manifest. تسجل الاستثناءات المعتمدة والموثقة فقط في Manifest بعد اكتمال Adoption؛ أما الطلبات والقرارات غير المحسومة فتبقى review evidence. وتخضع النتائج لقواعد §16.

### مثال توضيحي عام: Project-Aware Slim

عند تفعيل `project-aware-slim-module`، يظل Stage 1 مطالبًا بحل السلسلة كاملة `project-aware-slim-module → slim-module → base-module → composer-package` والتحقق من كل Profile وRequired Standard reference. بعد ذلك فقط يقيّم Stage 2 كل Standard على حدة. فإذا كان الـScope الفعلي ميزة Host-specific غير قابلة للاستخراج، فإن `COMPOSER_PACKAGE_STANDARD.md §3` لا ينطبق على هذا الـartifact لأنه يقصر نطاقه على مكتبات Composer مستقلة قابلة لإعادة الاستخدام؛ لذلك لا تدخل Composer Package Standard في المجموعة النهائية لذلك الـScope لمجرد inheritance. يظل Composer Profile ومرجعه جزءًا من الحل البنيوي، ولا يتغير inheritance أو أي Profile. هذا تطبيق للقاعدة العامة التي تملكها كل Standard، وليس استثناءً أو special case لاسم Profile.

## 8. Local Directory Layout وسلامة الروابط

عند نسخ Pinned Adoption Files إلى المشروع، يجب الحفاظ على البنية النسبية اللازمة لسلامة الروابط بين الملفات المختارة. المثال النهائي التالي يوضح أن Adoption Standard وProfile manifests المفعلة والموروثة جزء من النسخة المحلية، بينما تبقى المعايير الهندسية انتقائية:

```text
docs/php-engineering-standards/
├── STANDARDS_MANIFEST.md
└── standards/
    ├── STANDARDS_ADOPTION_STANDARD_AR.md
    ├── profiles/
    │   ├── <active profiles>
    │   └── <inherited profiles>
    ├── ai/
    │   └── <if applicable>
    ├── modules/
    │   └── <resolved applicable standards>
    ├── packages/
    │   └── <resolved applicable standards>
    └── testing/
        └── <if resolved>
```

تكون `<active profiles>` و`<inherited profiles>` أسماء الملفات المحلية الفعلية، ولا تعني placeholders لنسخ كل محتوى مجلد `profiles/`.

لا تُنسخ:

```text
unused profiles
unused engineering standards
docs/audits/
docs/decisions/
```

لا يشترط الاعتماد نسخ Folders فارغة، ولا تدخل الملفات التاريخية في Adoption Set. تظل `docs/audits/` و`docs/decisions/` في المستودع المركزي للتاريخ والحوكمة فقط.

## 9. عقد `STANDARDS_MANIFEST.md`

يجب أن يحتفظ كل مشروع تابع بملف:

```text
docs/php-engineering-standards/STANDARDS_MANIFEST.md
```

أو بالاسم نفسه تحت local standards root المعتمد للمشروع. الـ Manifest هو inventory وresolver record، وليس Standard جديدة. يجب أن يسجل على الأقل:

```text
Upstream Repository
Adoption Commit
Adoption Date أو metadata مناسبة وفق سياسة المشروع
Pinned Adoption Control Set
Active Profiles
Profile Version لكل Profile Activation
Scope لكل Profile Activation
Resolved Applicable Standards Set (final only; no non-applicable candidates)
Version لكل Standard
Explicit Additional Standards إن وجدت (مراجع الإدخال؛ لا تثبت وحدها applicability)
Explicit Exceptions/Overrides إن وجدت
```

يجب أن تجعل البيانات المسجلة قابلة لمراجعة العلاقة بين كل Profile Activation وScope والـ Control Set والـ Final Resolved Applicable Standards الناتجة عنها. لا تضع في Manifest القواعد الهندسية الكاملة؛ استخدم روابط إلى الملفات المملوكة لها. لا تسجل Candidate Standard غير منطبقة ضمن `Resolved Applicable Standards Set`؛ تظل سلامة مراجعها البنيوية قابلة للتحقق من ملفات Profiles المثبتة أو resolution evidence، وتظل Explicit Additional Standard مدخلًا معلنًا لا يتجاوز applicability canonical.

يسجل `Explicit Exceptions/Overrides` الاستثناءات المعتمدة والموثقة الداخلة في Adoption مكتملة فقط. لا يمثل هذا الـManifest حالة مقترحة أو غير محسومة؛ تطبق عليه حدود الاكتمال والترقية في §16.

## 10. تكامل `AGENTS.md` في المشروع التابع

يكفي أن يشير `AGENTS.md` في المشروع التابع إلى `STANDARDS_MANIFEST.md` وإلى هذا Adoption Standard، بدل سرد كل Standard يدويًا. عند بدء مهمة، يلتزم الوكيل بمسار resolution المناسب لنوع المهمة.

### 10.1 Normal Engineering Task

في المهمة الهندسية العادية:

1. يقرأ الوكيل `STANDARDS_MANIFEST.md`.
2. يحدد Scope الملفات المتأثرة.
3. يحدد Profile Activations المنطبقة المسجلة محليًا.
4. يستخدم الـ Applicable Standards Set المسجلة في Manifest.
5. يمكنه التحقق من composition عبر Profile manifests المحلية المثبتة.
6. يقرأ Applicable Standards فقط.

في هذا المسار يستخدم الوكيل Final Resolved Applicable Standards المحلية فقط؛ لا يعيد Stage 1 أو Stage 2 ولا يحتاج Candidate Standards غير المنطبقة أو الاتصال بـ upstream. لا يعاد بناء adoption من upstream في كل Task، ولا يحتاج الوكيل الاتصال بـ upstream لإتمام مهمة عادية.

### 10.2 Adoption / Upgrade / Manifest Validation

في Adoption أو Upgrade أو Manifest Validation، تظل Profile manifests المحلية المثبتة هي مدخلات Active Profile Activation وinheritance وControl Set. ومنها، مع Explicit Additional Standard references المعلنة، يُبنى Structural / Transitive Resolution ومجموعة Candidate Standard References. أما تعريفات الـStandards المرشحة اللازمة للتحقق البنيوي في Stage 1 وcanonical applicability في Stage 2، فتُقرأ من exact upstream Adoption Commit، ولا يلزم نسخ Candidate Standard غير منطبقة إلى المشروع المحلي.

يُختار مصدر الـCommit الدقيق كالتالي:

- **Adoption:** exact upstream Adoption Commit المقترح الذي سيسجل في Manifest بعد اكتمال Adoption.
- **Manifest Validation:** exact `Adoption Commit` المسجل في Manifest لإعادة التحقق من كل Candidate Standard reference وتعريفه وcanonical applicability الخاصة به، ثم مقارنة Final Resolved Applicable Standards Set الناتجة بالـManifest.
- **Upgrade:** exact upstream Adoption Commit الجديد المقترح، مع إعادة حل Profile Activations المسجلة ومقارنة النتيجة المقترحة بالـManifest السابقة دون استبدالها قبل اكتمال Upgrade.

يجب أن تتطابق Profile manifests المحلية المثبتة مع Control inputs للـAdoption Commit المستخدم وفق قاعدة §4. لا يضيف هذا الإجراء أي Manifest fields؛ يستخدم `Upstream Repository` و`Adoption Commit` الموجودين. إذا تعذر الوصول إلى exact Commit المطلوب لتنفيذ Structural / Transitive Resolution أو Canonical Standard Applicability، فتكون النتيجة حتمًا `Resolution Status = INVALID` وفق §16.3، ولا تنشأ عن عدم توفره Exception؛ ومع عدم وجود deviation مستقلة تكون `Exception State = NONE`. ينطبق ذلك سواء كان الإجراء Adoption أو Upgrade أو Manifest Validation. لا يستخدم floating `main` أو أي مرجع متحرك، ولا يُخمن أي تعريف، ولا ينشأ fallback source. عدم توفر الـCommit ليس `OWNER DECISION REQUIRED` أو Exception أو deviation قابلة للموافقة، ولا يثبت أن Standard مفقودة أو أن محتوى الـCommit broken؛ بل يعني أن required pinned verification input غير متاح، ولذلك لا يمكن إثبات resolution المطلوبة ولا يجوز للمالك تحويل النتيجة إلى `VALID`.

في Manifest Validation أو Upgrade، تبقى أي Manifest سابقة صحيحة كما هي؛ ولا تنشأ أو تعدل canonical Manifest قبل نجاح إعادة التحقق. مسار الاستعادة الوحيد هو إتاحة exact Commit ثم إعادة تنفيذ Resolution / Validation وإعادة تقييم الحالة. يظل حظر floating refs وحدود Manifest في §16.5 قائمين.

## 11. Additional Standards

يجوز للمشروع إضافة Standard مستقلة خارج Profiles كمرشح عندما تنطبق على Scope محدد. يجب أن يكون مرجعها canonical وصحيحًا بنيويًا، وأن تسجل تحت `Explicit Additional Standards` في Manifest كمدخل معلن، وتظل مثبتة إلى نفس Adoption Commit افتراضيًا. بعد ذلك تخضع للمرحلة الثانية نفسها: لا تجعل الإضافة applicability الخاصة بالـStandard أوسع، ولا تدخل المجموعة النهائية إذا كانت canonical applicability تستبعد الـScope أو الـartifact الفعلي.

لا يجوز استخدام Additional Standards كطريقة غير مباشرة للعودة إلى نسخ كل محتوى `standards/`. يجب أن يكون لكل إضافة سبب applicability ومسار أو Scope واضح.

## 12. الترقية والمراجعة والتتبع

تتم ترقية الاعتماد عبر reviewed change تسجل:

- الـ Adoption Commit الجديد.
- Profiles والإصدارات المستخدمة بعد الترقية.
- إعادة حل Scopes والـ Control Set والـ Applicable Standards Set.
- أي Standard أضيفت أو أزيلت ولماذا.
- أثر الروابط النسبية والاستثناءات.

الترقية لا تعني Refresh شاملًا للمستودع. يعاد Resolve للـ Profiles المفعلة فقط، وتبقى Standards غير المنطبقة خارج النسخة المحلية.

## 13. Adoption Precedence

هذا المعيار يحدد applicability وcomposition، ولا يلغي هرم الأولوية العام في `AI_COLLABORATION_WORKFLOW_AR.md`. عند وجود تداخل:

1. تنتج Profile Activations مع inheritance مجموعة المرشحين البنيوية. ولا يحذف Profile Standard موروثة من هذه المجموعة لمجرد specificity أو preference أو تفعيل Profile أخرى.
2. بعد اكتمال الحل البنيوي، تحدد canonical applicability التي تملكها كل Standard إن كانت تدخل المجموعة النهائية؛ لا يوسع Profile أو Manifest applicability ولا يلغيها.
3. تخضع Explicit Additional Standards للقاعدة نفسها، ولا يحول إدراجها إلى Manifest المرجع غير المنطبق إلى Standard منطبقة.
4. تطبق تعليمات `AGENTS.md` الخاصة بالمشروع وفق أولوية المشروع، وتكون أي deviations أو overrides مسموح بها صراحةً في العقد واضحة ومسجلة.
5. تبقى تعليمات المالك الحالية والقرارات المعتمدة أعلى من عقد الاعتماد وفق هرم المسؤولية المعمول به، لكنها لا تحول reference أو graph مكسورًا إلى Structural Resolution صحيح.

عند تعارض غير محسوم بين مصادر ذات أولوية متقاربة، لا يُحسم بالتخمين؛ يجب تسجيله ورفعه للمالك.

## 14. Invariants

يجب أن تظل الحقائق التالية صحيحة عند اعتماد أي مشروع:

```text
Central canonical source: YES
Adoption Standard present locally: YES
Active Profile manifests pinned locally: YES
Inherited Profile manifests pinned locally: YES
Unused Profile manifests copied: NO
Applicable Standards only: YES
Ordinary task requires upstream network: NO
Manifest independently auditable against pinned local Control inputs and its exact recorded upstream Adoption Commit: YES
Same upstream commit by default: YES
Floating main: NO
Full repository snapshot: NO
Historical audits and decisions copied to consumers: NO
Underlying standards remain source of truth: YES
```

## 15. Adoption Review Checklist

قبل قبول Adoption أو Upgrade، يجب التحقق من:

- [ ] وجود Upstream Repository وAdoption Commit ثابت.
- [ ] وجود Adoption Standard محليًا ضمن Pinned Adoption Control Set.
- [ ] وجود كل Active Profile manifest محليًا ومثبتًا.
- [ ] وجود كل inherited Profile manifest لازمة للحل محليًا ومثبتة.
- [ ] عدم نسخ Profile غير مستخدمة.
- [ ] عدم استخدام floating `main`.
- [ ] عدم نسخ `standards/` بالكامل افتراضيًا أو احتياطيًا.
- [ ] وجود Profile ID وVersion وScope لكل Activation.
- [ ] سلامة inheritance وعدم وجود cycle.
- [ ] وجود وصحة بنيوية لكل مراجع `Required Standards` المباشرة والموروثة في `Stage 1 / Candidate Standard References`؛ يظل أي مرجع Required Standard مفقود أو مكسور `Structural Invalidity` قبل Stage 2.
- [ ] اقتصار `Pinned / Final Resolved Applicable Standards Set` على Standards التي اجتازت canonical applicability الخاصة بها في `Stage 2`.
- [ ] عدم اشتراط إدراج Candidate Standard استُبعدت بصورة deterministic وفق canonical applicability المملوكة لها في مجموعة `Final / Pinned Applicable Standards Set`.
- [ ] تطبيق resolution عابر للـ Profiles دون تكرار يدوي.
- [ ] تطابق Control Set وApplicable Standards Set مع Manifest.
- [ ] عدم حاجة المهمة الهندسية العادية إلى upstream network.
- [ ] الحفاظ على relative links والبنية اللازمة لها.
- [ ] تسجيل Additional Standards والاستثناءات صراحةً.
- [ ] عدم إدخال `docs/audits/` أو `docs/decisions/` في Adoption Set.
- [ ] قراءة الوكيل للـ Standards المنطبقة فقط على Scope المهمة.
- [ ] سلامة البنية قبل تقييم Exceptions، وعدم تحويل Structural Invalidity إلى حالة قابلة للـwaive.
- [ ] تسجيل Resolution Status لكل Profile Activation/Scope بصورة مستقلة، دون إخفاء نتيجة غير صحيحة بنتيجة أخرى.
- [ ] تطبيق أولوية التجميع `INVALID` ثم `OWNER DECISION REQUIRED` ثم `VALID`.
- [ ] الفصل بين Resolution Status وException State واستخدام الحالات الثلاث المحددة لكل منهما فقط.
- [ ] عدم تطبيق Requested Exception أو اعتبارها موافقة.
- [ ] عدم إنشاء أو تحديث canonical Manifest لحالة Adoption/Upgrade غير محسومة، مع إبقاء Manifest صالحة سابقة كما هي أثناء Upgrade غير محسومة.

## 16. حسم Adoption وفشلها والاستثناءات

يحدد هذا القسم نتائج Adoption وUpgrade وManifest Validation. لا ينشئ Resolver أو Validator executable، ولا يغير Profile composition أو inheritance أو حقول Manifest القائمة.

### 16.1 تشخيص كل Profile Activation/Scope

كل Profile Activation مع Scope محدد وحدة تشخيص مستقلة. يربط كل تشخيص بـ`Profile ID` والـScope والـpinned inputs المستخدمة، وينتج بصورة deterministic `Resolution Status` خاصًا به، مع تسجيل `Exception State` الخاصة به عند انطباقها.

لا تدمج نتائج Activations مختلفة في تشخيص واحد قبل تسجيل نتيجة كل منها، ولا تسمح نتيجة `VALID` لـActivation بإخفاء نتيجة `INVALID` أو `OWNER DECISION REQUIRED` لـActivation أخرى. وإذا تكرر Profile على Scopes مختلفة، تسجل نتيجة كل Activation/Scope على حدة؛ لا تنشأ `Aggregate Exception State`.

### 16.2 Overall Resolution Status

القيم الوحيدة لـ`Resolution Status`، على مستوى Activation/Scope والنتيجة الإجمالية، هي:

```text
VALID
INVALID
OWNER DECISION REQUIRED
```

تحسب النتيجة الإجمالية لكل Activations/Scopes المنطبقة وكل قرارات Adoption المعلقة وفق الترتيب التالي:

```text
If ANY applicable Activation / Scope is INVALID
→ Overall Resolution Status = INVALID

Else if ANY applicable Activation / Scope
or any unresolved adoption decision
requires Owner Decision
→ Overall Resolution Status = OWNER DECISION REQUIRED

Else
→ Overall Resolution Status = VALID
```

الأولوية الإلزامية هي:

```text
INVALID
>
OWNER DECISION REQUIRED
>
VALID
```

وجود Owner Decision معلقة لا يغيّر `INVALID` الناتجة عن أي Structural Invalidity أو حالة `INVALID` أخرى إلى `OWNER DECISION REQUIRED`.

### 16.3 Structural Invalidity غير قابلة للاستثناء

تشمل Structural Invalidity، على الأقل:

```text
missing required Standard
missing active Profile
missing required inherited Profile
missing or broken Explicit Additional Standard reference
inheritance cycle
broken required reference
missing mandatory structural metadata
structural Manifest mismatch
```

يشمل `structural Manifest mismatch` عدم مطابقة ما يسجله Manifest فعليًا للـresolved Control Set أو Final Resolved Applicable Standards Set الناتجة من المرحلتين أو Profile Activations أو الـpinned structure المطلوبة. لا يعد عدم تسجيل Candidate Standard مستبعدة canonical ضمن Final Applicable Set mismatch؛ لكن يجب أن تظل كل مراجع Profile وRequired Standards قابلة للتحقق بنيويًا قبل تقييم applicability. يراعي فحص التطابق فقط deviations التي يسمح هذا العقد باستثنائها صراحةً؛ فلا يصبح deviation موثق ومسموح Structural Invalidity لمجرد أنه exception. لكن لا يجوز لأي exception أن يتجاوز أي بند من قائمة Structural Invalidity أعلاه؛ ويظل missing required Standard أو Profile أو inheritance أو reference أو metadata المطلوبة Structural Invalidity غير قابلة للـwaive.

عدم توفر exact upstream Adoption Commit المطلوب لمراجعة أو تنفيذ Structural / Transitive Resolution أو Canonical Standard Applicability يُصنف كفشل تحقق fail-closed، وتكون نتيجته حتمًا `Resolution Status = INVALID`. لا تنشأ عن هذه الحالة Exception؛ وتكون `Exception State = NONE` ما لم توجد deviation مستقلة أخرى تنطبق عليها دورة §16.4. هذا لا يعني أن Standard نفسها مفقودة أو أن محتوى الـCommit broken؛ يعني فقط أن required pinned verification input غير متاح، فلا يمكن إثبات resolution المطلوبة. هذه الحالة ليست Owner Decision أو deviation أو Exception قابلة للموافقة، ولا يملك المالك تحويلها إلى `VALID`. لا تستخدم لها floating ref أو fallback source أو تعريفات مخمّنة. مسار الاستعادة الوحيد هو إتاحة exact Commit ثم إعادة تنفيذ Resolution / Validation وإعادة تقييم الحالة.

كل Structural Invalidity تنتج `Resolution Status = INVALID`. لا يمكن لـOwner Approval تحويل graph أو structure مكسورة إلى `VALID`، ولا يعد قرار المالك إصلاحًا بنيويًا:

```text
Owner Decision
≠
Structural Repair
```

المسار الوحيد لإزالة Structural Invalidity هو:

```text
Detect Structural Invalidity
→ Repair Graph / Structure
→ Re-resolve
→ Re-evaluate
```

لا تنشئ Structural Invalidity نفسها Exception؛ ويظل `Exception State = NONE` ما لم توجد deviation أخرى مستقلة وقابلة للاستثناء أصلًا.

والاستبعاد deterministic وفق canonical Standard applicability في Stage 2 ليس Structural Invalidity أو deviation أو Exception، ولا يحتاج إلى طلب أو موافقة Exception؛ يظل `Exception State = NONE` ما لم توجد deviation مستقلة أخرى.

### 16.4 Exception State ودورة حياة الاستثناء

القيم الوحيدة لـ`Exception State` لكل deviation/Activation ذات صلة هي:

```text
NONE
REQUESTED
APPROVED_AND_DOCUMENTED
```

هذه الحالة مستقلة عن `Resolution Status` ولا تستبدلها. لا تنشأ قيمة Aggregate أو حالة رابعة عند وجود عدة Activations أو Exceptions؛ تحتفظ كل واحدة بحالتها، ثم تطبق قواعد التجميع في §16.2.

تقتصر دورة الاستثناء على deviation أو override يسمح هذا العقد باستثنائها أصلًا، ولا تشمل Structural Invalidity:

- **Unauthorized deviation:** إذا طُبق deviation قبل استيفاء مسار Request وApproval الصالح، بما في ذلك تطبيق Exception ما زالت `REQUESTED`، تكون النتيجة `Resolution Status = INVALID` و`Exception State = NONE`. لا يحول الاستخدام الفعلي غير المصرح به إلى Exception معتمدة.
- **Requested exception:** يبقى الاستثناء غير المطبق قبل القرار في `Resolution Status = OWNER DECISION REQUIRED` و`Exception State = REQUESTED`. الطلب ليس موافقة؛ لا تطبق الـdeviation ولا تعتبر Adoption مكتملة قبله. والموافقة وحدها دون توثيق السبب والنطاق والسلطة لا تحقق `APPROVED_AND_DOCUMENTED` ولا تسمح بالتطبيق أو الاكتمال؛ إذا لم تطبق الـdeviation بعد، تبقى النتيجة `OWNER DECISION REQUIRED` والحالة `REQUESTED` حتى تكتمل متطلبات الاستثناء. وإذا طُبقت قبل ذلك، تنطبق قاعدة `Unauthorized deviation` أعلاه.
- **Approved exception:** بعد موافقة المالك وتوثيق سبب الاستثناء ونطاقه والسلطة المعتمدة له، تكون `Exception State = APPROVED_AND_DOCUMENTED`. يمكن أن تكون `Resolution Status = VALID` فقط عندما تكون graph سليمة، ولا توجد Structural Invalidity أو Owner Decisions أخرى معلقة، ويسجل الاستثناء في Manifest عند اكتمال Adoption.

إذا كان المطلوب Owner Decision لا يتعلق بطلب Exception، مثل ambiguity غير محسومة، تكون النتيجة `Resolution Status = OWNER DECISION REQUIRED` و`Exception State = NONE`.

### 16.5 حدود canonical Manifest أثناء Adoption وUpgrade

`STANDARDS_MANIFEST.md` هو record لاعتماد مكتمل فقط. لا ينشأ ولا يحدث ليعرض proposed state تكون `Overall Resolution Status` فيها `INVALID` أو `OWNER DECISION REQUIRED`. لا تنشأ Draft Manifest أو أداة/ملف بديل لها؛ تحفظ الحالات المقترحة والقرارات غير المحسومة في review evidence فقط.

إذا كان للمشروع Manifest سابقة تمثل Adoption صحيحة، ثم أصبحت Upgrade مقترحة `INVALID` أو `OWNER DECISION REQUIRED`، تبقى Manifest المعتمدة الحالية كما هي ولا تستبدل بالحالة المقترحة. لا تثبت Manifest السابقة صلاحية الـUpgrade الجديدة. لا تستبدل بها إلا بعد أن تصبح نتيجة الـUpgrade الجديدة `VALID` وتكتمل Adoption.

لا يضيف هذا القسم حقولًا جديدة إلى Manifest؛ يسجل الحقول القائمة النتيجة المكتملة والاستثناءات المعتمدة والموثقة فقط.

### 16.6 Fail-Closed Review Contract

في Adoption أو Upgrade أو Manifest Validation، يسير الفحص بالترتيب التالي:

1. تنفيذ Structural / Transitive Resolution فعليًا من ملفات Profiles المحلية المثبتة، مع جمع والتحقق من كل Required Standard references وExplicit Additional Standard references لإنتاج Candidate Standard References.
2. التحقق من Structural Integrity لكل المرشحين والبنية قبل أي تصفية بسبب applicability أو تقييم للاستثناءات؛ يظل المرجع المكسور Structural Invalidity حتى إذا كان متوقعًا استبعاده في المرحلة التالية.
3. تنفيذ Canonical Standard Applicability لكل Candidate Standard مقابل كل Activation/Scope وحقائق الـartifact، وتكوين Final Resolved Applicable Standards Set؛ لا يطبق Profile أو Additional Standard override على scope المعيار الأصلي.
4. تشخيص كل Activation/Scope على حدة، بما في ذلك نتيجة applicability؛ والاستبعاد deterministic لا ينتج حالة Invalid أو Exception.
5. تقييم الاستثناءات المسموح بها وحالتها، دون تطبيق أي Requested Exception.
6. حساب Overall Resolution Status بقواعد §16.2.
7. عدم اعتبار Adoption مكتملة إلا إذا كانت `Overall Resolution Status = VALID`.

`OWNER DECISION REQUIRED` ليست نجاحًا، و`REQUESTED` ليست موافقة، و`APPROVED_AND_DOCUMENTED` لا تصلح Structural Invalidity.
