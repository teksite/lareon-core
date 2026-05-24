<?php

namespace Lareon\Steward\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputOption;

class AppReset extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'app:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset the app: refresh migrations, clear caches';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],
            ['admin', null, InputOption::VALUE_OPTIONAL, 'make admin user'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run'],
            ['restore', null, InputOption::VALUE_OPTIONAL, 'restore backups from storage'],
        ];
    }

    public function handle(): void
    {
        try {
            $admin = $this->option('admin');
            $doRestore = $this->option('restore');

            $options = [
                '--database' => $this->option('database'),
            ];

            $this->call('migrate:reset',$options);

            $this->call('module:migrate',$options);


            if ($this->option('seed')) {
                $this->call('module:db-seed');
                $this->call('db:seed');
            }


          /*  if ($doRestore) {
                if (is_dir($backupPath)) {
                    $files = File::allFiles($backupPath);
                    if (count($files) > 0) {
                        $this->line('restoring backup data:');

                        foreach ($files as $file) {
                            $fileName = $file->getBasename();
                            $path = $file->getPathname();
                            $this->print(function () use ($path, $db) {
                                DB::unprepared(file_get_contents($path));
                                // exec("mysql --user={$db['username']} --password={$db['password']} --host={$db['host']} --database {$db['database']} < $path");
                            }, "$fileName");
                        }
                        $this->newLine();
                    }
                }
            }*/

           /* $this->newLine();
            $this->line("<fg=cyan;options=bold>clearing cached data</>");
            Artisan::call('optimize:clear');
            $this->components->twoColumnDetail("<fg=gray>  └─ optimized data</>", "<fg=green>✓ cleared</>");

            Artisan::call('config:clear');
            $this->components->twoColumnDetail("<fg=gray>  └─ configs data</>", "<fg=green>✓ cleared</>");

            Artisan::call('route:clear');
            $this->components->twoColumnDetail("<fg=gray>  └─ routes data</>", "<fg=green>✓ cleared</>");


            Artisan::call('view:clear');
            $this->components->twoColumnDetail("<fg=gray>  └─ views data</>", "<fg=green>✓ cleared</>");

            Artisan::call('cache:clear');
            $this->components->twoColumnDetail("<fg=gray>  └─ caches data</>", "<fg=green>✓ cleared</>");

            $this->info('The site is refreshed successfully :)');*/

        } catch (\Exception $e) {

        }
    }


    protected function getDatabaseConnection(): string
    {
        return $this->option('database') ?: $this->laravel['config']['database.default'];
    }

}
