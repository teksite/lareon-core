<?php

namespace Lareon\Steward\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Artisan;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Services\ServiceWrapper;


class MaintenanceLogic
{


    /**
     * disable maintenance mode
     *
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function up(): ServiceResultContract
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
    public function down(string $secretString): ServiceResultContract
    {
        return ServiceWrapper::make(hasTransaction: false)->do(function () use ($secretString) {
            Artisan::call('down', ['--secret' => $secretString]);
        })->run();
    }
}


