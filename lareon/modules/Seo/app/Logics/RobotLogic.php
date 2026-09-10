<?php

namespace Lareon\Modules\Seo\App\Logics;

use Illuminate\Support\Facades\File;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Data\ServiceResult;
use Teksite\Handler\Services\ServiceWrapper;


class RobotLogic
{
    private const string ROBOTS_FILE_NAME = 'robots.txt';

    private readonly string $filePath;

    public function __construct()
    {
        $this->filePath = public_path(self::ROBOTS_FILE_NAME);
    }


    /**
     * Retrieves the contents of the robots.txt file
     *
     * @return ServiceResult The file contents
     * @throws
     */
    public function getContent(): ServiceResultContract
    {
        return ServiceWrapper::make(hasTransaction: false)->do(function () {
            $this->ensureFileExists();
            return File::get($this->filePath);
        })->run();
    }

    /**
     * Updates the contents of the robots.txt file
     *
     * @param array $inputs Array containing the new content under 'content' key
     * @return ServiceResultContract The updated file contents
     * @throws
     */
    public function changeContent(array $inputs,): ServiceResultContract
    {
        return ServiceWrapper::make(hasTransaction: false)->do(function () use ($inputs) {
            $content = trim((string)($inputs['content'] ?? ''));
            File::put($this->filePath, $inputs['content']);
            File::put($this->filePath, $content, lock: true);
            return File::get($this->filePath);
        })->run();
    }

    private function ensureFileExists(): void
    {
        if (File::exists($this->filePath)) return;
        File::put($this->filePath, $this->defaultContent(), lock: true);
    }

    /**
     * default content for robot.txt
     *
     * @return string
     */
    private function defaultContent(): string
    {
        $homeUrl = rtrim(url('/'), '/');
        $adminPath = $this->extractPath(route('admin.dashboard'));
        $sitemapUrl = "{$homeUrl}/sitemap.xml";

        return <<<EOT
            User-agent: *
            Disallow: {$adminPath}/
            Disallow: /login/
            Allow: /

            Sitemap: {$sitemapUrl}

            # Prevent crawling of search results pages
            Disallow: /search/

            # Block crawling of common temporary or cache files
            Disallow: /*.php$
            Disallow: /*.tmp$

            # Crawl delay for politeness (optional)
            Crawl-delay: 10

            # Host directive (optional, for some search engines)
            Host: {$homeUrl}
            EOT;
    }

    private function extractPath(string $url,): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '/tkadmin';
        return '/'.ltrim($path, '/');
    }

}

