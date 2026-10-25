<?php

return [
    'name' => 'Ticketing',

    'default_priority'=> \Lareon\Modules\Ticketing\App\Enums\PriorityType::Normal,

    'attachments' => [
        'enabled' => true,
        'disk' => env('TICKETING_ATTACHMENT_DISK', 'ticketing'),
        'max_size_kb' => (int) env('TICKETING_ATTACHMENT_MAX_KB', 5120),
        'max_files_per_message' => (int) env('TICKETING_ATTACHMENT_MAX_FILES', 5),
        'allowed_extensions' => [
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'pdf', 'doc', 'docx', 'xls', 'xlsx',
            'zip', 'rar', 'txt',
        ],
        'allowed_mimes' => [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip', 'application/x-rar-compressed',
            'text/plain',
        ],
    ],


    'ai' => [
        'enabled' => (bool) env('TICKETING_AI_ENABLED', false),
        'webhook_url' => env('TICKETING_AI_WEBHOOK_URL'),
        'timeout' => (int) env('TICKETING_AI_TIMEOUT', 10),
        'retry_times' => (int) env('TICKETING_AI_RETRY_TIMES', 3),
        'queue' => env('TICKETING_AI_QUEUE', 'ticketing-ai'),

        // draft: پاسخ AI فقط به صورت یادداشت داخلی برای ادمین ذخیره می‌شود (پیشنهاد برای ماه اول)
        // auto : پاسخ AI مستقیم برای کاربر نمایش داده می‌شود
        'mode' => env('TICKETING_AI_MODE', 'draft'),

        // کلید HMAC برای webhook خروجی و callback ورودی (فقط n8n و لاراول می‌دانند)
        'shared_secret' => env('TICKETING_AI_SHARED_SECRET'),
        // توکن جدا و فقط‌خواندنی برای APIهای دانش/زمینه (کمترین سطح دسترسی)
        'read_token' => env('TICKETING_AI_READ_TOKEN'),
        // IPها/CIDRهای مجاز (خالی = بدون محدودیت). مثال: 10.0.0.5,172.18.0.0/16
        'allowed_ips' => array_values(array_filter(array_map('trim', explode(',', (string) env('TICKETING_AI_ALLOWED_IPS', ''))))),
        // حداکثر اختلاف زمانی مجاز امضا (ثانیه) برای جلوگیری از replay
        'max_timestamp_skew' => 300,
        // حداکثر زمان انتظار برای callback پس از ارسال (ثانیه)
        'pending_ttl' => 3600,

        'confidence_threshold' => (float) env('TICKETING_AI_CONFIDENCE_THRESHOLD', 0.75),

        // کنترل هزینه
        'max_auto_replies' => 1,                    // AI حداکثر چند بار روی یک تیکت جواب بدهد
        'skip_priorities' => ['urgent'],            // تیکت‌های فوری مستقیم به انسان می‌روند
        'skip_when_attachments' => true,            // AI فایل‌ها را نمی‌بیند؛ مستقیم به انسان
        'max_requests_per_user_per_day' => 10,

        // هاست‌های مجاز برای لینک منبع در پاسخ AI (خالی = فقط هاست APP_URL)
        'allowed_source_hosts' => [],

        'sender_label' => env('TICKETING_AI_SENDER_LABEL', 'دستیار هوشمند'),
    ],

    /*
    |--------------------------------------------------------------------------
    | پایگاه دانش (منبع داده‌ی RAG)
    |--------------------------------------------------------------------------
    | هر منبع فقط «مانیفست» (id + نسخه) و «سند» تحویل می‌دهد تا n8n فقط موارد
    | تغییرکرده را دوباره ایندکس کند. متن تیکت‌ها قبل از خروج ماسک می‌شود.
    */
    'knowledge' => [
        'sources' => [
            // فقط تیکت‌های بسته‌شده‌ای که ادمین هنگام بستن «ذخیره در پایگاه دانش» را زده
            'ticket' => ['enabled' => true],

            // فایل‌های FAQ/مستندات: .md .txt .csv(question,answer) .json([{question,answer}])
            'file' => [
                'enabled' => false,
                'disk' => 'local',
                'path' => 'ticketing-knowledge',
            ],

            // صفحات سایت از هر مدل Eloquent (نمونه را فعال و تنظیم کنید)
            'models' => [
                // 'page' => [
                //     'model' => \App\Models\Page::class,
                //     'id' => 'id',
                //     'title' => 'title',
                //     'content' => 'body',
                //     'url' => '/pages/{slug}',
                //     'updated_at' => 'updated_at',
                //     'where' => ['is_published' => true],
                //     'type' => 'page',
                // ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | تنظیمات اعلان‌ها (اختیاری - در صورت وجود کانال‌های پیاده‌سازی‌شده)
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'enabled' => (bool) env('TICKETING_NOTIFICATIONS_ENABLED', true),
    ],

];
