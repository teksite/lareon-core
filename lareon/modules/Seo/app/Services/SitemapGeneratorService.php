<?php

namespace Lareon\Modules\Seo\App\Services;

use Illuminate\Support\Facades\File;
use Lareon\Modules\Seo\App\Enums\SitemapType;
use Lareon\Modules\Seo\App\Models\SeoSitemap;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class SitemapGeneratorService
{
    private const int DEFAULT_CHUNK_SIZE = 40_000;

    public function generate(): void
    {
        $type = config('seo.sitemap.type', SitemapType::Index->value);

        match ($type) {
            SitemapType::Single->value => $this->generateSingle(),
            SitemapType::Index->value  => $this->generateIndex(),
            default                    => throw new \InvalidArgumentException("Invalid sitemap type [{$type}]."),
        };
    }

    /**
     * Generate one sitemap.xml containing all URLs.
     */
    private function generateSingle(): void
    {
        $path = public_path(config('seo.sitemap.filename', 'sitemap.xml'));

        $sitemap = Sitemap::create();

        $this->query()->chunkById($this->chunkSize(), function ($items) use ($sitemap) {
            foreach ($items as $item) {
                $sitemap->add($this->makeUrl($item));
            }
        });

        $this->writeAtomically($sitemap, $path);
    }

    /**
     * Generate sitemap index and grouped sitemap files.
     */
    private function generateIndex(): void
    {
        $directory = config(
            'seo.sitemap.directory',
            public_path('sitemaps')
        );

        $indexPath = public_path(
            config('seo.sitemap.filename', 'sitemap.xml')
        );

        $this->prepareDirectory($directory);

        /*
         * Remove old generated sitemap files.
         *
         * This prevents stale sitemap files from remaining
         * inside /public/sitemaps.
         */
        $this->cleanGeneratedFiles($directory);

        $index = SitemapIndex::create();

        /*
         * Get groups without loading all rows.
         */
        SeoSitemap::query()
            //  ->where('available_at', null)
                  ->select('group')
                  ->whereNotNull('group')
                  ->distinct()
                  ->orderBy('group')
                  ->pluck('group')
                  ->each(function (string $group) use (
                      $directory,
                      $index
                  ) {
                      $this->generateGroup(
                          $group,
                          $directory,
                          $index
                      );
                  });

        $this->writeAtomically(
            $index,
            $indexPath
        );
    }

    /**
     * Generate all sitemap files belonging to one group.
     */
    private function generateGroup(
        string       $group,
        string       $directory,
        SitemapIndex $index
    ): void
    {
        $fileNumber = 0;

        $sitemap = Sitemap::create();

        SeoSitemap::query()
            //   ->where('available_at', null)
                  ->where('group', $group)
                  ->orderBy('id')
                  ->chunkById(
                      $this->chunkSize(),
                      function ($items) use (
                          &$sitemap,
                          &$fileNumber,
                          $group,
                          $directory,
                          $index
                      ) {
                          foreach ($items as $item) {

                              $sitemap->add(
                                  $this->makeUrl($item)
                              );

                              /*
                               * We deliberately split by URL count.
                               *
                               * 40,000 is below Google's 50,000 limit.
                               */
                              if (
                                  $sitemap->getUrls()->count()
                                  >= $this->chunkSize()
                              ) {
                                  $this->writeGroupFile(
                                      $sitemap,
                                      $group,
                                      $fileNumber,
                                      $directory,
                                      $index
                                  );

                                  $fileNumber++;

                                  $sitemap = Sitemap::create();
                              }
                          }
                      }
                  );

        /*
         * Write remaining URLs.
         */
        if ($sitemap->getUrls()->isNotEmpty()) {
            $this->writeGroupFile(
                $sitemap,
                $group,
                $fileNumber,
                $directory,
                $index
            );
        }
    }

    /**
     * Create URL tag.
     */
    private function makeUrl(SeoSitemap $item): Url
    {
        $url = Url::create($this->absoluteUrl($item->url));

        if ($item->last_modified) $url->setLastModificationDate($item->last_modified);


        if ($item->priority !== null) $url->setPriority((float)$item->priority);


        if ($item->change_frequency) $url->setChangeFrequency($item->change_frequency);


        if (is_array($item->image)) {
            foreach ($item->image as $image) {
                if (is_string($image)) {
                    $url->addImage($this->absoluteUrl($image));
                    continue;
                }

                if (!is_array($image)) continue;

                if (!empty($image['url'])) $url->addImage($this->absoluteUrl($image['url']), $image['caption'] ?? null);

            }
        }

        if (is_array($item->video)) {
            foreach ($item->video as $video) {

                if (!is_array($video)) continue;

                if (empty($video['thumbnail_loc']) || empty($video['title']) || empty($video['description'])) continue;

                $url->addVideo($this->absoluteUrl($video['thumbnail_loc']),
                    $video['title'],
                    $video['description'],
                    !empty($video['content_loc']) ? $this->absoluteUrl($video['content_loc']) : null,
                    !empty($video['player_loc']) ? $this->absoluteUrl($video['player_loc']) : null,
                );
            }
        }

        return $url;
    }

    /**
     * Write a single grouped sitemap.
     */
    private function writeGroupFile(Sitemap $sitemap, string $group, int $fileNumber, string $directory, SitemapIndex $index): void
    {
        $filename = $this->groupFilename($group, $fileNumber);

        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        $this->writeAtomically(
            $sitemap,
            $path
        );

        /*
         * URL used inside sitemap.xml.
         */
        $index->add(
            $this->publicSitemapUrl($filename)
        );
    }

    /**
     * Filename:
     *
     * group_sitemap.xml
     * group_1_sitemap.xml
     * group_2_sitemap.xml
     */
    private function groupFilename(
        string $group,
        int    $fileNumber
    ): string
    {
        $safeGroup = $this->sanitizeGroup($group);

        if ($fileNumber === 0) {
            return "{$safeGroup}_sitemap.xml";
        }

        return "{$safeGroup}_{$fileNumber}_sitemap.xml";
    }

    /**
     * Prevent path traversal / invalid filenames.
     */
    private function sanitizeGroup(string $group): string
    {
        $group = trim($group);

        $group = preg_replace(
            '/[^a-zA-Z0-9_-]+/',
            '-',
            $group
        );

        $group = trim($group, '-_');

        if ($group === '') {
            return 'default';
        }

        return substr($group, 0, 100);
    }

    /**
     * Build sitemap query.
     */
    private function query()
    {
        return SeoSitemap::query()
                         ->where('activating', true)
                         ->whereNotNull('url')
                         ->where('url', '!=', '')
                         ->orderBy('id');
    }

    /**
     * Absolute URL.
     */
    private function absoluteUrl(string $url): string
    {
        if (
            str_starts_with($url, 'http://') ||
            str_starts_with($url, 'https://')
        ) {
            return $url;
        }

        return rtrim(
                config('seo.sitemap.base_url', config('app.url')),
                '/'
            ) . '/' . ltrim($url, '/');
    }

    /**
     * Public URL of a grouped sitemap.
     */
    private function publicSitemapUrl(string $filename): string
    {
        return rtrim(
                config('seo.sitemap.base_url', config('app.url')),
                '/'
            ) . '/sitemaps/' . rawurlencode($filename);
    }

    /**
     * Sitemap chunk size.
     */
    private function chunkSize(): int
    {
        return max(
            1,
            min(
                (int)config(
                    'seo.sitemap.max_urls_per_file',
                    self::DEFAULT_CHUNK_SIZE
                ),
                50_000
            )
        );
    }

    /**
     * Prepare output directory.
     */
    private function prepareDirectory(string $directory): void
    {
        if (!File::exists($directory)) {
            File::makeDirectory(
                $directory,
                0755,
                true
            );
        }
    }

    /**
     * Remove old generated sitemap files.
     */
    private function cleanGeneratedFiles(string $directory): void
    {
        if (!File::isDirectory($directory)) {
            return;
        }

        foreach (File::files($directory) as $file) {

            if (
                $file->getExtension() !== 'xml'
            ) {
                continue;
            }

            File::delete(
                $file->getRealPath()
            );
        }
    }

    /**
     * Atomic sitemap writing.
     *
     * Write to a temporary file first and then rename it.
     * This prevents users / Google from reading a partially
     * generated XML file.
     */
    private function writeAtomically(
        Sitemap|SitemapIndex $sitemap,
        string               $path
    ): void
    {
        $directory = dirname($path);

        $this->prepareDirectory($directory);

        $temporaryPath =
            $path . '.tmp-' . bin2hex(random_bytes(8));

        try {

            $sitemap->writeToFile(
                $temporaryPath
            );

            if (!File::exists($temporaryPath)) {
                throw new \RuntimeException(
                    "Sitemap file was not created: {$temporaryPath}"
                );
            }

            File::move(
                $temporaryPath,
                $path
            );

        } finally {

            if (File::exists($temporaryPath)) {
                File::delete($temporaryPath);
            }
        }
    }
}
