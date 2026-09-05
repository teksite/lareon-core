<?php

use Lareon\Modules\Seo\App\Enums\SitemapType;

return [

    'name' => 'Seo',

    'sitemap' => [

        /*
         * Default values stored in database.
         */
        'default_priority' => 0.5,

        'default_change_frequency' => 'yearly',

        /*
         * index | single
         */
        'type' => SitemapType::Index->value,

        /*
         * Google maximum is 50,000 URLs.
         *
         * We intentionally use a lower value because
         * image/video metadata can make XML files very large.
         */
        'max_urls_per_file' => 40000,

        /*
         * Output directory.
         */
        'directory' => public_path('sitemaps'),

        /*
         * Main sitemap file.
         */
        'filename' => 'sitemap.xml',

        /*
         * Base URL.
         *
         * Example:
         * https://example.com
         */
        'base_url' => env('APP_URL'),

    ],

    'schema' => [

        'WebPage'     => 'web-page',
        'Article'     => 'article-page',
        'VideoObject' => 'video-object-page',
        'Event'       => 'event-page',
        'FAQ'         => 'faq-page',
        'Person'      => 'person-page',
        'JobPosition' => 'job-position-page',
        'Product'     => 'product-page',

        'Blog'                => 'coming-soon',
        'SoftwareApplication' => 'coming-so-soon',
        'HowTo'               => 'coming-soon',
        'Recipe'              => 'coming-soon',
    ],

];
