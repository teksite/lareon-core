<?php

namespace Lareon\Modules\Menu\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lareon\Modules\Menu\App\Models\Menu;
use Lareon\Modules\Menu\App\Models\SubMenu;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class SubMenuLogic
{

    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get(SubMenu::class, ['title']),
        )->run();
    }

    /**
     * @throws \Throwable
     */
    public function allByMenu(Menu $menu, mixed $fetchData = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($menu) {
            $items = SubMenu::where('menu_id', $menu->id)->tree()->get();
            return $items->toTree();
        })->run();
    }


    /**
     * @throws \Throwable
     */
    public function create(Menu $menu, array $inputs = [],): ServiceResultContract
    {
        return ServiceWrapper::make(true)->do(function () use ($menu, $inputs) {
            $max = DB::table('menu_items')->where('menu_id', $menu->id)->max('position');

            $inputs['position'] = $max ? $max + 1 : 0;
            $inputs['parent_id'] = null;
            $menu->subs()->create($inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Menu $menu, array $inputs = [],): ServiceResultContract
    {
        return ServiceWrapper::make(true)->do(function () use ($menu, $inputs) {
            $items = $inputs['items'];
            $updateItems = [];
            $newItems = [];
            $existingIds = $menu->subs()->pluck('id')->toArray();
            $newItemIds = collect($items)->pluck('id')->filter()->toArray();

            foreach ($items as $key=>$item) {
                if (isset($item['id'])) {
                    $item['menu_id']=$menu->id;
                    $updateItems[] = $item;
                }else{
                    $newItems[$key] = $item;
                    $newItems[$key]['menu_id'] =$menu->id;

                }
            }

            DB::table('menu_items')->upsert($updateItems, ['id']);
            DB::table('menu_items')->insert($newItems);

            $idDiff = array_diff($existingIds, $newItemIds);
            if (!empty($idDiff))  $menu->subs()->whereIn('id', $idDiff)->delete();

        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(SubMenu $item,): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($item) {
            $item->delete();
        })->run();
    }

    protected function getModelClass(): string
    {
        return SubMenu::class;
    }

}

