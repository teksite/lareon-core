<?php

namespace Lareon\Modules\Meta\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Lareon\Modules\Meta\App\Models\MetaTemplate;
use Teksite\Handler\Actions\ServiceResult;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Services\FetchDataService;

class MetaTemplateLogic
{
    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(
                                 fn() => FetchDataService::get(MetaTemplate::class, ['title', 'template'])
                             )->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function first(array $inputs = [], bool $any = true): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = MetaTemplate::query();
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
            return MetaTemplate::query()->create($inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(MetaTemplate $template, array $inputs = []): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($template, $inputs) {
            $template->update(['title' => $inputs['title']]);

            $this->attachElements($template, $inputs['elements'] ?? []);

            return $template->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(MetaTemplate $template): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($template) {
            $template->delete();
        })->run();
    }


    public function attachElements(MetaTemplate $template, array $elements = []): void
    {
        $template->elements()->detach();
        $model_type = $elements['model_type'];

        foreach ($elements['items'] ?? [] as $key => $element) {
            $template->elements()->attach($element['element_id'], [
                'model_type' => $model_type,
                'name'       => $element['name'],
                'title'      => $element['title'],
                'sort'       => $key,

            ]);
        }
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function getFiles(?string $path = null): ServiceResult
    {
        $models = config('meta.models', []);
        $files = [];
        foreach ($models as ['model' => $model, 'path' => $path]) {
            $dir = resource_path('views/' . $path);
            if (!File::isDirectory($dir)) return new ServiceResult(true, []);

            $files[$model]['model'] = $model;

            $files[$model]['pathes'] = collect(File::allFiles($dir))
                ->map(function ($file) use ($path) {

                    return Str::of($file->getRelativePathname())
                              ->prepend($path . '/')
                              ->after($path . DIRECTORY_SEPARATOR)
                              ->replace('\\', '/')
                              ->replaceLast('.php', '')
                              ->toString();
                })
                ->all();

        }

        return new ServiceResult(true, $files);
    }


    public function getUnregistered(?string $path = null) :array
    {
        $files = $this->getFiles($path)->result ?? [];

        if (empty($files)) return [];

        $unregistered = [];

        foreach ($files as ['pathes' => $pathes, 'model' => $model]) {
            foreach ($pathes as $path) {
                $isRegistered = MetaTemplate::query()->where(function ($q) use ($model, $path) {
                    $q->where('model_type', $model)->where('template', $path);

                })->exists();
                if ($isRegistered) continue;
                $unregistered[] = ['path' => $path, 'model' => $model];
            }
        }

        return $unregistered;

    }
}

