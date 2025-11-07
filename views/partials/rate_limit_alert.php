<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2025-11-07
 * Time: 19:45
 * Project: rate-limiter
 * IDE: PhpStorm
 * https://www.Maatify.dev
 */

declare(strict_types=1);

session_start();

if (!empty($_SESSION['rate_limit_error'])) {
    $error = $_SESSION['rate_limit_error'];
    $retryAfter = $error['retry_after'] ?? 5;
    $action = $error['action'] ?? 'generic';
    $lang = $_SESSION['lang'] ?? $_COOKIE['lang'] ?? 'en';

    $messages = [
        'login' => [
            'ar' => "🚫 تم تجاوز عدد محاولات تسجيل الدخول المسموح بها. حاول بعد {$retryAfter} ثانية.",
            'en' => "🚫 Too many login attempts. Try again after {$retryAfter} seconds.",
        ],
        'otp' => [
            'ar' => "📱 تم تجاوز عدد محاولات إرسال الكود المسموح بها. حاول بعد {$retryAfter} ثانية.",
            'en' => "📱 Too many OTP requests. Try again after {$retryAfter} seconds.",
        ],
        'api_call' => [
            'ar' => "⚙️ تم تجاوز عدد طلبات API المسموح بها. حاول بعد {$retryAfter} ثانية.",
            'en' => "⚙️ Too many API calls. Try again after {$retryAfter} seconds.",
        ],
        'generic' => [
            'ar' => "⛔ تم تجاوز الحد المسموح به. حاول بعد {$retryAfter} ثانية.",
            'en' => "⛔ Too many attempts. Try again after {$retryAfter} seconds.",
        ]
    ];

    // fallback للإنجليزية لو اللغة مش متوفرة
    $message = $messages[$action][$lang]
               ?? $messages[$action]['en']
                  ?? $messages['generic']['en'];
    ?>

    <div class="alert alert-danger alert-dismissible fade show text-center mt-3" role="alert" id="rate-limit-alert">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        setTimeout(() => {
            const alertBox = document.getElementById('rate-limit-alert');
            if (alertBox) {
                const bsAlert = new bootstrap.Alert(alertBox);
                bsAlert.close();
            }
        }, 5000);
    </script>

    <?php unset($_SESSION['rate_limit_error']); ?>
<?php } ?>