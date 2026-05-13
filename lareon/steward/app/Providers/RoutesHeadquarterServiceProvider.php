<?php

namespace Lareon\Steward\App\Providers;

use Illuminate\Support\Facades\Route;
use Teksite\Module\Providers\Support\RoutesHeadquarterServiceProvider as ServiceProvider;

class RoutesHeadquarterServiceProvider extends ServiceProvider
{
     public function map(): void
     {
        parent::map();
     }
}
