# 🧩 Phase 3.1 – Enum Contracts Refactor

**Goal:**  
Enhance the reusability and extensibility of Maatify Rate Limiter  
by replacing hard-coded enums with **interface-based contracts**.

---

## 🎯 Objective

Make the library framework-agnostic and open for external integrations by  
introducing `RateLimitActionInterface` and `PlatformInterface`,  
allowing external projects to define their own enums or classes.

---

## ✅ Tasks Completed

- Added **`RateLimitActionInterface`** and **`PlatformInterface`** contracts
- Updated **`RateLimitActionEnum`** and **`PlatformEnum`** to implement them
- Updated **`RateLimiterInterface`** to depend on these contracts instead of fixed enums
- Updated **Redis**, **Mongo**, and **MySQL** drivers accordingly
- Added unit test verifying enum–contract compatibility

---

## 📂 Files Created / Updated

````

src/Contracts/
├── RateLimitActionInterface.php
└── PlatformInterface.php

src/Enums/
├── RateLimitActionEnum.php
└── PlatformEnum.php

src/Contracts/
└── RateLimiterInterface.php (updated)

````

---

## 🧩 Example

```php
use Maatify\RateLimiter\Contracts\RateLimitActionInterface;
use Maatify\RateLimiter\Contracts\PlatformInterface;

enum CustomAction: string implements RateLimitActionInterface
{
    case ORDER_SUBMIT = 'order_submit';
    public function value(): string { return $this->value; }
}

enum CustomPlatform: string implements PlatformInterface
{
    case MOBILE_APP = 'mobile_app';
    public function value(): string { return $this->value; }
}
````

Then you can use them:

```php
$status = $limiter->attempt('user-501', CustomAction::ORDER_SUBMIT, CustomPlatform::MOBILE_APP);
```

---

## 🧠 Advantages

| Feature               | Before               | After                   |
| --------------------- | -------------------- | ----------------------- |
| Enum Usage            | Fixed inside library | Customizable externally |
| Reusability           | ❌ Limited            | ✅ Unlimited             |
| Type Safety           | ✅ Yes                | ✅ Yes                   |
| Open/Closed Principle | ❌ Broken             | ✅ Fully applied         |

---

## 📊 Result Summary

| Item                 | Status                      |
| -------------------- | --------------------------- |
| Contracts Introduced | ✅                           |
| Drivers Updated      | ✅                           |
| Tests Passed         | ✅                           |
| Architecture         | Now reusable across domains |

---

## 🧩 Version

```
1.0.0-alpha-phase3.1
```

---

## 📜 Notes

This phase turned Maatify Rate Limiter into a truly **domain-agnostic** library.
External systems can now define their own Enums that implement
`RateLimitActionInterface` and `PlatformInterface` without editing core code.
