CREATE TABLE `ip_rate_limits` (
                                  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,

    -- 🧠 المفتاح الفريد الذي يحدد هوية القيد
    -- أمثلة: "rate:ip:192.168.1.10:login:web" أو "rate:ip:192.168.1.10:global"
                                  `rate_key` VARCHAR(255) NOT NULL UNIQUE,

    -- 📦 عنوان الـ IP المستخدم في العملية
                                  `ip` VARCHAR(45) NOT NULL,

    -- 🔧 نوع العملية (login, otp_request, api_call...)
                                  `action` VARCHAR(64) DEFAULT NULL,

    -- 💻 المنصة (web, api, mobile)
                                  `platform` VARCHAR(32) DEFAULT NULL,

    -- 📊 إجمالي عدد المحاولات خلال نافذة الزمن الحالية
                                  `attempts` INT UNSIGNED NOT NULL DEFAULT 0,

    -- 🕒 وقت انتهاء صلاحية النافذة الزمنية (لتنظيف السجلات)
                                  `expires_at` DATETIME DEFAULT NULL,

    -- ⏳ وقت الحظر الحالي (في حالة تجاوز الحد)
                                  `blocked_until` DATETIME DEFAULT NULL,

    -- 🔁 عدد ثواني التأخير الحالية نتيجة الـ exponential backoff
                                  `backoff_seconds` INT UNSIGNED DEFAULT NULL,

    -- 📅 آخر وقت محاولة تم تسجيلها
                                  `last_attempt_at` DATETIME DEFAULT CURRENT_TIMESTAMP,

    -- 📅 وقت إنشاء السجل لأول مرة
                                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,

    -- 📅 وقت آخر تحديث للسجل
                                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

                                  PRIMARY KEY (`id`),
                                  UNIQUE KEY `uk_rate_key` (`rate_key`),
                                  KEY `idx_ip` (`ip`),
                                  KEY `idx_action` (`action`),
                                  KEY `idx_platform` (`platform`),
                                  KEY `idx_blocked_until` (`blocked_until`),
                                  KEY `idx_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
