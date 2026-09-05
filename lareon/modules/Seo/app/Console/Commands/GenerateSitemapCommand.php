<?php

namespace Lareon\Modules\Seo\App\Console\Commands;

use Illuminate\Console\Command;
use Lareon\Modules\Seo\App\Enums\SitemapGeneratorType;
use Lareon\Modules\Seo\App\Services\CrawlerSitemapGeneratorService;
use Lareon\Modules\Seo\App\Services\DbSitemapGeneratorService;
use Symfony\Component\Console\Input\InputOption;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */


    protected $name = 'seo:sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate SEO sitemap files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Generating sitemap...');

        $type = $this->option('generator');
        if ($type) {
            $generator = SitemapGeneratorType::tryFrom($type);
        } else {
            $generator = config('seo.sitemap.generator_type');
        }

        try {

            match ($generator) {
                SitemapGeneratorType::DB      => app(DbSitemapGeneratorService::class)->generate(),
                SitemapGeneratorType::Crawler => app(CrawlerSitemapGeneratorService::class)->generate(),
                default                       => throw new \InvalidArgumentException("Invalid sitemap generator type [$generator]"),
            };
        } catch (\Throwable $e) {

            report($e);

            $this->components->error(
                $e->getMessage()
            );

            return self::FAILURE;
        }

        $this->components->info(
            'Sitemap generated successfully.'
        );

        return self::SUCCESS;
    }


    protected function getOptions(): array
    {
        return [
            ['generator', null, InputOption::VALUE_OPTIONAL, 'generate by db or crawler or by config'],
        ];
    }
}
