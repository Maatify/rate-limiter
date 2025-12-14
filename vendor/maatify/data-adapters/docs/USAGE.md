# 📦 **maatify/data-adapters**

![Maatify.dev](https://www.maatify.dev/assets/img/img/maatify_logo_white.svg)

---

# 📘 **Maatify Data-Adapters — Full Usage Examples (Phase 13+)**

**Version:** 1.1.0
**Maintainer:** Maatify.dev
**Updated:** Phase 13 — Registry + DSN + Builders + Unified Resolver

---

# #️⃣ Contents

1. 🔹 Basic Resolver Usage
2. 🔹 MySQL Examples (PDO / DBAL)
3. 🔹 Redis Examples (phpredis / Predis / DSN / Legacy)
4. 🔹 Mongo Examples (Normal + SRV)
5. 🔹 Registry Example
6. 🔹 Dynamic Profile Example
7. 🔹 **Advanced Examples**

    * Failover
    * Cluster
    * Sharding
8. 🔹 **Integration Examples**

    * Slim Framework
    * Laravel
    * CLI / Console
9. 🔹 **JSON/YAML Config Examples**

---

# 1️⃣ **Resolver — Basic Usage**

```php
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;

$env = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($env);
```

---

# 2️⃣ **MySQL Examples**

## ✔ MySQL (PDO) — DSN

```env
MYSQL_MAIN_DSN="mysql:host=127.0.0.1;dbname=app;port=3306"
```

```php
$db = $resolver->resolve('mysql.main', autoConnect: true);

$now = $db->getConnection()
          ->query("SELECT NOW()")
          ->fetchColumn();

echo $now;
```

---

## ✔ MySQL (DBAL) + Driver Override

```env
MYSQL_REPORTS_DRIVER=dbal
MYSQL_REPORTS_DSN=mysql://user:pass@10.0.0.44:3307/reports
```

```php
$db = $resolver->resolve('mysql.reports', true);

$rows = $db->getConnection()->fetchAllAssociative("SELECT * FROM reports");
print_r($rows);
```

---

# 3️⃣ **Redis Examples**

## ✔ Redis — DSN (phpredis)

```env
REDIS_CACHE_DSN=redis://:pass123@127.0.0.1:6379/2
```

```php
$redis = $resolver->resolve('redis.cache', true);

$redis->getConnection()->set('key', 'maatify');
echo $redis->getConnection()->get('key');
```

---

## ✔ Redis — Legacy Mode

```env
REDIS_MAIN_HOST=127.0.0.1
REDIS_MAIN_PORT=6379
REDIS_MAIN_DB=0
```

```php
$redis = $resolver->resolve('redis.main', true);
$redis->getConnection()->set('legacy', 'ok');
```

---

## ✔ Redis — Predis Fallback

If phpredis extension is **not installed**, resolver automatically uses `PredisAdapter`.

```php
$cache = $resolver->resolve('redis.cache', true);
echo $cache->getConnection()->ping();
```

---

# 4️⃣ **MongoDB Examples**

## ✔ Normal DSN

```env
MONGO_LOGS_DSN=mongodb://127.0.0.1:27017/logs
```

```php
$mongo = $resolver->resolve('mongo.logs', true);

$db = $mongo->getConnection()->selectDatabase('logs');
$db->command(['ping' => 1]);
```

---

## ✔ SRV Cluster

```env
MONGO_ANALYTICS_DSN=mongodb+srv://cluster0.xyx.mongodb.net/analytics
```

```php
$mongo = $resolver->resolve('mongo.analytics', true);
$mongo->getConnection()->selectDatabase('analytics');
```

---

# 5️⃣ **Registry Override Example**

## registry.json

```json
{
  "databases": {
    "mysql": {
      "main": {
        "host": "192.168.10.55",
        "port": 3309,
        "database": "override_db"
      }
    }
  }
}
```

```php
$_ENV['DB_REGISTRY_PATH'] = __DIR__ . '/registry.json';

$env = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($env);

$db = $resolver->resolve('mysql.main');

$config = $db->debugConfig();
echo $config->host; // 192.168.10.55
```

---

# 6️⃣ **Dynamic Unknown Profile**

```php
$db = $resolver->resolve('mysql.billing'); // works without env
print_r($db->debugConfig());
```

---

# 7️⃣ **Advanced Examples**

## ⚡ **A) Failover Policy (Manual)**

```php
try {
    $db = $resolver->resolve('mysql.main', true);
} catch (Throwable $e) {
    // fallback to replica
    $db = $resolver->resolve('mysql.replica', true);
}
```

---

## ⚡ **B) Redis Cluster Mode**

```php
$_ENV['REDIS_CLUSTER_DSN'] = "redis://127.0.0.1:7001,redis://127.0.0.1:7002";

$nodes = explode(',', $_ENV['REDIS_CLUSTER_DSN']);

foreach ($nodes as $node) {
    $redis = (new Predis\Client($node));
    if ($redis->ping()) {
        return $redis; // connected to working node
    }
}
```

---

## ⚡ **C) MongoDB Sharding Selection**

```php
// analytics shard
$analytics = $resolver->resolve('mongo.analytics', true);

// real-time shard
$rt = $resolver->resolve('mongo.realtime', true);

// write into right shard:
$rt->getConnection()->selectDatabase('realtime')->insertOne([...]);
```

---

# 8️⃣ **Integration Examples**

---

## ✔ Slim Framework

```php
$app->get('/users', function ($req, $res) {
    $db = $this->resolver->resolve('mysql.main', true);

    $users = $db->getConnection()
                ->query("SELECT * FROM users")
                ->fetchAll();

    return $res->withJson($users);
});
```

Container binding:

```php
$container['resolver'] = function () {
    return new DatabaseResolver(new EnvironmentConfig(__DIR__));
};
```

---

## ✔ Laravel Integration (Service Provider)

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Maatify\DataAdapters\Core\{DatabaseResolver, EnvironmentConfig};

class DataAdaptersServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DatabaseResolver::class, function () {
            return new DatabaseResolver(new EnvironmentConfig(base_path()));
        });
    }
}
```

Usage:

```php
$resolver = resolve(DatabaseResolver::class);
$db = $resolver->resolve('mysql.main', true);
```

---

## ✔ CLI / Console Script

```php
#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

