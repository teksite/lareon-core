<?php

namespace Lareon\Modules\Menu\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Menu\App\Models\Menu;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class MenuLogic
{
    use HasTrashLogic;

    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get(Menu::class, ['title', 'label'])
        )->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function first(array $inputs = [], bool $any = true): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = Menu::query();
            foreach ($inputs as $key => $value) {
                $query->where($key, $value);
            }
            return $query->first();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = []): ServiceResultContract
    {
        return ServiceWrapper::make(true)->do(function () use ($inputs) {
            return Menu::query()->create($inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Menu $menu, array $inputs = []): ServiceResultContract
    {
        return ServiceWrapper::make(true)->do(function () use ($menu, $inputs) {
            $menu->update($inputs);
            return $menu->fresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Menu $menu): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($menu) {
            $menu->delete();
        })->run();
    }

    protected function getModelClass(): string
    {
        return Menu::class;
    }

}

