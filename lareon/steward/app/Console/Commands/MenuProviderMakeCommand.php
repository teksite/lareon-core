<?php

namespace Lareon\Steward\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Teksite\Module\Console\BasicMigrator;
use Teksite\Module\Console\GeneratorModuleCommand;

class MenuProviderMakeCommand extends GeneratorModuleCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-menu-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new menu provider class in modules or steward';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type = 'Menu Provider';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     * @throws \Exception
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/menu-provider.stub';
    }

    protected function path(): string
    {
        return  'app/Providers';
    }

    /**
     * set replacements
     *
     * @return array [string $searchable , string $replace ]
     */
    protected function replacements(): array
    {
        return [];

    }
    protected function getNameInput(): string
    {
        return 'MenuProvider';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, "Create the class or file even if the {$this->type} already exists"],
        ];
    }

    /**
     * Get console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of the module or steward'],
        ];
    }
}
