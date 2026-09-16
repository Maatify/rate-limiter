# Maatify PHP Library Repository Presentation Standard

## Standard Metadata

- **Standard ID:** `std-library-presentation`
- **Standard Version:** `1.0.1`
- **Standard Version Format:** `MAJOR.MINOR.PATCH`

## 1. Normative Language

The key words "MUST", "MUST NOT", "REQUIRED", "SHOULD", "SHOULD NOT", "MAY", and "OPTIONAL" in this document are to be interpreted as described in RFC 2119.

* `MUST / MUST NOT`: These are binding rules.
* `SHOULD / SHOULD NOT`: These represent the default expected behavior. Any deviation requires a documented reason.
* `MAY`: This is an optional choice allowed depending on the nature of the library.

## 2. Scope and Relationship to Other Standards

This Standard is responsible for governing:
* Repository presentation.
* README visual and information architecture.
* Badges.
* Governance document identity.
* Release-facing documentation state.
* Author and ecosystem identity.
* GitHub description, topics and PR metadata.
* SemVer Release Candidate presentation and first Stable release readiness.

It explicitly does **not** govern:
* Runtime architecture.
* Public API design.
* Database behavior.
* Exception hierarchy.
* Test architecture.
* CI implementation.
* Dependency constraints.

### Relationship to other standards:
* `PACKAGE_BUILDING_STANDARD.md`: Governs library building, code architecture, and the package contract.
* `COMPOSER_PACKAGE_STANDARD.md`: Governs Composer metadata, dependencies and Composer stability constraints, autoloading, scripts, configuration, and lock-file policy.
* `CI_WORKFLOW_STANDARD.md`: Governs CI, quality gates, and automated testing.

This Standard owns the release-facing lifecycle and first Stable readiness. Composer stability constraints govern dependency resolution; they do not define release eligibility or publication state.

Each Standard has clear boundaries and must not duplicate the content of another.

## 3. Applicability

The current version of this Standard applies exclusively to:
`Maatify standalone PHP Composer libraries`

It MUST NOT be generalized to JavaScript, Rust, or any other language.
Every other language or ecosystem MUST have a separate Standard when needed.

