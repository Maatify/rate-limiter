# Phase 4 — Text & Placeholder Utilities
**Version:** 1.0.0
**Status:** ✅ Completed
**Category:** Text Processing / Rendering Utilities
**Date:** 2025-11-09

---

## 🧩 Overview

Phase 4 introduces the **Text & Placeholder Utilities**, a comprehensive collection of text-processing tools that standardize formatting, normalization, placeholder rendering, and secure text comparison across the Maatify ecosystem.

This module is one of the most frequently used layers across all libraries — powering email templates, SMS formatting, logging, reporting, command output, API responses, and multi-language content.

The utilities are lightweight, safe, deterministic, and designed for high-performance usage in production systems.

---

## 🎯 Goals

- Provide standardized text processing utilities across all maatify libraries.
- Support safe placeholder rendering using nested dot-notation variables.
- Offer normalization, slugification, and formatting helpers.
- Introduce timing-safe comparison for authentication/security workflows.
- Add regex utilities for common matching and replacement operations.
- Ensure consistent text behavior across locales and environments.

---

## 📦 Tasks Completed

- Added `PlaceholderRenderer` supporting:
    - simple placeholders (`{{name}}`)
    - nested variables (`{{user.name}}`)
    - full array-based rendering
    - failsafe behavior when placeholders are missing
- Implemented `TextFormatter` with:
    - slugify
    - normalize
    - titleCase
    - transliteration fallback
- Added `RegexHelper` providing safe wrappers for replace/match logic.
- Added `SecureCompare`, a timing-attack-safe string comparison utility.
- Ensured all utilities are stateless for performance and thread-safety.
- Added detailed documentation and usage examples.

---

## 🗂 Files Created / Updated

### **Source Files**

- `src/Text/PlaceholderRenderer.php`
- `src/Text/TextFormatter.php`
- `src/Text/RegexHelper.php`
- `src/Text/SecureCompare.php`

### **Directory Structure**

```
src/Text/
├── PlaceholderRenderer.php
├── TextFormatter.php
├── RegexHelper.php
└── SecureCompare.php
```

---

## 🧪 Tests Added

### **Test Files**

- `tests/Text/PlaceholderRendererTest.php`
- `tests/Text/TextFormatterTest.php`
- `tests/Text/RegexHelperTest.php`
- `tests/Text/SecureCompareTest.php`

### **Coverage Highlights**

- Placeholder rendering:
    - nested keys
    - missing keys
    - escaped vs non-escaped output
- Text normalization under multiple locales
- Regex replacement and pattern validation
- Timing-safe comparison (resistance to early exit attacks)
- Slugification tests for multilingual and Unicode strings

### **Result**
- Coverage: **~100%**
- Fully stable under PHP 8.4+
- Deterministic and predictable behavior verified

---

## 🧠 Technical Notes

- `SecureCompare` is critical for:
    - token verification
    - code validation
    - HMAC signature matching
- `TextFormatter::normalize()` ensures consistency when dealing with:
    - accented characters
    - Unicode sequences
    - mixed-locale input

- Placeholder rendering avoids:
    - template injection
    - recursive unintended replacements
- RegexHelper centralizes pattern handling to prevent invalid regex crashes.

---

## 🔗 Related Versions

- **Introduced in:** v1.0.0
- Forms essential infrastructure for:
    - maatify/messaging-core
    - maatify/security-guard
    - maatify/api-core
    - maatify/i18n

---

## 🔜 Next Phase

**Phase 5 — Date & Time Utilities**
Provides humanized time differences, timezone conversions, and localized date formatting.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---