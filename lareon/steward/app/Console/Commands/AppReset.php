<?php

namespace Lareon\Steward\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use Throwable;
use ZipArchive;

class AppReset extends Command
{
    use ConfirmableTrait;

    protected $name = 'app:reset';

    protected $description = 'reset the app: refresh migrations, seed, restore backups, clear caches';

    protected function getOptions(): array
    {
        return [
            ['database', null, InputOption::VALUE_REQUIRED, 'The database connection to use'],
            ['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run'],
            ['restore', null, InputOption::VALUE_NONE, 'Restore SQL backups from the path defined in config'],
            ['path', null, InputOption::VALUE_REQUIRED, 'Override the backup file/directory (used with --restore)'],
            ['composer', null, InputOption::VALUE_NONE, 'Dump autoload of composer'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run in production'],
        ];
    }

    public function handle(): int
    {
        if (!$this->confirmToProceed()) return self::FAILURE;


        $connection = $this->getDatabaseConnection();
        $dbOption = $this->option('database') ? ['--database' => $this->option('database')] : [];

        try {
            $this->call('migrate:fresh', $dbOption + ['--force' => true]);
            $this->call('module:migrate', $dbOption + ['--force' => true]);

            if ($this->option('seed')) {
                $this->call('module:db-seed', $dbOption + ['--force' => true]);
                $this->call('db:seed', $dbOption + ['--force' => true]);
            }

            if ($this->option('restore')) {
                if (!$this->restoreBackups($connection)) {
                    $this->components->error('Some backups failed to restore.');
                    return self::FAILURE;
                }
            }

            $this->newLine();
            $this->line('<fg=cyan;options=bold>clearing cached data</>');

            $this->callSilent('optimize:clear');
            $this->components->twoColumnDetail('<fg=gray>  └─ optimized data (config, routes, views, cache)</>', '<fg=green>✓ cleared</>');

            $this->callSilent('schedule:clear-cache');
            $this->components->twoColumnDetail('<fg=gray>  └─ schedule cache</>', '<fg=green>✓ cleared</>');

            if ($this->option('composer')) $this->call('composer:dump-autoload');

            $this->info('The site is refreshed successfully :)');

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->newLine();
            $this->components->error($e->getMessage());

            if ($this->getOutput()->isVerbose()) $this->line($e->getTraceAsString());

            return self::FAILURE;
        }
    }

    /* -----------------------------------------------------------------
     |  Restore
     | -----------------------------------------------------------------*/

    protected function restoreBackups(string $connection): bool
    {
        $this->newLine();
        $this->line('<fg=cyan;options=bold>restoring backups</>');

        $files = $this->findBackupFiles();

        if (empty($files)) {
            $this->components->warn('No backup files found.');
            return true;
        }

        $allOk = true;

        foreach ($files as $file) {
            $temps = [];

            try {
                foreach ($this->expand($file, $temps) as $item) {
                    try {
                        $this->restoreOne($item['path'], $item['name'], $connection);
                        $this->components->twoColumnDetail("<fg=gray>  └─ {$item['name']}</>", '<fg=green>✓ restored</>');
                    } catch (Throwable $e) {
                        $allOk = false;
                        $this->components->twoColumnDetail("<fg=gray>  └─ {$item['name']}</>", '<fg=red>✗ failed</>');
                        $this->components->error($e->getMessage());
                    }
                }
            } catch (Throwable $e) {
                $allOk = false;
                $this->components->twoColumnDetail('<fg=gray>  └─ '.basename($file).'</>', '<fg=red>✗ failed</>');
                $this->components->error($e->getMessage());
            } finally {
                $this->cleanup($temps);
            }
        }

        return $allOk;
    }

    /**
     * @return string[]
     */
    protected function findBackupFiles(): array
    {
        $path = $this->option('path') ?: config('steward.restore.path', storage_path('app/backups'));
        $extensions = config('steward.restore.extensions', ['sql', 'gz', 'zip', 'dump', 'backup', 'bak']);

        if (is_file($path)) return [$path];


        if (!is_dir($path)) throw new RuntimeException("Backup path not found: {$path}");


        $files = config('steward.restore.recursive', false)
            ? File::allFiles($path)
            : File::files($path);

        return collect($files)
            ->filter(fn($f) => in_array(strtolower($f->getExtension()), $extensions, true))
            ->map(fn($f) => $f->getPathname())
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }

    /**
     * Decompress gz/zip into temp files. Returns list of [path, name].
     *
     * @param string[] $temps temp paths to be cleaned up afterwards
     */
    protected function expand(string $file, array &$temps): array
    {
        return match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
            'gz'    => [$this->gunzip($file, $temps)],
            'zip'   => $this->unzip($file, $temps),
            default => [['path' => $file, 'name' => basename($file)]],
        };
    }

