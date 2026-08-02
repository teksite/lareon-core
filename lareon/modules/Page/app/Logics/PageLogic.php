<?php

namespace Lareon\Modules\Page\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Lareon\Modules\Meta\App\Models\MetaFieldTemplate;
use Lareon\Modules\Meta\App\Models\MetaModel;
use Lareon\Modules\Page\App\Models\Page;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\contracts\ServiceResult;
use Teksite\Handler\Services\FetchDataService;


class PageLogic
{
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
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = []): ServiceResult
    {
        return ServiceWrapper::make(true)->do(function () use ($inputs) {
            $page = Page::query()->create(Arr::except($inputs, ['seo', 'meta']));
            return $page;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Page $page, array $inputs = []): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($page, $inputs) {

            $page->update(Arr::except($inputs, ['seo', 'meta']));
            $page->metaData()->detach();

            foreach ($inputs['meta_data'] as $key => $value) {
                MetaModel::query()->updateOrCreate(
                    [
                        'meta_template_id' => $page->template_id,
                        'model_type'       => Page::class,
                        'model_id'         => $page->id,

                    ],[
                        'content'          => $value,
                    ]
                );
            }
            return $page->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Page $page): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($page) {
            $page->delete();
        })->run();
    }

}

