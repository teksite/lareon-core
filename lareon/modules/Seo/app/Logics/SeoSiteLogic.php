<?php

namespace Lareon\Modules\Seo\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Models\SeoSite;
use Teksite\Handler\Actions\ServiceWrapper;


class SeoSiteLogic
{
    public function all(mixed $fetchData = [])
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => SeoSite::all()->keyBy('key'))
                             ->run();

    }


    public function updateAll(array $inputs = [])
    {
        return ServiceWrapper
            ::make(false)
            ->do(function () use ($inputs) {
                foreach ($inputs as $key => $input) {
                    SeoSite::query()->updateOrCreate(
                        ['key' => $key,],
                        ['value' => $input, 'state' => $input['state'] ?? false,]
                    );
                }
            })->run();
    }


    public function update(SeoSite $seoSite, array $inputs = [])
    {
        return ServiceWrapper::make(false)
                             ->do(function () use ($seoSite, $inputs) {
                                 $seoSite->update($inputs);
                                 return $seoSite->refresh();
                             })->run();
    }


}