    protected function gunzip(string $file, array &$temps): array
    {
        $tmp = tempnam(sys_get_temp_dir(), 'restore_');
        $temps[] = $tmp;

        $in = gzopen($file, 'rb');
        $out = fopen($tmp, 'wb');

        if (!$in || !$out) {
            throw new RuntimeException("Cannot decompress: {$file}");
        }

        while (!gzeof($in)) {
            fwrite($out, gzread($in, 1024 * 1024));
        }

        gzclose($in);
        fclose($out);

        return ['path' => $tmp, 'name' => basename($file, '.gz')];
    }

    protected function unzip(string $file, array &$temps): array
    {
        $zip = new ZipArchive();

        if ($zip->open($file) !== true) throw new RuntimeException("Cannot open zip: {$file}");

        $dir = sys_get_temp_dir().'/restore_'.uniqid();
        mkdir($dir, 0700, true);
        $temps[] = $dir;

        $zip->extractTo($dir);
        $zip->close();

        $allowed = ['sql', 'dump', 'backup', 'bak'];

        return collect(File::allFiles($dir))
            ->filter(fn($f) => in_array(strtolower($f->getExtension()), $allowed, true))
            ->sortBy(fn($f) => $f->getPathname(), SORT_NATURAL | SORT_FLAG_CASE)
            ->map(fn($f) => ['path' => $f->getPathname(), 'name' => basename($file).':'.$f->getFilename()])
            ->values()
            ->all();
    }

    protected function cleanup(array $temps): void
    {
        foreach ($temps as $tmp) {
            is_dir($tmp) ? File::deleteDirectory($tmp) : File::delete($tmp);
        }
    }

    protected function restoreOne(string $path, string $name, string $connection): void
    {
        $config = config("database.connections.{$connection}");

        if (!$config) throw new RuntimeException("Database connection [{$connection}] is not configured.");

        $driver = $config['driver'];
        $isPgCustom = in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), ['dump', 'backup', 'bak'], true);

        match (true) {
            in_array($driver, ['mysql', 'mariadb'], true) && $this->hasBinary('mysql') => $this->restoreWithMysql($path, $config),
            $driver === 'pgsql' && $isPgCustom && $this->hasBinary('pg_restore')       => $this->restoreWithPgRestore($path, $config),
            $driver === 'pgsql' && $this->hasBinary('psql')                            => $this->restoreWithPsql($path, $config),
            default                                                                    => $this->restoreWithPdo($path, $connection),
        };
    }

    protected function restoreWithMysql(string $path, array $c): void
    {
        $cmd = ['mysql', '--user='.$c['username'], '--default-character-set=utf8mb4'];

        if (!empty($c['unix_socket'])) {
            $cmd[] = '--socket='.$c['unix_socket'];
        } else {
            $cmd[] = '--host='.$c['host'];
            $cmd[] = '--port='.$c['port'];
        }

        $cmd[] = $c['database'];

        $handle = fopen($path, 'rb');
        $this->runProcess(new Process($cmd, null, ['MYSQL_PWD' => (string)($c['password'] ?? '')]), $handle);
    }

    protected function restoreWithPsql(string $path, array $c): void
    {
        $cmd = [
            'psql', '--quiet',
            '--host='.$c['host'], '--port='.$c['port'],
            '--username='.$c['username'], '--dbname='.$c['database'],
            '-v', 'ON_ERROR_STOP=1',
            '-f', $path,
        ];

        $this->runProcess(new Process($cmd, null, ['PGPASSWORD' => (string)($c['password'] ?? '')]));
    }

    protected function restoreWithPgRestore(string $path, array $c): void
    {
        $cmd = [
            'pg_restore', '--clean', '--if-exists', '--no-owner',
            '--host='.$c['host'], '--port='.$c['port'],
            '--username='.$c['username'], '--dbname='.$c['database'],
            $path,
        ];

        $this->runProcess(new Process($cmd, null, ['PGPASSWORD' => (string)($c['password'] ?? '')]));
    }

    /**
     * Driver-agnostic fallback (sqlite, sqlsrv, or when CLI client is missing).
     * Loads the whole file in memory, so it's not suited for huge dumps.
     *
     * @throws FileNotFoundException
     */
    protected function restoreWithPdo(string $path, string $connection): void
    {
        $sql = File::get($path);

        Schema::connection($connection)->disableForeignKeyConstraints();

        try {
            DB::connection($connection)->unprepared($sql);
        } finally {
            Schema::connection($connection)->enableForeignKeyConstraints();
        }
    }

    protected function runProcess(Process $process, $input = null): void
    {
        $process->setTimeout(null);

        if ($input)   $process->setInput($input);

        $process->run();

        if (is_resource($input)) fclose($input);

        if (!$process->isSuccessful())  throw new RuntimeException(trim($process->getErrorOutput()) ?: 'Restore process failed.');

    }

    protected function hasBinary(string $name): bool
    {
        return (new ExecutableFinder())->find($name) !== null;
    }

    protected function getDatabaseConnection(): string
    {
        return $this->option('database') ?: $this->laravel['config']['database.default'];
    }
}
