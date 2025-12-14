
---

## 🧱 Architecture Overview

```
src/
├─ Core/
│   ├─ EnvironmentConfig.php
│   ├─ DatabaseResolver.php
│   ├─ BaseAdapter.php
│   └─ Exceptions/
│       └─ ConnectionException.php
├─ Adapters/
│   ├─ RedisAdapter.php
│   ├─ PredisAdapter.php
│   ├─ MongoAdapter.php
│   ├─ MySQLAdapter.php
│   └─ MySQLDbalAdapter.php
├─ Diagnostics/
│   ├─ DiagnosticService.php
│   └─ AdapterFailoverLog.php
├─ Enums/
│   └─ AdapterTypeEnum.php
└─ Telemetry/
    ├─ AdapterMetricsCollector.php
    ├─ PrometheusMetricsFormatter.php
    ├─ AdapterMetricsMiddleware.php
    └─ Logger/
       └─ AdapterLogContext.php

```

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
