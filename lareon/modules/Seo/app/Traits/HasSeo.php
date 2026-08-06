<?php

namespace Lareon\Modules\Seo\App\Traits;

trait HasSeo
{
    use HasMeta, HasSchema, HasSitemap;

    public function saveSeo(array $seoInputs=[]): void
    {
        if ($this->hasSiteMap && $this->hasSiteMap === false) return;
        $this->syncSitemap($seoInputs['sitemap'] ?? []);
    }



    public function deleteSeo(array $seoInputs=[]): void
    {
        if ($this->hasSiteMap && $this->hasSiteMap === false) return;
        $this->deleteSitemap();
    }
}
