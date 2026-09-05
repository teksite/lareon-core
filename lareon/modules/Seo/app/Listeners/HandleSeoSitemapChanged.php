<?php

namespace Lareon\Modules\Seo\App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Lareon\Modules\Seo\App\Events\SeoSitemapChanged;

class HandleSeoSitemapChanged
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(SeoSitemapChanged $event): void
    {
        $sitemap = $event->sitemap;

    }

}
