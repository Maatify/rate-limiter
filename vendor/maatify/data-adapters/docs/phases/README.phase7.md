## ![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

## ⚙️ Maatify Data-Adapters

**Phase ID:** 7
**Title:** Observability & Metrics
**Version:** 1.0.0-alpha
**Maintainer:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))
**Date:** 2025-11-12
**Status:** ✅ Completed (Tests Passed & Integration Verified)

---

### 🧩 Objective

To introduce **structured observability and telemetry** across all adapters (Redis, MongoDB, MySQL).
This phase equips the system with unified performance metrics, PSR-logger integration,
and Prometheus-ready outputs for seamless monitoring and analytics.

---

### 🧱 Core Components

| Component                    | Responsibility                                                                           |
|------------------------------|------------------------------------------------------------------------------------------|
| `AdapterMetricsCollector`    | Collects runtime counters such as latency, success rate, and failover count per adapter. |
| `PrometheusMetricsFormatter` | Converts collected metrics into Prometheus-compliant text output.                        |
| `AdapterMetricsMiddleware`   | Wraps adapter operations to measure execution time automatically.                        |
| `AdapterLogContext`          | Defines structured logging context for maatify/psr-logger.                               |
| `DatabaseResolver`           | Extended to provide metrics injection hooks for all adapters.                            |

---

### 🧪 Testing Summary

| Test Suite                                     | Purpose                                                     | Status   |
|------------------------------------------------|-------------------------------------------------------------|----------|
| **Telemetry → AdapterMetricsCollectorTest**    | Validates latency, counters, and failover increments.       | ✅ Passed |
| **Telemetry → PrometheusMetricsFormatterTest** | Ensures proper Prometheus formatting and tag mapping.       | ✅ Passed |
| **Integration → MetricsEndpointTest**          | Verifies `/metrics` endpoint output is Prometheus-parsable. | ✅ Passed |

**PHPUnit Coverage:** ≈ 90 %
**Assertions:** All passing
**Performance Impact:** Negligible (< 0.3 ms overhead per operation)

---

### 🔍 Design Highlights

* **Non-Blocking Profiling:** Uses microtime deltas for precise latency without I/O delay.
* **PSR-Logger Harmony:** Latency and failover events are routed through maatify/psr-logger.
* **Unified Metric Tags:** Every adapter operation tagged by type (`redis`, `mongo`, `mysql`).
* **Prometheus Readiness:** Outputs fully compatible `text/plain` metrics stream.
* **Future Extensibility:** Designed for Grafana dashboards and maatify/monitoring integration.

---

### 📊 Metrics Examples

**Prometheus Output Sample**

```
# HELP adapter_latency_avg Average adapter latency in milliseconds
# TYPE adapter_latency_avg gauge
adapter_latency_avg{adapter="redis"} 3.24
adapter_latency_avg{adapter="mysql"} 5.87
adapter_failover_count{adapter="redis"} 0
```

**Usage Example**

```php
use Maatify\DataAdapters\Telemetry\AdapterMetricsCollector;
use Maatify\DataAdapters\Telemetry\PrometheusMetricsFormatter;

$collector = AdapterMetricsCollector::instance();
$collector->record('redis', 'set', latencyMs: 3.24, success: true);

$formatter = new PrometheusMetricsFormatter($collector);
header('Content-Type: text/plain');
echo $formatter->render();
```

---

### 📦 Artifacts Generated

| File                                                 | Description                        |
|------------------------------------------------------|------------------------------------|
| `src/Telemetry/AdapterMetricsCollector.php`          | Core metrics collection logic      |
| `src/Telemetry/PrometheusMetricsFormatter.php`       | Prometheus exporter                |
| `src/Telemetry/AdapterMetricsMiddleware.php`         | Automatic latency measurement      |
| `src/Telemetry/Logger/AdapterLogContext.php`         | Structured log context definition  |
| `tests/Telemetry/AdapterMetricsCollectorTest.php`    | Unit tests for metrics collector   |
| `tests/Telemetry/PrometheusMetricsFormatterTest.php` | Unit tests for Prometheus exporter |

---

## 🗂 File Structure

```
src/
 ├─ Core/
 │   └─ DatabaseResolver.php
 ├─ Telemetry/
 │   ├─ AdapterMetricsCollector.php
 │   ├─ PrometheusMetricsFormatter.php
 │   ├─ AdapterMetricsMiddleware.php
 │   └─ Logger/
 │       └─ AdapterLogContext.php
 └─ Diagnostics/
     └─ DiagnosticService.php
```

---

## 📘 .env Example

```env
ADAPTER_LOG_PATH=/var/logs/maatify/adapters/
METRICS_ENABLED=true
METRICS_EXPORT_FORMAT=prometheus
METRICS_SAMPLING_RATE=1.0
```

> *Metrics can be polled through the `/metrics` endpoint or integrated directly into maatify/admin-dashboard.*

---
### 🧩 Example Usage Preview

For practical examples of telemetry setup and metrics export in action,
see full examples in:

➡️ [`docs/examples/README.telemetry.md`](../examples/README.telemetry.md)

---

### 📜 Next Step → **Phase 8 — Documentation & Release**

In the next phase:

* Consolidate all phase documentation into `/docs/README.full.md`.
* Generate `CHANGELOG.md`, `VERSION`, and Packagist release notes.
* Finalize integration examples and cross-module usage with maatify/rate-limiter and maatify/security-guard.
* Target release tag → **v1.0.0-stable**.

---

🧱 **Maatify Ecosystem Integration:**
This phase establishes the observability layer within `maatify/data-adapters`,
bridging real-time telemetry for `maatify/rate-limiter`, `maatify/security-guard`, and `maatify/mongo-activity`.

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
