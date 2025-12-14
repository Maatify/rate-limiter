# 🧱 Phase 4.2 — Adapter Logger Abstraction via DI

## 🎯 Goal
Refactor the adapter logging mechanism to replace the static `AdapterFailoverLog` usage with a **Dependency Injection (DI)**–based architecture.
Introduce a unified logging interface that can later integrate with `maatify/psr-logger` (Phase 7).

This allows flexible logging strategies — such as file-based, PSR-based, or external log aggregation — without touching existing adapter logic.

---

## ✅ Implemented Tasks

- [x] Created `AdapterLoggerInterface` defining a standard `record()` method.
- [x] Implemented `FileAdapterLogger` with dynamic `.env`-based path support.
- [x] Updated `DiagnosticService` to accept an injected logger via constructor.
- [x] Preserved backward compatibility with `AdapterFailoverLog::record()`.
- [x] Ensured automatic directory creation for log storage.
- [x] Added environment variable `ADAPTER_LOG_PATH` for customizable log location.
- [x] Documented architecture and examples in this phase file.

---

## ⚙️ Files Created

```

src/Diagnostics/Contracts/AdapterLoggerInterface.php
src/Diagnostics/Logger/FileAdapterLogger.php
docs/phases/README.phase4.2.md

````

---

## 🧩 Code Highlights

### AdapterLoggerInterface
```php
<?php
declare(strict_types=1);

namespace Maatify\DataAdapters\Diagnostics\Contracts;

interface AdapterLoggerInterface
{
    public function record(string $adapter, string $message): void;
}
````

---

### FileAdapterLogger

```php
<?php
declare(strict_types=1);

namespace Maatify\DataAdapters\Diagnostics\Logger;

use Maatify\DataAdapters\Diagnostics\Contracts\AdapterLoggerInterface;

final class FileAdapterLogger implements AdapterLoggerInterface
{
    private string $file;

    public function __construct(?string $path = null)
    {
        $logPath = $path
            ?? ($_ENV['ADAPTER_LOG_PATH'] ?? getenv('ADAPTER_LOG_PATH') ?: __DIR__ . '/../../../storage');
        $this->file = rtrim($logPath, '/') . '/failover.log';
        @mkdir(dirname($this->file), 0777, true);
    }

    public function record(string $adapter, string $message): void
    {
        $line = sprintf("[%s] [%s] %s%s",
            date('Y-m-d H:i:s'),
            strtoupper($adapter),
            $message,
            PHP_EOL
        );
        @file_put_contents($this->file, $line, FILE_APPEND);
    }
}
```

---

### DiagnosticService (excerpt)

```php
final class DiagnosticService
{
    public function __construct(
        private readonly EnvironmentConfig $config,
        private readonly DatabaseResolver  $resolver,
        private readonly AdapterLoggerInterface $logger = new FileAdapterLogger()
    ) {}

    public function collect(): array
    {
        $data = [];

        foreach ($this->adapters as $type => $adapter) {
            $status = false;
            $error  = null;

            try {
                $adapter->connect();
                $status = $adapter->healthCheck();
            } catch (\Throwable $e) {
                $error = $e->getMessage();
                $this->logger->record($type, $error);
            } finally {
                $adapter->disconnect();
            }

            $data[] = [
                'adapter'   => $type,
                'connected' => $status,
                'error'     => $error,
                'timestamp' => date('Y-m-d H:i:s'),
            ];
        }

        return $data;
    }
}
```

---

## 🧠 Usage Example

```php
use Maatify\DataAdapters\Diagnostics\Logger\FileAdapterLogger;
use Maatify\DataAdapters\Diagnostics\DiagnosticService;
use Maatify\DataAdapters\Core\DatabaseResolver;
use Maatify\DataAdapters\Core\EnvironmentConfig;

$config   = new EnvironmentConfig(__DIR__ . '/../');
$resolver = new DatabaseResolver($config);
$logger   = new FileAdapterLogger($_ENV['ADAPTER_LOG_PATH'] ?? null);

$diagnostic = new DiagnosticService($config, $resolver, $logger);
echo $diagnostic->toJson();
```

---

## 🧪 Testing & Verification

* Ran PHPUnit suite to verify logger injection does not break diagnostics flow.
* Simulated adapter connection failures to confirm log writes occur correctly.
* Validated dynamic path creation under both default and `.env`-configured paths.
* Confirmed compatibility with existing `AdapterFailoverLog` static calls.

---

## 📦 Result

* Dependency-injected logger successfully replaces static design.
* Phase 4.2 completed and ready for integration with PSR logger upgrade (Phase 7).

---

## ✅ Completed Phases

| Phase | Title                                 | Status      |
|:-----:|:--------------------------------------|:------------|
|   1   | Environment Setup                     | ✅ Completed |
|   2   | Core Interfaces & Base Structure      | ✅ Completed |
|   3   | Adapter Implementations               | ✅ Completed |
|  3.5  | Adapter Smoke Tests Extension         | ✅ Completed |
|   4   | Health & Diagnostics Layer            | ✅ Completed |
|  4.1  | Hybrid AdapterFailoverLog Enhancement | ✅ Completed |
|  4.2  | Adapter Logger Abstraction via DI     | ✅ Completed |

---

**© 2025 Maatify.dev**
Engineered by **Mohamed Abdulalim ([@megyptm](https://github.com/megyptm))** — https://www.maatify.dev

📘 Full documentation & source code:
https://github.com/Maatify/data-adapters

---