$env = new EnvironmentConfig(__DIR__);
$resolver = new DatabaseResolver($env);

$db = $resolver->resolve('mysql.main', true);

$count = $db->getConnection()
            ->query("SELECT COUNT(*) FROM users")
            ->fetchColumn();

echo "Users count: $count\n";
```

---

# 9️⃣ **JSON / YAML Config Examples**

## ✔ JSON (registry.json)

```json
{
  "databases": {
    "mysql": {
      "main": {
        "host": "10.0.0.1",
        "port": 3306,
        "database": "app",
        "user": "root",
        "pass": ""
      }
    },
    "redis": {
      "cache": {
        "host": "127.0.0.1",
        "port": 6379,
        "db": 2
      }
    }
  }
}
```

---

## ✔ YAML (registry.yaml) — *if YAML loader added later*

```yaml
databases:
  mysql:
    reporting:
      host: 192.168.1.10
      port: 3310
      database: reporting
  redis:
    sessions:
      host: 10.0.0.22
      port: 6379
      db: 3
```

---

# 🔭 **Telemetry & Metrics — Adapter Observability Examples (Phase 7 + Phase 13)**

The Maatify Data-Adapters library includes an internal **Telemetry Engine** that provides:

* Real-time latency tracking
* Success / failure counters
* Per-adapter & per-operation statistics
* Prometheus-ready exports
* Optional PSR-logger integration

This section shows how to enable and use metrics with Redis / MySQL / Mongo — fully compatible with the registry + builder system from Phase 13.

---

## 1️⃣ Enable Telemetry via `.env`

```env
METRICS_ENABLED=true
METRICS_EXPORT_FORMAT=prometheus
METRICS_SAMPLING_RATE=1.0
ADAPTER_LOG_PATH=/var/logs/maatify/adapters/
```

---

## 2️⃣ Basic Telemetry Example

```php
use Maatify\DataAdapters\Telemetry\{
    AdapterMetricsCollector,
    AdapterMetricsMiddleware,
    PrometheusMetricsFormatter
};

