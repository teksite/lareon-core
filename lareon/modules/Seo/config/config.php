<?php

return [
    'name' => 'Seo',

    'sitemap' => [
        'default_priority'         => 0.5,  // this value saved at database, so if you change it, you should change pre saved data in database manually
        'default_change_frequency' => 'yearly',  // this value saved at database, so if you change it, you should change pre saved data in database manually
    ],

    'schema' => [
        'WebPage'     => 'web-page',
        'Article'     => 'article-page',
        'VideoObject' => 'video-object-page',
        'Event'       => 'event-page',
        'FAQ'         => 'faq-page',
        'Person'      => 'person-page',
        'JobPosition' => 'job-position-page',

        'Blog'                => 'coming-soon',
        'Product'             => 'coming-soon',
        'SoftwareApplication' => 'coming-soon',
        'HowTo'               => 'coming-soon',
        'Recipe'              => 'coming-soon',

    ],
];
