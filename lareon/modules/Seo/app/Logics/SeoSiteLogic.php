<?php

namespace Lareon\Modules\Seo\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Models\SeoSitemap;
use Teksite\Handler\Actions\ServiceWrapper;


class SeoSiteLogic
{
    public function all(mixed $fetchData = [])
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => SeoSitemap::all())
                             ->run();

    }


    public function update(Model $model, array $inputs = []) {}


}