$collector  = AdapterMetricsCollector::instance();
$middleware = new AdapterMetricsMiddleware($collector);

// Measure Redis SET
$middleware->measure('redis', 'set', fn() => usleep(2000));

// Measure MySQL query (simulate failure)
try {
    $middleware->measure('mysql', 'query', fn() => throw new RuntimeException('Query timeout'));
} catch (Throwable $e) {}
```

---

## 3️⃣ Display Raw Metrics

```php
print_r(AdapterMetricsCollector::instance()->getAll());
```

---

## 4️⃣ Prometheus Export

```php
$formatter = new PrometheusMetricsFormatter(
    AdapterMetricsCollector::instance()
);

header('Content-Type: text/plain');
echo $formatter->render();
```

---

## 5️⃣ Auto-Telemetry Inside Adapters

```php
use Maatify\DataAdapters\Telemetry\AdapterMetricsMiddleware;

final class RedisAdapter
{
    private AdapterMetricsMiddleware $metrics;

    public function __construct()
    {
        $this->metrics = new AdapterMetricsMiddleware(
            AdapterMetricsCollector::instance()
        );
    }

    public function set(string $key, string $value): bool
    {
        return $this->metrics->measure('redis', 'set', function () use ($key, $value) {
            return $this->connection->set($key, $value);
        });
    }
}
```

---

## 6️⃣ Slim Framework `/metrics` Endpoint

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

## 7️⃣ Supported Formats

| Format           | Class                        | Usage                                      |
|------------------|------------------------------|--------------------------------------------|
| `prometheus`     | `PrometheusMetricsFormatter` | Grafana / Prometheus Monitoring            |
| `json`           | *(planned)*                  | Admin dashboards                           |
| `maatify-logger` | `AdapterLogContext`          | PSR structured logs via maatify/psr-logger |

---

## 8️⃣ Integration With Maatify Ecosystem

Telemetry feeds into:

* **maatify/rate-limiter** → adapter throttling metrics
* **maatify/security-guard** → login performance monitoring
* **maatify/mongo-activity** → insert/update latency tracking
* **maatify/admin-dashboard** → graphs using `/metrics` endpoint

---

## 9️⃣ Demo Output

```
adapter_latency_avg{adapter="redis",operation="set"} 2.050
adapter_success_total{adapter="redis",operation="set"} 1
adapter_fail_total{adapter="redis",operation="set"} 0
adapter_latency_avg{adapter="mysql",operation="query"} 4.080
adapter_success_total{adapter="mysql",operation="query"} 0
adapter_fail_total{adapter="mysql",operation="query"} 1
```

---

# 🏁 **End of Examples Document**

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---

## 🧱 Authors & Credits

This library is part of the **Maatify.dev Core Ecosystem**, designed and maintained under the technical supervision of:

**👤 Mohamed Abdulalim** — *Backend Lead & Technical Architect*
Lead architect of the **Maatify Backend Infrastructure**, responsible for the overall architecture, core library design,
and technical standardization across all backend modules within the Maatify ecosystem.
🔗 [www.Maatify.dev](https://www.maatify.dev) | ✉️ [mohamed@maatify.dev](mailto:mohamed@maatify.dev)

**🤝 Contributors:**
The **Maatify.dev Engineering Team** and open-source collaborators who continuously help refine, test, and extend
the capabilities of this library across multiple Maatify projects.

> 🧩 This project represents a unified engineering effort led by Mohamed Abdulalim, ensuring every Maatify backend component
> shares a consistent, secure, and maintainable foundation.

---

<p align="center">
  <sub><span style="color:#777">Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</span></sub>
</p>
