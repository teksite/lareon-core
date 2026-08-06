<?php

namespace Lareon\Modules\Page\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Lareon\Modules\Page\App\Models\Page;
use Lareon\Steward\App\Service\ContentSaverService;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\contracts\ServiceResult;
use Teksite\Handler\Services\FetchDataService;


class PageLogic
{
    use HasTrashLogic;

    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(
                                 fn() => FetchDataService::get(Page::class, ['title', 'slug', 'publish_status'], with: ['primaryMedia'])
                             )->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function first(array $inputs = [], bool $any = true): ServiceResult
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
    public function create(array $inputs = []): ServiceResult
    {
        return ServiceWrapper::make(true)->do(function () use ($inputs) {
            return ContentSaverService::create(new Page, $inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Page $page, array $inputs = []): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($page, $inputs) {
            return ContentSaverService::update($page, $inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Page $page): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($page) {
            $page->delete();
            $page->deleteSeo();
            $page->deteMetaData();
        })->run();
    }

    protected function getModelClass(): string
    {
        return Page::class;
    }
}

