<?php

namespace Lareon\Modules\Seo\App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Lareon\Modules\Seo\App\Contracts\GeneratorSitemap;
use Lareon\Modules\Seo\App\Enums\SitemapType;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class CrawlerSitemapGeneratorService implements GeneratorSitemap
{
    private const int DEFAULT_MAX_URLS_PER_FILE = 40000;

    public function generate(): void
    {
        match ($this->type()) {
            SitemapType::Single => $this->generateSingle(),
            SitemapType::Index  => $this->generateIndex(),

            default => throw new \InvalidArgumentException("Invalid sitemap type [{$this->type()->value}]."),
        };
    }

    /**
     * Generate a single sitemap.xml.
     */
    private function generateSingle(): void
    {
        $this->cleanup();

        SitemapGenerator::create($this->baseUrl())
                        ->shouldCrawl(fn (string $url): bool => ! $this->isExcluded($url))
                        ->writeToFile($this->mainPath());
    }

    /**
     * Generate sitemap index and child sitemap files.
     */
    private function generateIndex(): void
    {
        $this->prepareDirectory();
        $this->cleanup();

        SitemapGenerator::create($this->baseUrl())
                        ->shouldCrawl(fn (string $url): bool => ! $this->isExcluded($url))
                        ->maxTagsPerSitemap($this->maxUrlsPerFile())
                        ->sitemapIndexPath($this->mainPath())
                        ->writeToFile(fn (Url $url): string => $this->nextSitemapPath());
    }

    /**
     * Return the next sitemap file path.
     *
     * The callback is called for every crawled URL.
     */
    private function nextSitemapPath(): string
    {
        static $fileNumber = 0;
        static $path = null;

        if ($path === null) $path = $this->sitemapPath($fileNumber);

        return $path;
    }

    /**
     * Build sitemap child path.
     */
    private function sitemapPath(int $number): string
    {
        return $this->sitemapDirectory() . DIRECTORY_SEPARATOR . 'sitemap-' . ($number + 1) . '.xml';
    }

    /**
     * Sitemap type.
     */
    private function type(): SitemapType
    {
        $type = config('seo.sitemap.type', SitemapType::Single->value);

        if ($type instanceof SitemapType) return $type;

        return SitemapType::from($type);
    }

    /**
     * URLs that should not be crawled.
     */
    private function isExcluded(string $url): bool
    {
        return in_array($url, $this->excludedUrls(), true);
    }

    /**
     * Build excluded URLs.
     */
    private function excludedUrls(): array
    {
        $exceptions = config('seo.sitemap.except', []);

        $urls = [];

        foreach ($exceptions['routes'] ?? [] as $route) {
            if (! Route::has($route))  continue;
            $urls[] = route($route);
        }

        foreach ($exceptions['urls'] ?? [] as $url) $urls[] = url($url);


        return array_values(array_unique(array_filter($urls)));
    }

    /**
     * Maximum URLs per sitemap file.
     */
    private function maxUrlsPerFile(): int
    {
        return max(
            1,
            min((int) config('seo.sitemap.max_urls_per_file', self::DEFAULT_MAX_URLS_PER_FILE), 50000)
        );
    }

    /**
     * Sitemap index path.
     */
    private function mainPath(): string
    {
        return public_path(config('seo.sitemap.filename', 'sitemap.xml'));
    }

    /**
     * Child sitemap directory.
     */
    private function sitemapDirectory(): string
    {
        return config('seo.sitemap.directory', public_path('sitemaps'));
    }

    /**
     * Base URL.
     */
    private function baseUrl(): string
    {
        return rtrim(config('seo.sitemap.base_url', config('app.url')), '/');
    }

    /**
     * Prepare sitemap directory.
     */
    private function prepareDirectory(): void
    {
        File::ensureDirectoryExists($this->sitemapDirectory());
    }

    /**
     * Remove previously generated sitemap files.
     */
    private function cleanup(): void
    {
        $directory = $this->sitemapDirectory();

        if (! File::isDirectory($directory)) return;
        
        foreach (File::files($directory) as $file) {
            if ($file->getExtension() !== 'xml')  continue;
            File::delete($file->getRealPath());
        }
    }
}
