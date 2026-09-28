<?php

namespace Lareon\Modules\Auth\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Steward\App\Models\Setting;
use Teksite\Handler\Services\ServiceWrapper;


class OAuthLogic
{
    public function getSettings(mixed $fetchData = [],)
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => Setting::query()->firstWhere(['key' => 'oauth']))
                             ->run();
    }

    /**
     * @throws \Throwable
     */
    public function updateSetting(array $inputs = [],)
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {

            $data = array_filter($inputs['oauth'] ?? [], function ($item,) {
                return isset($item['secret_key']) || isset($item['client_id']);
            });

            $data = array_map(function ($item,) {
                if (isset($item['secret_key']) || isset($item['client_id'])) return $item;

                $item['enable'] = 0;
                return $item;

            }, $data);


            return Setting::query()->updateOrCreate(
                ['key' => 'oauth'],
                ['value' => $data],
            );
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public
    function enables()
    {
        return ServiceWrapper::make(false)->do(function () {
            $settings = Setting::query()->firstWhere(['key' => 'oauth'])?->value ?? [];
            return array_filter($settings, function ($details,) {
                return !(empty($details['enable']) && empty($details['secret_key']) && empty($details['client_id']));
            });
        })->run();
    }


}

