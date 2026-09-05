<?php

namespace Lareon\Modules\Seo\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Lareon\Modules\Seo\App\Enums\SitemapGeneratorType;
use Lareon\Modules\Seo\App\Services\CrawlerSitemapGeneratorService;
use Lareon\Modules\Seo\App\Services\DbSitemapGeneratorService;
use Symfony\Component\Finder\SplFileInfo;
use Teksite\Handler\Actions\ServiceWrapper;


class SitemapLogic
{

    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function getFiles()
    {
        return ServiceWrapper::make(false)->do(function () {
            $filesUrl = [];
            $indexUrl = null;

            $sitemapsDir = public_path('sitemaps');
            $indexPath = public_path('sitemap.xml');

            if (file_exists($indexPath)) $indexUrl = url('/sitemap.xml');

            if (file_exists($indexPath)) $indexUrl = url('/sitemap.xml');
            if (File::isDirectory($sitemapsDir)) {
                $filesUrl = collect(File::files($sitemapsDir))
                    ->filter(fn(SplFileInfo $file) => $file->getExtension() === 'xml')
                    ->map(fn(SplFileInfo $file) => url('/sitemaps/' . $file->getFilename()))
                    ->values()
                    ->toArray();
            }

            return [
                'index' => $indexUrl,
                'files' => $filesUrl,
            ];
        }
        )->run();

    }

    public function generate()
    {
        return ServiceWrapper::make(false)->do(function () {
          match (config('seo,sitemap.generator_type' , SitemapGeneratorType::DB )) {
              SitemapGeneratorType::DB => app(DbSitemapGeneratorService::class)->generate(),
              SitemapGeneratorType::Crawler => app(CrawlerSitemapGeneratorService::class)->generate(),
              default => throw new \InvalidArgumentException("Invalid sitemap generator type"),
          };
        })->run();
    }

    public function create(array $inputs = []) {}

    public function update(Model $model, array $inputs = []) {}

    public function delete(Model $model) {}

}

