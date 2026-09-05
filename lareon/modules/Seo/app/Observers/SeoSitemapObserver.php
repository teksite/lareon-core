<?php

namespace Lareon\Modules\Seo\App\Observers;

use Lareon\Modules\Seo\App\Events\SeoSitemapChanged;
use Lareon\Modules\Seo\App\Models\SeoSitemap;

class SeoSitemapObserver
{
    public function created(SeoSitemap $sitemap): void
    {
        $this->dispatch($sitemap, 'created');
    }

    public function updated(SeoSitemap $sitemap): void
    {
        $this->dispatch($sitemap, 'updated');
    }

    public function deleted(SeoSitemap $sitemap): void
    {
        $this->dispatch($sitemap, 'deleted');
    }

    private function dispatch(SeoSitemap $sitemap, string $action): void
    {
        SeoSitemapChanged::dispatch($sitemap, $action);
    }
}
