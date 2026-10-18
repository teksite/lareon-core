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
];
