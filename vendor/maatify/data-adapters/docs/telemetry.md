## 📈 Observability & Metrics

Starting from **Phase 7**, `maatify/data-adapters` introduces a full **telemetry and metrics layer**
for real-time monitoring and performance analytics across all adapters
(**Redis**, **MongoDB**, **MySQL**).

### ⚙️ Core Features
| Feature                        | Description                                                                                             |
|:-------------------------------|:--------------------------------------------------------------------------------------------------------|
| **AdapterMetricsCollector**    | Collects latency, success, and failover counters at runtime.                                            |
| **AdapterMetricsMiddleware**   | Wraps adapter operations and automatically measures execution time.                                     |
| **PrometheusMetricsFormatter** | Exports metrics in Prometheus-compatible text format for dashboards.                                    |
| **PSR-Logger Integration**     | Routes latency and failover logs through [`maatify/psr-logger`](https://github.com/Maatify/psr-logger). |
| **Grafana Ready**              | Metrics can be visualized directly in Grafana or maatify/admin-dashboard.                               |

### 🧩 Example Usage
```php
use Maatify\DataAdapters\Telemetry\{
    AdapterMetricsCollector,
    PrometheusMetricsFormatter
};

$collector = AdapterMetricsCollector::instance();

// Record metrics after any adapter operation
$collector->record('redis', 'set', latencyMs: 2.15, success: true);

// Render Prometheus output
$formatter = new PrometheusMetricsFormatter($collector);
header('Content-Type: text/plain');
echo $formatter->render();
```

**Prometheus Output Example**

```
# HELP adapter_latency_avg Average adapter latency (ms)
# TYPE adapter_latency_avg gauge
adapter_latency_avg{adapter="redis"} 2.15
adapter_success_total{adapter="redis"} 1
adapter_fail_total{adapter="redis"} 0
```

### 📘 .env Configuration

```env
METRICS_ENABLED=true
METRICS_EXPORT_FORMAT=prometheus
METRICS_SAMPLING_RATE=1.0
ADAPTER_LOG_PATH=/var/logs/maatify/adapters/
```

> Metrics are accessible via the `/metrics` endpoint or directly from maatify/admin-dashboard.
> For complete examples, see [`docs/examples/README.telemetry.md`](examples/README.telemetry.md).

---

🧱 This observability layer enables deep insight into adapter performance,
supports Prometheus and Grafana visualization,
and completes the reliability stack introduced in previous phases.

---

### 🔗 Integration with maatify/bootstrap

The **maatify/data-adapters** library is fully compatible with
[`maatify/bootstrap`](https://github.com/Maatify/bootstrap),
which handles automatic initialization and dependency injection
of all registered adapters via the shared `Container` instance.

---

#### ⚙️ Auto-Registration

Once `maatify/bootstrap` is installed,
the adapters are automatically registered during the bootstrap phase:

```php
use Maatify\Bootstrap\Bootstrap;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;

$bootstrap = new Bootstrap();
$container = $bootstrap->container();

// Resolve adapters anywhere in the system:
$config   = $container->get(EnvironmentConfig::class);
$resolver = $container->get(DatabaseResolver::class);
$redis    = $resolver->resolve('redis');
```

No manual setup required — `.env` variables are loaded globally by `maatify/bootstrap`,
and all diagnostics, failover, and recovery mechanisms are instantly available.

---

#### 🧩 Use within Other Maatify Modules

| Module                      | Integration                                                      |
|:----------------------------|:-----------------------------------------------------------------|
| **maatify/rate-limiter**    | Uses `RedisAdapter` (phpredis / predis) for request limiting     |
| **maatify/security-guard**  | Connects via `MySQLAdapter` for credential checks                |
| **maatify/mongo-activity**  | Uses `MongoAdapter` for structured event logging                 |
| **maatify/common-security** | Reads adapters through the shared container                      |
| **maatify/psr-logger**      | Injects `FileAdapterLogger` or PSR-based logger for adapter logs |

---

#### 🧠 Unified Configuration Flow

All connection parameters are managed from a single `.env` file shared across projects:

```env
# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=

# MongoDB
MONGO_HOST=127.0.0.1
MONGO_PORT=27017
MONGO_USER=
MONGO_PASS=
MONGO_DB=maatify_dev

# MySQL
MYSQL_HOST=127.0.0.1
MYSQL_PORT=3306
MYSQL_USER=root
MYSQL_PASS=
MYSQL_DB=maatify_dev
MYSQL_DRIVER=dbal

# Logs
ADAPTER_LOG_PATH=/var/logs/maatify/adapters/
```

Any library within the Maatify ecosystem can simply request a database connection
through the container — **no duplicate setup or credentials required.**

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
