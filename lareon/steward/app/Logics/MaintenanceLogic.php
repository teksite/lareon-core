<?php

namespace Lareon\Steward\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;
use Teksite\Handler\Actions\ServiceResult;
use Teksite\Handler\Actions\ServiceWrapper;


class MaintenanceLogic
{


    /**
     * disable maintenance mode
     *
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function up(): ServiceResult
    {
        return ServiceWrapper::make(hasTransaction: false)->do(function () {
            Artisan::call('up');
        })->run();
    }

    /**
     * enable maintenance mode
     *
     * @throws BindingResolutionException|\Throwable
     *
 */
    public function down(string $secretString): ServiceResult
    {
        return ServiceWrapper::make(hasTransaction: false)->do(function () use ($secretString) {
            Artisan::call('down', ['--secret' => $secretString]);
        })->run();
    }
}


