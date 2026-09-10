<?php

namespace Lareon\Modules\Page\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Lareon\Modules\Page\App\Models\Page;
use Lareon\Steward\App\Service\ContentSaverService;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class PageLogic
{
    use HasTrashLogic;

    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get(Page::class, ['title', 'slug', 'publish_status'], with:[ 'primaryMedia'])
        )->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function first(array $inputs = [], bool $any = true): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = Page::query();
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
            return ContentSaverService::create(new Page, $inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Page $page, array $inputs = []): ServiceResultContract
    {
        return ServiceWrapper::make(true)->do(function () use ($page, $inputs) {
            return ContentSaverService::update($page, $inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Page $page): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($page) {
            return ContentSaverService::delete($page);

        })->run();
    }

    protected function getModelClass(): string
    {
        return Page::class;
    }
}

