<?php

namespace Lareon\Modules\Seo\App\Console\Commands;

use Illuminate\Console\Command;
use Lareon\Modules\Seo\App\Services\SitemapGeneratorService;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */


    protected $signature = 'seo:sitemap:generate';

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

        try {

            app(SitemapGeneratorService::class)->generate();

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
}
