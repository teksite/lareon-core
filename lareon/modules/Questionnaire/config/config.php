<?php

return [
    'name' => 'Questionnaire',

    'announcement' => resource_path('views/questionnaire/announcement'),
    'forms'    => resource_path('views/questionnaire/forms'),


    'upload' => [
        'disk'       => env('FORM_UPLOAD_DISK', 'local'),
        'directory'  => 'questionnaire/inboxes',
        'visibility' => 'private',
    ],
];
