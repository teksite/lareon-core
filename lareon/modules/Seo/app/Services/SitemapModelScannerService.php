<?php

namespace Lareon\Modules\Seo\App\Services;

use Illuminate\Database\Eloquent\Model;

class SitemapModelScannerService
{
    private const int DEFAULT_CHUNK_SIZE = 1000;

    public function __construct(private readonly SaveSitemapService $saveSitemapService) {}

    public function scan(): void
    {
        foreach ($this->models() as $modelClass) {
            $this->scanModel($modelClass);
        }
    }

    private function scanModel(string $modelClass): void
    {
        if (!class_exists($modelClass)) return;

        /** @var Model $model */
        $model = new $modelClass;

        $model->newQuery()->orderBy($model->getKeyName())
              ->chunkById($this->chunkSize(), function ($models): void {
                      foreach ($models as $model) {
                          $this->saveSitemapService->sync($model);
                      }
                  }
              );
    }

    private function models(): array
    {
        return config('seo.sitemap.scan_models', []);
    }

    private function chunkSize(): int
    {
        return max(1, (int)config('seo.sitemap.scan_chunk_size', self::DEFAULT_CHUNK_SIZE));
    }
}
