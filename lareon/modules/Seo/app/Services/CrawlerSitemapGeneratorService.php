<?php

namespace Lareon\Modules\Seo\App\Services;

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Seo\App\Contracts\GeneratorSitemap;
use Spatie\Sitemap\SitemapGenerator;

class CrawlerSitemapGeneratorService implements GeneratorSitemap
{
    public function generate(): void
    {
        $exceptions = config('seo.sitemap.except', []);

        $urls = [];

        foreach ($exceptions['routes'] ?? [] as $route) {
            if (Route::has($route)) $urls[] = route($route);

        }

        foreach ($exceptions['urls'] ?? [] as $url) {
            $urls[] = url($url);
        }

        $urls = array_values(array_unique(array_filter($urls)));

        SitemapGenerator::create(config('seo.sitemap.base_url'))
                        ->shouldCrawl(function ($url) use ($urls) {
                            return !in_array((string)$url, $urls, true);
                        })
                        ->writeToFile($this->mainPath());
    }

    private function mainPath(): string
    {
        return public_path(config('seo.sitemap.filename', 'sitemap.xml'));
    }
}
