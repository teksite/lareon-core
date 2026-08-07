<?php

return [
    'name' => 'Seo',

    'sitemap' => [
        'default_priority'         => 0.5,  // this value saved at database, so if you change it, you should change pre saved data in database manually
        'default_change_frequency' => 'yearly',  // this value saved at database, so if you change it, you should change pre saved data in database manually
    ],

    'models' => [
        'page' => \Lareon\Modules\Page\App\Models\Page::class,
        'user' => \Lareon\Modules\User\App\Models\User::class,
    ],
];
