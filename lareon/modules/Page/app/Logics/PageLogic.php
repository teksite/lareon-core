<?php

namespace Lareon\Modules\Page\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Lareon\Modules\Meta\App\Models\MetaElementTemplate;
use Lareon\Modules\Meta\App\Models\MetaModel;
use Lareon\Modules\Meta\App\Traits\HasMetaLogic;
use Lareon\Modules\Page\App\Models\Page;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\contracts\ServiceResult;
use Teksite\Handler\Services\FetchDataService;


class PageLogic
{
    use HasTrashLogic, HasMetaLogic;

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
            $page->metaData()->delete();
            foreach ($inputs['meta_data'] as $key => $value) {
                if (isset($value['id'])) {
                    Log::error('element_id field is not set in the element', ['instance' => $page, 'inputs' => $inputs]);
                    continue;
                }
                MetaModel::query()->create([
                        'key'         => $key,
                        'template_id' => $page->template_id,
                        'element_id'  => (int)$value['element_id'],
                        'model_type'  => Page::class,
                        'model_id'    => $page->id,
                        'content'     => $value['data'],
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

    protected function getModelClass(): string
    {
        return Page::class;
    }
}

