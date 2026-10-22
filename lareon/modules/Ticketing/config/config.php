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


    /*
    |--------------------------------------------------------------------------
    | AI / n8n Integration
    |--------------------------------------------------------------------------
    | This section is completely independent of the core ticketing logic.
    | When enabled=false, the module behaves exactly like a standard ticketing
    | system. When enabled, every newly created ticket is sent externally via
    | webhook_url (e.g., to an n8n workflow or another AI service) before any
    | administrator has viewed it.
    |
    | The AI response is returned to the system through API routes
    | (routes/api.php) using a secure token.
    */
    'ai' => [
        // Enable or disable the entire AI functionality
        'enabled' => (bool) env('TICKETING_AI_ENABLED', false),

        // Destination webhook URL (e.g., an incoming webhook for an n8n workflow)
        'webhook_url' => env('TICKETING_AI_WEBHOOK_URL'),

        // Webhook request timeout in seconds
        'timeout' => (int) env('TICKETING_AI_TIMEOUT', 10),

        // Number of retry attempts if delivery fails (managed by the queue)
        'retry_times' => (int) env('TICKETING_AI_RETRY_TIMES', 3),

        // This secret is used both to sign outgoing requests with HMAC
        // and to validate incoming callback requests.
        'shared_secret' => env('TICKETING_AI_SHARED_SECRET'),

        // Dedicated queue for AI requests, separate from other project queues
        'queue' => env('TICKETING_AI_QUEUE', 'ticketing-ai'),

        // If the AI response confidence is below this threshold, the ticket
        // is routed to the manual admin review queue. This threshold is provided
        // to the external workflow as a reference; the final decision is made
        // by the external service.
        'confidence_threshold' => (float) env('TICKETING_AI_CONFIDENCE_THRESHOLD', 0.6),

        // Virtual sender name used when storing AI-generated messages
        'sender_label' => env('TICKETING_AI_SENDER_LABEL', 'AI Assistant'),
    ],


];
