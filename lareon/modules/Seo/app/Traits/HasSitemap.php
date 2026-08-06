<?php

namespace Lareon\Modules\Seo\App\Traits;


use Lareon\Modules\Seo\App\Models\SeoSitemap;
use Lareon\Modules\Seo\App\Service\SeoSitemapService;

trait HasSitemap
{
    protected static function bootHasSitemap(): void
    {
        static::saved(function ($model) {
            app(SeoSitemapService::class)->syncSitemap($model);
        });

        static::deleted(function ($model) {
            app(SeoSitemapService::class)->desyncSitemap($model);
        });

        static::restored(function ($model) {
            app(SeoSitemapService::class)->syncSitemap($model);
        });
    }

    public function siteMap()
    {
        return $this->morphOne(SeoSitemap::class, 'model');
    }
}