The canonical README footer is defined exclusively in [Section 18](#18-canonical-maatify-footer).
Do not replace `PHP Libraries` with `Software Libraries` or any other generic term.

## 4. Canonical Placeholders

The examples in this Standard use canonical placeholders. When applying these templates, the placeholders MUST be replaced with actual values.

* `{PACKAGE_DISPLAY_NAME}`: The human-readable display name of the package.
* `{COMPOSER_PACKAGE_NAME}`: The full Composer package name (e.g., `vendor/package`).
* `{REPOSITORY_SLUG}`: The repository name within the Maatify organization.
* `{PACKAGE_BADGE_NAME}`: The display name used inside badges.
* `{PACKAGE_BADGE_TOKEN}`: The text used within shields.io badges (note that hyphens `-` in the text might need to be encoded as `--`).
* `{PACKAGE_REFERENCE_FILE}`: The specific reference markdown file for the package.
* `{MINIMUM_PHP_VERSION}`: The minimum supported PHP version.
* `{SUPPORTED_MAJOR_LINE}`: The actively supported release line.
* `{RELEASE_VERSION}`: The version number for the release.
* `{RELEASE_DATE}`: The date of the release.
* `{BADGE_AREA}`: The complete rendered badge groups selected for the current library according to the badge architecture defined by this Standard.
* `{PACKAGE_SUMMARY}`: A concise and technically accurate summary of the library's primary purpose and supported scope.
* `{ENCODED_COMPOSER_PACKAGE_NAME}`: The URL-encoded Composer package name used inside shields.io badge text, with `/` encoded as `%2F`.

*Note: This standard MUST NOT contain hardcoded names of specific existing packages as default templates. Always use the placeholders.*

## 5. Core Presentation Principles

1. Every library MUST clearly look like a part of the Maatify ecosystem.
2. The first screen of the README MUST communicate:
   * The library name.
   * Its ecosystem identity.
   * Its primary purpose.
   * The package status.
   * How to install or access it.
3. The presentation MUST NOT claim features, support, or a quality status that is not proven.
4. The shared identity MUST NOT erase the functional differences between libraries.
5. A SemVer Release Candidate MUST be presented against its actual pre-release version before its tag and distribution are published.
6. Copying the README or governance files from another library without replacing all names and links is strictly forbidden.
7. The GitHub-rendered appearance is the ultimate reference, not just the raw Markdown source.
8. Presentation changes MUST NOT alter runtime contracts.

## 6. Required Release-Facing Files

Any Maatify PHP library ready for release MUST contain the following files (where applicable):

* `README.md`: Quickly introduces the library.
* `{PACKAGE_REFERENCE_FILE}`: The detailed source of truth for contracts.
* `CHANGELOG.md`: Documents releases.
* `SECURITY.md`: Defines support, reporting, and scope.
* `CONTRIBUTING.md`: Explains contribution and local verification.
* `CODE_OF_CONDUCT.md`: Establishes community rules.
* `LICENSE`: The package license.
* `composer.json`: The package definition.

The `README.md` MUST NOT be overloaded with all the details present in the Package Reference.

## 7. README Header Standard

By default, the README header MUST contain:
1. Package display name.
2. Maatify logo.
3. Badge area.
4. Package summary.
5. A separator before the detailed content.

### Canonical Template:
```markdown
<div align="center">

# {PACKAGE_DISPLAY_NAME}

![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

{BADGE_AREA}

{PACKAGE_SUMMARY}

</div>

---
```

* Center alignment is the recommended default. It MAY be deviated from only with a documented reason.
* The logo link MUST point to the approved Maatify source.
* Logos of other libraries MUST NOT be added.
* The package summary MUST be concise and accurate.

## 8. README Badge Architecture

Badges MUST be divided into logical groups.

### 8.1 Package Status
For a Composer library published or intended for Packagist that is preparing publication of a SemVer pre-release, preparing its first Stable release, or has at least one published Stable version, the following badge markup MUST be prepared:
* Latest Version.
* PHP Version.
* License.
* PHPStan Level Max (as long as it is actually proven in the project).

During first Stable Release Preparation, badge markup is prepared internally. A live `Latest Version` badge MUST NOT be displayed until a Stable version has actually been published. A published pre-release MAY be shown only as an explicitly labeled pre-release according to Section 8.4.

### 8.2 Documentation
Clear badges or links MUST be prepared for:
* Changelog.
* Package Reference.
* Security Policy.
* Contributing Guide.

### 8.3 Ecosystem and Adoption
Where applicable, the following MUST be prepared:
* Monthly Downloads.
* Total Downloads.
* Maatify Ecosystem.
* Install.

### 8.4 SemVer Pre-release Badge Rule
When Packagist is the selected package registry, badge markup for an intended SemVer pre-release or Stable release MAY be prepared before its tag is created when publication is planned immediately after owner approval. Preparing markup does not create a release or establish that a Release Candidate exists. Live Packagist badges MUST NOT be exposed before the package is actually published and the version name is valid.

This rule applies only when Packagist is the library's selected package registry or when immediate Packagist publication is part of the approved release plan.

However, before the package actually exists on Packagist, Packagist Version, PHP, License, and Downloads badges MUST NOT be displayed live, because Shields will render them as `not found` (even though the endpoint returns HTTP 200).
Before the first Stable Tag, a version badge MUST NOT be labeled `Latest Version` if no Stable release exists. The default Packagist version badge cannot be relied upon to display pre-releases without configuration. Using the `include_prereleases` parameter is optional and only permitted when there is an explicit decision to display a pre-release clearly labeled as such, not as Stable.

### 8.5 Badge Style
This standard does not force `style=for-the-badge` on the README.
* Small badges MAY be used.
* `for-the-badge` MAY be used.
* The standard is visual consistency.
* Badge groups MUST NOT mix sizes and styles randomly.
* The final selection MUST be reviewed against GitHub rendering.
* The exact number of lines is not strictly required to be identical across libraries.

### 8.6 Badge Accuracy
Every Badge MUST:
* Refer to the current library.
* Use the correct Composer package name.
* Use the correct Repository slug.
* Link to the correct local file or external page.
* Not point to a different reference library.
* Not claim a quality gate that does not exist.
* Not claim a License different from the actual one.

## 9. Canonical Composer / Packagist Badge Templates

*Note: The following live templates MUST ONLY be exposed when the conditions described in Section 8.4 are met.*

### Package Status
```markdown
[![Latest Version](https://img.shields.io/packagist/v/{COMPOSER_PACKAGE_NAME}.svg)](https://packagist.org/packages/{COMPOSER_PACKAGE_NAME})
[![PHP Version](https://img.shields.io/packagist/php-v/{COMPOSER_PACKAGE_NAME}.svg)](https://packagist.org/packages/{COMPOSER_PACKAGE_NAME})
[![License](https://img.shields.io/packagist/l/{COMPOSER_PACKAGE_NAME}.svg)](LICENSE)
```
*(Plus PHPStan where proven)*

### Ecosystem and Usage
```markdown
[![Monthly Downloads](https://img.shields.io/packagist/dm/{COMPOSER_PACKAGE_NAME})](https://packagist.org/packages/{COMPOSER_PACKAGE_NAME})
[![Total Downloads](https://img.shields.io/packagist/dt/{COMPOSER_PACKAGE_NAME})](https://packagist.org/packages/{COMPOSER_PACKAGE_NAME})
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-blueviolet)](https://github.com/Maatify)
[![Install](https://img.shields.io/badge/Install-composer%20require%20{ENCODED_COMPOSER_PACKAGE_NAME}-blue)](https://packagist.org/packages/{COMPOSER_PACKAGE_NAME})
```
*Note: Ensure proper URL encoding for the install badge (e.g., `%2F` for `/`).*

### Documentation
```markdown
[![Changelog](https://img.shields.io/badge/Changelog-View-blue.svg)](CHANGELOG.md)
[![Package Reference](https://img.shields.io/badge/Reference-Read-blue.svg)]({PACKAGE_REFERENCE_FILE})
[![Security Policy](https://img.shields.io/badge/Security-Policy-blue.svg)](SECURITY.md)
[![Contributing Guide](https://img.shields.io/badge/Contributing-Guide-blue.svg)](CONTRIBUTING.md)
```

## 10. README Section Architecture

The general default order of sections is:
1. Header and Identity
2. Package Summary
3. Key Features
4. Requirements
5. Installation
6. Quick Usage / Usage
7. Public Runtime API
8. Critical Runtime Behavior
9. Architecture Guarantees
10. Exception and Error Propagation
11. Security and Trust Boundaries
12. Examples
13. Documentation
14. Quality Status
15. Development and Testing
16. License
17. Author
18. Maatify Footer

### Required Sections
Any release-ready library MUST include:
* Package Summary.
* Key Features.
* Requirements.
* Installation.
* Usage or Quick Usage.
* Documentation.
* Quality Status.
* License.
* Author.
* Maatify Footer.

### Conditional Sections
These sections are added only when they apply:
* Public Runtime API.
* Critical Runtime Behavior.
* Architecture Guarantees.
* Exception and Error Propagation.
* Security and Trust Boundaries.
* Examples.
* Schema.
* Integration Testing.
* Migration Guide.
* Upgrade Notes.

Do not create empty sections merely to satisfy a template.

## 11. Heading and Emoji Rules

* Emoji MAY be used.
* If used, they MUST be consistent across parallel headings.
* Do not use emoji randomly in some sections while leaving similar sections unstyled.
* The heading hierarchy MUST be semantically correct.
* There MUST be one logical `#` main heading.
* Separators `---` MAY be used between major groups, but without excess.
* The presentation MUST NOT become cluttered or overly decorative at the expense of clarity.

## 12. Runtime and Documentation Accuracy

The Presentation Standard does not allow altering technical facts.
The README MUST:
* State the actual runtime requirements.
* State the actual dependencies.
* Describe the actually supported databases.
* Clarify concurrency, transaction, or error propagation when public contracts require it.
* Distinguish between package-defined exceptions and external throwables.
* Not omit critical contracts for the sake of appearance.
* Not copy runtime claims from another library.
* Link to the reference documentation for lengthy details.

## 13. Governance Document Identity Badges

Standard identity badges MUST be present in specific locations.

### 13.1 `CODE_OF_CONDUCT.md`
Defaults to starting with:
```markdown
# Code of Conduct — {COMPOSER_PACKAGE_NAME}

[![Maatify {PACKAGE_BADGE_NAME}](https://img.shields.io/badge/Maatify-{PACKAGE_BADGE_TOKEN}-blue?style=for-the-badge)](https://github.com/Maatify/{REPOSITORY_SLUG})
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)
```

### 13.2 `SECURITY.md`
Defaults to starting with:
```markdown
# Security Policy

[![Maatify {PACKAGE_BADGE_NAME}](https://img.shields.io/badge/Maatify-{PACKAGE_BADGE_TOKEN}-blue?style=for-the-badge)](https://github.com/Maatify/{REPOSITORY_SLUG})
[![Maatify Ecosystem](https://img.shields.io/badge/Maatify-Ecosystem-9C27B0?style=for-the-badge)](https://github.com/Maatify)
```

### Mandatory Rules
* `style=for-the-badge` MUST be used in these two locations.
* The first badge MUST point to the current repository.
* The Ecosystem badge MUST point to `https://github.com/Maatify`.
* The name or repository link of another library MUST NOT be used.
* A third badge MUST NOT be added without a documented decision.
* The large Maatify logo MUST NOT be added inside these files.
* Do not copy errors or links from an older reference file.

### Approved Locations
Identity badges are required by default in:
* `CODE_OF_CONDUCT.md`
* `SECURITY.md`

They are NOT automatically added to:
* `CHANGELOG.md`
* `CONTRIBUTING.md`
* `LICENSE`
Unless a subsequent decision alters this Standard.

## 14. First Stable Release Lifecycle and SECURITY Presentation States

**Publication State Definition:** For this Standard and cross-Standard use, a package/version is Published only when that exact package identity and exact version are externally resolvable and installable by an external consumer through an actual recognized Composer distribution source. Packagist MAY be such a source, but it is not the only possible source. A branch, commit, tag, GitHub Release, Draft PR, successful CI run, local path repository, documentation claim, or Git tag without evidence of external Composer resolution and installation does not by itself establish Published state. This definition clarifies publication state and does not replace or weaken the release lifecycle, exact tagged RC installation, Consumer Verification Harness, two independent Real Host validations, release evidence, or owner release authorization below.

### 14.1 First Stable Release Gate

This gate applies only to a package that has never published a Stable release. Its required sequence is:

```text
Development
→ SemVer RC
→ Consumer Verification Harness
→ Real Host Validation in 2+ independent projects/Hosts
→ Stable
```

A SemVer Release Candidate (RC) MUST be an actual SemVer pre-release of the intended Stable version, such as the tag `v1.0.0-rc.1`. It MUST be published and resolvable by an external consumer through the package's approved distribution channel. For a Composer library, consumers MUST be able to resolve and install that exact tagged version through its actual Composer distribution source. A branch, Draft PR, successful CI run, documentation state, local path repository, or unpublished tag MUST NOT be treated as a SemVer RC.

For this first-Stable sequence, the Consumer Verification Harness MUST verify the exact published RC after it becomes externally consumable. The Harness's test semantics remain owned by the [Testing Standard](../testing/TESTING_STANDARD.md). Real Host Validation MUST then use that same RC in at least two independent real projects/Hosts. Reusing one Harness twice or testing multiple environments of one Host does not satisfy the two-Host requirement. Release readiness MUST retain verifiable evidence that identifies the RC version and each Host validation.

CI success and Consumer Verification Harness success alone MUST NOT authorize the first Stable release. Until the published RC, Harness, and both independent Host validations have passed, the package MUST remain Pre-Stable. The two-Host requirement applies only before the first Stable release; it is not retroactive for packages that already have a published Stable release and MUST NOT be repeated as a condition for later patch, minor, or Stable releases.

Tagging, releasing, publishing, and owner approval remain governed by the applicable release controls. This Standard does not authorize those actions.

### 14.2 Development State
When neither a SemVer RC nor a Stable release has been published, `SECURITY.md` MAY state that there is currently no supported Stable release line. When a SemVer RC has been published, Section 14.3 applies; an RC does not become a Stable release or a supported Stable line.

### 14.3 Published SemVer RC State
A SemVer RC exists only after its actual pre-release version and tag are published and available to external consumers through the approved distribution channel. `SECURITY.md` MUST describe that version as a pre-release and MUST NOT present the target Stable version as already published. A SemVer RC does not establish a new supported Stable line; existing published Stable support lines remain governed by the actual support policy.

### 14.4 Stable Release Preparation State
After the SemVer RC, Consumer Verification Harness, and Real Host Validation steps in Section 14.1 have passed, release-facing files MAY be prepared internally for the target Stable version and date while awaiting owner approval and publication. This state MUST be called Stable Release Preparation or release preparation; it MUST NOT be called a Release Candidate. Until the Stable tag is published and the version is available through the approved distribution channel, the files and their PR metadata MUST NOT claim that the Stable version exists, is published, or is already supported. The target Stable support wording MUST be synchronized with the actual policy before publication.

### 14.5 Published Stable State
After the Stable tag is published and the version is available to consumers through the approved distribution channel, the Security Policy enters the Published Stable State. It MUST be prepared for the actual supported release lines.

For one supported major line, a `SECURITY.md` section MAY use:

```markdown
## Supported Versions

The actively supported release line is `{SUPPORTED_MAJOR_LINE}`.

Security fixes are provided in the latest stable release within the supported `{SUPPORTED_MAJOR_LINE}` line. Users should upgrade to the latest available `{SUPPORTED_MAJOR_LINE}` version before reporting a vulnerability.
```

A table MAY be used when it accurately represents the published support policy:

```markdown
| Version | Supported |
|---------|-----------|
| `{SUPPORTED_MAJOR_LINE}` | Yes |
| Older lines | No |
```

In the Published Stable State, `SECURITY.md` MUST:

- describe only release lines that are currently supported by published stable releases
- identify every supported major line when more than one major line is actively supported
- direct users to the latest stable release within each supported line before reporting a vulnerability
- remove any wording that describes the package as unreleased, pre-release, or awaiting publication
- remain synchronized with the actual support policy whenever a supported line is added, replaced, or retired

A future release line MUST NOT be presented as actively supported before its first Stable Tag exists. SemVer RC and Stable Release Preparation states do not establish Stable support.

Publishing a patch or minor release within an already supported major line does not require a Security Policy change unless the file names an exact version, changes the support scope, or otherwise becomes inaccurate.

If support for a release line is withdrawn, `SECURITY.md` MUST be updated as part of the same owner-approved release or governance change that withdraws support.

### 14.6 Major Version Preservation

This rule applies to packages that have published a Stable release; it does not extend the first-Stable release gate in Section 14.1. A Stable package MUST preserve its current Major version whenever the intended change can reasonably be delivered compatibly.

A Major release MUST be used only for a genuine breaking public-contract change that cannot reasonably be contained through an additive API, a deprecation cycle, a compatibility adapter or shim, a migration path, or a staged replacement.

Internal refactors, implementation cleanup, documentation or presentation changes, naming normalization by itself, and compatible additive capabilities MUST NOT justify a Major version bump.

When a breaking public-contract change makes a Major release unavoidable, its compatibility impact and migration path MUST be explicit, reviewed decisions. The Major bump MUST NOT follow automatically from a modernization, refactor, cleanup, or naming change.

## 15. CHANGELOG Presentation Standard

The CHANGELOG MUST follow these rules:
* Keep a Changelog format.
* Semantic Versioning.
* `[Unreleased]` MUST always be present at the top.
* Every published release MUST have its exact version and release date.
* Release links MUST be at the bottom of the file.
* Do not list features that do not exist.
* Do not describe future changes as already implemented.
* A SemVer RC entry MUST use its exact pre-release version and matching release date/tag, such as `1.0.0-rc.1`.
* Stable Release Preparation MAY stage the target Stable version and planned date in an internal release PR after the first-Stable gates pass. Before its Stable tag is published, the entry MUST remain clearly a preparation and MUST NOT imply that the Stable release already exists.

### Template:
```markdown
## [Unreleased]

## [{RELEASE_VERSION}] - {RELEASE_DATE}
```

### Links Template:
```markdown
[Unreleased]: https://github.com/Maatify/{REPOSITORY_SLUG}/compare/v{RELEASE_VERSION}...HEAD
[{RELEASE_VERSION}]: https://github.com/Maatify/{REPOSITORY_SLUG}/releases/tag/v{RELEASE_VERSION}
```

## 16. CONTRIBUTING Presentation

The `CONTRIBUTING.md` file MUST clarify:
* Package identity.
* Package boundaries.
* Ways to contribute.
* Local verification commands.
* Test and Integration requirements.
* Pull Request expectations.
* Architecture discussion requirements.
* Security-reporting route.
* `composer.lock` policy (according to the library's nature).

Identity badges are NOT forced inside this file in the current version of the Standard.
The file MUST NOT preemptively enforce a GitHub Issue for every change unless this is an approved repository policy.

## 17. Canonical Author Block

The following Author block is approved for Maatify PHP libraries:

```markdown
## 👤 Author

Engineered by **Mohamed Abdulalim** ([@megyptm](https://github.com/megyptm))<br>
Backend Lead & Technical Architect<br>
[https://www.maatify.dev](https://www.maatify.dev)
```

### Formatting Rules
* Use `<br>` for mandatory line breaks.
* Do not use hidden trailing spaces as a line break mechanism.
* Do not alter the name.
* Do not alter the GitHub username.
* Do not alter the title.
* Do not alter the Maatify link without explicit permission.

If the library does not use Emoji in headings, it is acceptable to use:
```markdown
## Author
```
while keeping the content of the section unchanged.

## 18. Canonical Maatify Footer

The absolutely final element in the README MUST be:

```markdown
---

<div align="center">

[Built with ❤️ by Maatify.dev — Unified Ecosystem for Modern PHP Libraries](https://www.maatify.dev)

</div>
```

No text may appear after the closing `</div>`.
Do not replace `Modern PHP Libraries` with any other generic wording within this Standard.

## 19. Package and Repository Metadata

This Standard covers presentation-facing consistency only.

### Composer Metadata

The canonical construction and validation rules for Composer metadata are defined by [COMPOSER_PACKAGE_STANDARD.md](COMPOSER_PACKAGE_STANDARD.md).

This section governs only presentation-facing consistency between Composer, README, Packagist, and GitHub metadata.

* `description` MUST remain accurate and consistent with the public package presentation.
* `keywords` MUST remain relevant and MUST NOT contradict GitHub Topics.
* `homepage` MUST point to the current repository.
* Author metadata MUST match the approved Maatify identity.
* License metadata MUST match `LICENSE`.

Dependency declarations, constraints, autoloading, scripts, configuration, stability, and lock-file policy are governed exclusively by [COMPOSER_PACKAGE_STANDARD.md](COMPOSER_PACKAGE_STANDARD.md).

### GitHub Metadata
The following MUST be reviewed:
* Repository description.
* Website.
* Topics.
* Releases visibility.
* Packages visibility (when applicable).

Topics or descriptions MUST NOT claim features that do not exist.

## 20. Pull Request Presentation Metadata

Any PR preparing publication of a SemVer RC or Stable Release Preparation MUST contain:
* A Title identifying the exact RC target or target Stable version and the actual release-preparation state.
* A Body describing the actual changes.
* A Scope confirmation.
* For a first-Stable Release Preparation PR, verifiable references to the published RC, Consumer Verification Harness, and both independent Host validations.
* A release-control statement confirming that no Merge, Tag, Release, or distribution publication occurs without owner approval.
* Accurate wording that distinguishes an unpublished target version, a published SemVer RC, and a published Stable release.

PR metadata MUST NOT describe a prepared target version as already published. If a Stable Release Preparation is pending its Stable tag, the body MUST say that the Stable release is not yet published, identify the actual published RC, and state that Stable publication awaits owner approval. Version and date wording MUST match what is known; an unknown date MUST NOT be represented as a committed release date.

If the PR scope changes, the Title and Body MUST be updated to remain an accurate historical record.

## 21. Visual Review Rules

Before presentation is considered ready, the following MUST be verified:
1. Review the GitHub-rendered README.
2. Ensure badges are not clustered messily.
3. Verify badge size consistency.
4. Check all links.
5. Verify the Composer name and Repository slug in every Badge.
6. Ensure the logo renders correctly.
7. Verify the heading hierarchy.
8. Verify the Author line breaks (`<br>`).
9. Verify the Footer is the very last element.
10. Ensure a final newline exists.
11. Ensure there are no names or links belonging to other libraries.
12. Ensure no Runtime contracts were deleted during the visual polish.

## 22. Anti-Copy and Repository Isolation Rules

When using another library as a visual reference, the implementer MUST NOT copy:
* Package names.
* Repository URLs.
* Composer names.
* Badge labels.
* Security links.
* Author variants.
* Runtime claims.
* Dependencies.
* Database support.
* Release version.
* Release date.

An explicit search for reference repository names MUST be conducted before submission.

## 23. Release Presentation and First Stable Readiness Checklist

* [ ] The first-Stable gate is applied only when the package has no previously published Stable release.
* [ ] The SemVer RC is an actual tagged pre-release of the target Stable version and is resolvable by an external consumer through the approved distribution channel.
* [ ] The Consumer Verification Harness passed against that exact published RC.
* [ ] Real Host Validation passed against that same RC in at least two independent projects/Hosts, and verifiable evidence for both is retained.
* [ ] The same Harness was not counted twice, and two environments of one Host were not counted as two projects/Hosts.
* [ ] Stable Release Preparation is identified as preparation, not as another Release Candidate.
* [ ] Before the Stable tag and release exist, README, CHANGELOG, SECURITY, badges, and PR metadata do not claim a published or supported Stable version.
* [ ] The two-Host gate is not imposed on already-Stable packages or later patch, minor, or Stable releases.
* [ ] README header and Maatify identity are present.
* [ ] Required badges exist and point to the current package.
* [ ] Packagist badges are complete when the library uses or is being prepared for immediate publication on Packagist.
* [ ] README sections match the package's actual behavior.
* [ ] Critical runtime contracts remain documented.
* [ ] CODE_OF_CONDUCT identity badges are correct.
* [ ] SECURITY identity badges are correct.
* [ ] SECURITY supported release line is correct.
* [ ] CHANGELOG contains `[Unreleased]`.
* [ ] CHANGELOG contains the target version and date.
* [ ] Release links target the current repository.
* [ ] CONTRIBUTING reflects actual local verification.
* [ ] Author block uses visible `<br>` line breaks.
* [ ] The canonical PHP-library footer is the final README element.
* [ ] Composer and GitHub metadata are accurate.
* [ ] PR title and body match the actual published RC or Stable Release Preparation state.
* [ ] No foreign package names or URLs remain.
* [ ] No `composer.lock` was introduced when the library does not track it.
* [ ] CI Gate is successful.
* [ ] No Merge, Tag, Release, or Packagist action occurred without owner approval.

## 24. Non-Goals

This Standard explicitly does NOT force:
* The exact same Runtime sections on every library.
* The exact same number of examples.
* The exact same number of Documentation links.
* The exact literal Emoji set.
* `for-the-badge` inside the README.
* Packagist badges on a library not utilizing Packagist.
* Database or framework claims.
* Empty sections.
* Verbatim README copying from another library.
* Runtime changes during presentation work.
