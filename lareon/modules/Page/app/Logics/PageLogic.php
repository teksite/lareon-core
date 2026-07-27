<?php

namespace Lareon\Modules\Page\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Lareon\Modules\Page\App\Models\Page;
use Teksite\FileManager\Models\UploadFile;
use Teksite\FileManager\Models\UploadFileRelation;
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
                                 fn() => FetchDataService::get(Page::class, ['title', 'slug', 'publish_status'])
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
            $page = Page::query()->create(Arr::except($inputs, ['image', 'seo', 'meta']));
            if (isset($inputs['image'])) {

                UploadFileRelation::query()->create([
                    'file_id'    => $inputs['image'],
                    'model_id'   => $page->getKey(),
                    'model_type' => $page->getMorphClass(),
                    'collection' => 'featured_image',
                    'order'      => 0,
                    'name'       => $page->title,
                ]);
            }
            return $page;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Page $page, array $inputs = []): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($page, $inputs) {
            $page->update($inputs);
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

