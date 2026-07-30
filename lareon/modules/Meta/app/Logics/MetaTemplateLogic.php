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
    public function update(MetaTemplate $templates, array $inputs = []): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($templates, $inputs) {
            $templates->update(['title'=>$inputs['title']]);
            return $templates->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(MetaTemplate $templates): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($templates) {
            $templates->delete();
        })->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function getFiles(?string $path = null): ServiceResult
    {
        $path ??= modulePath('meta', 'resources/views/components/editor', true);

        if (!File::isDirectory($path)) return new ServiceResult(true, []);


        $files = collect(File::allFiles($path))
            ->map(function ($file) use ($path) {
                return Str::of($file->getPathname())
                          ->after($path . DIRECTORY_SEPARATOR)
                          ->replace('\\', '/')
                          ->replaceLast('.php', '')
                          ->toString();
            })
            ->values()
            ->all();

        return new ServiceResult(true, $files);
    }


    public function getUnregistered(?string $path = null)
    {
        $files= $this->getFiles($path)->result ??[];
        $registeredPath=MetaTemplate::query()->select('template')->get()->pluck('template')->toArray();

        return array_diff($files, $registeredPath);
    }
}

