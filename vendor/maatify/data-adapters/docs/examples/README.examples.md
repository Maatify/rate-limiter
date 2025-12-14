# 🧩 Maatify Data-Adapters — Usage Examples

**Version:** 1.0.0
**Maintainer:** [Maatify.dev](https://www.maatify.dev)
**Author:** Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))

---

## 1️⃣ Basic Usage — Connecting to Adapters

Demonstrates basic usage of the unified resolver to connect to Redis, MongoDB, and MySQL.

```php
use Maatify\DataAdapters\Core\EnvironmentConfig;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Enums\AdapterTypeEnum;

require_once __DIR__ . '/../../vendor/autoload.php';

$config   = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($config);

// Redis Example
$redis = $resolver->resolve("redis");
$redis->connect();
$redis->set('maatify:demo', 'ok');
echo $redis->get('maatify:demo'); // ok

// MySQL Example
$mysql = $resolver->resolve("mysql");
$pdo   = $mysql->getConnection();
$stmt  = $pdo->query("SELECT NOW()");
echo $stmt->fetchColumn();
```

✅ Adapters auto-detect configuration from `.env`
✅ No code duplication across multiple environments

---

## 2️⃣ Telemetry & Metrics (Prometheus)

Records adapter latency and success metrics, then exports them in Prometheus format.

```php
use Maatify\DataAdapters\Telemetry\{
    AdapterMetricsCollector,
    PrometheusMetricsFormatter
};

$collector = AdapterMetricsCollector::instance();

// Record latency and outcome
$collector->record('redis', 'set', latencyMs: 2.15, success: true);
$collector->record('mysql', 'query', latencyMs: 5.84, success: true);

// Render Prometheus output
$formatter = new PrometheusMetricsFormatter($collector);
header('Content-Type: text/plain');
echo $formatter->render();
```

**Example Output**

```
# HELP adapter_latency_avg Average adapter latency (ms)
# TYPE adapter_latency_avg gauge
adapter_latency_avg{adapter="redis"} 2.15
adapter_latency_avg{adapter="mysql"} 5.84
adapter_success_total{adapter="redis"} 1
```

---

## 3️⃣ Full Integration Example

A real-world integration showing **connection → fallback → recovery → telemetry → logging**.

```php
use Maatify\DataAdapters\Core\{
    EnvironmentConfig,
    DatabaseResolver
};
use Maatify\DataAdapters\Fallback\RecoveryWorker;
use Maatify\DataAdapters\Telemetry\{
    AdapterMetricsCollector,
    PrometheusMetricsFormatter
};
use Maatify\DataAdapters\Diagnostics\Logger\FileAdapterLogger;
use Maatify\DataAdapters\Core\Exceptions\ConnectionException;

$config   = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($config);
$logger   = new FileAdapterLogger($_ENV['ADAPTER_LOG_PATH'] ?? null);
$collector = AdapterMetricsCollector::instance();

try {
    $redis = $resolver->resolve('redis');
    $redis->connect();

    $start = microtime(true);
    $redis->set('maatify:integration', 'ok');
    $latency = (microtime(true) - $start) * 1000;
    $collector->record('redis', 'set', latencyMs: $latency, success: true);

} catch (ConnectionException $e) {
    $logger->record('redis', "Fallback triggered: {$e->getMessage()}");
    $collector->record('redis', 'set', latencyMs: 0, success: false);
}

// Recovery & metrics export
(new RecoveryWorker($redis))->run();

echo (new PrometheusMetricsFormatter($collector))->render();
```

✅ Demonstrates full data-adapter lifecycle in production.
✅ Metrics ready for Grafana / maatify/admin-dashboard.
✅ Logs saved to `ADAPTER_LOG_PATH`.

---

## 🧱 Conclusion

These examples cover the **entire operational flow** of
`maatify/data-adapters` — from initialization and fallback to telemetry and integration.

For detailed API documentation, refer to:
📘 [`docs/README.full.md`](../README.full.md)

---

**© 2025 Maatify.dev**
Built and maintained by **Mohamed Abdulalim (megyptm)**
> 🧩 *Unified Data Connectivity & Diagnostics Layer*

---
