<?php

namespace Lareon\Modules\Meta\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Lareon\Modules\Meta\App\Models\MetaElement;
use Teksite\Handler\Actions\ServiceResult;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Services\FetchDataService;

class MeteaElementLogic
{
    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(
                                 fn() => FetchDataService::get(MetaElement::class, ['title', 'path'])
                             )->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function first(array $inputs = [], bool $any = true): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = MetaElement::query();
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
            return MetaElement::query()->create($inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(MetaElement $elements, array $inputs = []): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($elements, $inputs) {
            $elements->update($inputs);
            return $elements->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(MetaElement $elements): ServiceResult
    {
        return ServiceWrapper::make(false)->do(function () use ($elements) {
            $elements->delete();
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
}

