# Phase 5 — Date & Time Utilities
**Version:** 1.0.0
**Status:** ✅ Completed
**Category:** Date/Time Processing & Localization
**Date:** 2025-11-09

---

## 🧩 Overview

Phase 5 introduces the **Date & Time Utilities**, providing unified, localized, and human-friendly date/time handling across all Maatify libraries.

The purpose of this module is to eliminate duplicated date logic scattered across services and to ensure every component — from logs, notifications, analytics, UI displays, and API responses — uses **standard, predictable, and locale-aware** date utilities.

This phase ensures the library handles:

- Localized formatting (Arabic / English / French)
- Humanized time differences
- Timezone conversion for global deployments
- Consistent formatting across backend and UI clients

---

## 🎯 Goals

- Provide standardized tools for date/time manipulation.
- Support localized output for multiple languages.
- Add humanized "time ago" formatting.
- Ensure timezone-safe operations.
- Deliver API-level consistency for timestamp representation.
- Guarantee proper handling of DateTime and DateTimeImmutable objects.

---

## 📦 Tasks Completed

- Implemented `DateFormatter` with:
    - `humanizeDifference()`
    - localized month/day names
    - short and long formatting modes
- Implemented `DateHelper` with:
    - timezone conversion helpers
    - localized `toLocalizedString()`
    - support for EN / AR / FR
- Added internal handling for Arabic Indic digits (١٢٣٤٥…)
- Ensured robust fallback when Intl extension behaves inconsistently.
- Added full test coverage verifying:
    - localization accuracy
    - timezone correctness
    - formatting integrity
    - edge cases (nulls, invalid types, future dates)

---

## 🗂 Files Created / Updated

### **Source Files**

- `src/Date/DateFormatter.php`
- `src/Date/DateHelper.php`

### **Directory Structure**

```
src/Date/
├── DateFormatter.php
└── DateHelper.php
```

---

## 🧪 Tests Added

### **Test Files**

- `tests/Date/DateFormatterTest.php`
- `tests/Date/DateHelperTest.php`

### **Coverage Highlights**

- Humanized difference:
    - seconds/minutes/hours/days/weeks/months/years
    - pluralization handling
    - locale-aware units
- Localized formatting:
    - Arabic → (٩ نوفمبر ٢٠٢٥)
    - English → (November 9, 2025)
    - French → (9 Novembre 2025)
- Timezone conversions tested against:
    - Africa/Cairo
    - America/New_York
    - UTC

### **Result**
- Coverage: **99%**
- Verified correctness across multiple locales and timezones.

---

## 🧠 Technical Notes

- Arabic digit rendering is done using Unicode transformations to ensure UI clients display correct glyphs.
- Uses `IntlDateFormatter` where available, with graceful fallback.
- `humanizeDifference()` intentionally avoids external libraries to maintain portability.
- Timezone conversion is fully immutable when DateTimeImmutable is passed in.
- The utilities are optimized to reduce overhead in loops (e.g., rendering lists, logs, or batch outputs).

---

## 🔗 Related Versions

- **Introduced in:** v1.0.0
- Forms foundational support for:
    - notifications
    - analytics dashboards
    - reporting engines
    - audit logs
    - scheduler timestamps

---

## 🔜 Next Phase

**Phase 6 — Validation & Filtering Tools**
Introduces strong input validation, array filtering, type detection, and data cleanup utilities.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/common

---