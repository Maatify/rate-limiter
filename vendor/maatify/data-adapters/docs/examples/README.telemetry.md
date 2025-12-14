
## ![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

## ⚙️ Maatify Data-Adapters — Telemetry & Metrics Example

**Version:** 1.0.0-alpha
**Maintainer:** Mohamed Abdulalim (megyptm)
**Date:** 2025-11-12
**Status:** ✅ Verified & Tested
---

### 🧩 Overview

This example demonstrates how to enable **real-time observability** for all adapters (Redis, MongoDB, MySQL) using the Maatify Telemetry subsystem introduced in **Phase 7**.
It covers both **stand-alone testing** and **integration inside adapters** with full Prometheus compatibility.

---

## 📘 1. Installation

```bash
composer require maatify/data-adapters
```

Make sure you already have:

* `maatify/psr-logger` installed for structured logging
* PHP ≥ 8.1 with `ext-json`, `ext-mbstring`
* Optional monitoring tools (Prometheus / Grafana / maatify/admin-dashboard)

---

## ⚙️ 2. Environment Setup

Add these variables to your `.env` file (or system environment):

```env
METRICS_ENABLED=true
METRICS_EXPORT_FORMAT=prometheus
METRICS_SAMPLING_RATE=1.0
ADAPTER_LOG_PATH=/var/logs/maatify/adapters/
```

**Options explained**

| Variable                | Description                                   | Default                       |
|-------------------------|-----------------------------------------------|-------------------------------|
| `METRICS_ENABLED`       | Enables telemetry system globally             | `true`                        |
| `METRICS_EXPORT_FORMAT` | Output format (`prometheus` / `json`)         | `prometheus`                  |
| `METRICS_SAMPLING_RATE` | Fraction of calls to be recorded (1.0 = 100%) | `1.0`                         |
| `ADAPTER_LOG_PATH`      | File path for PSR log output                  | `/var/logs/maatify/adapters/` |

---

## 🧠 3. Quick Start Example

Create file: **`examples/telemetry_example.php`**

```php
<?php

use Maatify\DataAdapters\Telemetry\{
    AdapterMetricsCollector,
    AdapterMetricsMiddleware,
    PrometheusMetricsFormatter
};

require_once __DIR__ . '/../vendor/autoload.php';

// 🧩 Initialize
$collector  = AdapterMetricsCollector::instance();
$middleware = new AdapterMetricsMiddleware($collector);

// 1️⃣ Simulate adapter operations
try {
    $middleware->measure('redis', 'set', function () {
        usleep(2000); // simulate 2 ms latency
        return true;
    });

    $middleware->measure('mysql', 'query', function () {
        usleep(4000);
        throw new RuntimeException('Query timeout');
    });
} catch (Throwable $e) {
    echo "⚠️ Exception captured: {$e->getMessage()}\n";
}

// 2️⃣ Display collected metrics
echo "\n=== Raw Metrics ===\n";
print_r($collector->getAll());

// 3️⃣ Export in Prometheus format
$formatter = new PrometheusMetricsFormatter($collector);
echo "\n=== Prometheus Metrics ===\n";
echo $formatter->render();
```

**Run**

```bash
php examples/telemetry_example.php
```

---

## 📊 4. Example Output

```
⚠️ Exception captured: Query timeout

=== Raw Metrics ===
Array
(
    [redis] => Array
        (
            [set] => Array
                (
                    [avg_latency_ms] => 2.05
                    [success] => 1
                    [fail] => 0
                )
        )

    [mysql] => Array
        (
            [query] => Array
                (
                    [avg_latency_ms] => 4.08
                    [success] => 0
                    [fail] => 1
                )
        )
)

=== Prometheus Metrics ===
# HELP adapter_latency_avg Average adapter latency (ms)
# TYPE adapter_latency_avg gauge
adapter_latency_avg{adapter="redis",operation="set"} 2.050
adapter_success_total{adapter="redis",operation="set"} 1
adapter_fail_total{adapter="redis",operation="set"} 0
adapter_latency_avg{adapter="mysql",operation="query"} 4.080
adapter_success_total{adapter="mysql",operation="query"} 0
adapter_fail_total{adapter="mysql",operation="query"} 1
```

---

## 🧩 5. Integration Inside an Adapter

If you want automatic metrics collection inside a real adapter:

```php
use Maatify\DataAdapters\Telemetry\AdapterMetricsMiddleware;

final class RedisAdapter
{
    private AdapterMetricsMiddleware $metrics;

    public function __construct()
    {
        $this->metrics = new AdapterMetricsMiddleware(
            \Maatify\DataAdapters\Telemetry\AdapterMetricsCollector::instance()
        );
    }

    public function set(string $key, string $value): bool
    {
        return $this->metrics->measure('redis', 'set', function () use ($key, $value) {
            // your actual Redis logic
            return $this->redis->set($key, $value);
        });
    }
}
```

This pattern gives every adapter **built-in latency tracking** automatically.

---

## 🪄 6. Optional Output Formats

| Format           | Class                              | Description                                  |
|------------------|------------------------------------|----------------------------------------------|
| `prometheus`     | `PrometheusMetricsFormatter`       | Text output, scraped by Prometheus / Grafana |
| `json`           | `JsonMetricsFormatter` *(planned)* | Human-readable JSON for admin dashboard      |
| `maatify-logger` | `AdapterLogContext`                | Structured PSR logs for maatify/psr-logger   |

---

## ⚙️ 7. Advanced Options

| Feature              | Description                                                                         |
|----------------------|-------------------------------------------------------------------------------------|
| **Sampling**         | You can record only a percentage of calls by setting `METRICS_SAMPLING_RATE` < 1.0. |
| **Reset metrics**    | Call `$collector->reset()` to clear stats between requests.                         |
| **Group by adapter** | `$collector->getAll()` returns nested array per adapter → operation.                |
| **Live endpoint**    | Add `/metrics` route in your Slim / Bootstrap app to output Prometheus data:        |

```php
$app->get('/metrics', function () {
    $formatter = new PrometheusMetricsFormatter(
        \Maatify\DataAdapters\Telemetry\AdapterMetricsCollector::instance()
    );
    header('Content-Type: text/plain');
    echo $formatter->render();
});
```

---

## 🧱 8. Design Summary

| Component                    | Responsibility                           |
|------------------------------|------------------------------------------|
| `AdapterMetricsCollector`    | Aggregates all runtime data              |
| `AdapterMetricsMiddleware`   | Measures execution time automatically    |
| `PrometheusMetricsFormatter` | Converts metrics to Prometheus format    |
| `AdapterLogContext`          | Provides structured PSR-log context      |
| `.env` Config                | Controls telemetry enablement & sampling |

---

## 📈 9. Ecosystem Integration

Telemetry module feeds directly into:

* **maatify/rate-limiter** → monitor blocked / allowed calls per adapter
* **maatify/security-guard** → measure login throttling latency
* **maatify/mongo-activity** → track logging write times
* **maatify/admin-dashboard** → display graphs from `/metrics` endpoint

---

## 🧾 10. License & Credits

**License:** MIT
**Engineered by:** [Mohamed Abdulalim (megyptm)](https://www.maatify.dev)
**Repository:** [maatify/data-adapters](https://github.com/Maatify/data-adapters)

© 2025 Maatify.dev — Built for reliability, transparency & performance.

---
