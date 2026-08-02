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
        foreach ($elements['items'] ?? [] as $key => $element) {
            $template->elements()->attach($element['element_id'], [

                'name'     => $element['name'],
                'title'    => $element['title'],
                'settings' => [
                    'arguments'=> $element['args'] ?? [],
                ],
                'sort'     => $key,

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
        foreach ($models as $key => ['model' => $model, 'path' => $path]) {
            $dir = resource_path('views/' . $path);
            if (!File::isDirectory($dir)) return new ServiceResult(true, []);

            $files[$key] = collect(File::allFiles($dir))
                ->map(function ($file) use ($path) {

                    return Str::of($file->getRelativePathname())
                              ->prepend($path . '/')
                              ->after($path . DIRECTORY_SEPARATOR)
                              ->replace('\\', '/')
                              ->replaceLast('.blade.php', '')
                              ->toString();
                })
                ->all();

        }

        return new ServiceResult(true, $files);
    }


    public function getUnregistered(?string $path = null): array
    {
        $files = $this->getFiles($path)->result ?? [];

        if (empty($files)) return [];

        $unregistered = [];

        foreach ($files as $key => $files) {
            foreach ($files as $file) {

                $isRegistered = MetaTemplate::query()->where(function ($q) use ($file, $key) {
                    $q->where('model_type', $key)->where('template', $file);

                })->exists();
                if ($isRegistered) continue;
                $unregistered[$key][] = $file;
            }
        }
        return $unregistered;
    }
}

