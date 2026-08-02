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

class MetaElementLogic
{
    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(
                                 fn() => FetchDataService::get(MetaElement::class, ['title', 'element'])
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
            $elements->update([
                'title'    => $inputs['title'],
                'settings' => $inputs['settings'] ?? [],
            ]);
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
        $config = config('meta.elements');
        $module = $config['modules'] ?? 'meta';
        $pathModule = $config['path'] ?? 'resources/views/components/editor/extra';
        $path ??= modulePath($module, $pathModule, true);

        if (!File::isDirectory($path)) return new ServiceResult(true, []);


        $files = collect(File::allFiles($path))
            ->map(function ($file) use ($path) {
                return Str::of($file->getPathname())
                          ->after($path . DIRECTORY_SEPARATOR)
                          ->replace('\\', '/')
                          ->replaceLast('.blade.php', '')
                          ->toString();
            })
            ->values()
            ->all();

        return new ServiceResult(true, $files);
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function getElementPath(string $element, ?string $path = null): ServiceResult
    {
        $config = config('meta.elements');
        $module = $config['modules'] ?? 'meta';
        $pathModule = $config['path'] ?? 'resources/views/components/editor/extra';
        $path ??= modulePath($module, $pathModule, true);

        $element = trim($element, '/');

        $file = $path . DIRECTORY_SEPARATOR . $element . '.php';

        if (!File::exists($file)) return new ServiceResult(false, null);

        return new ServiceResult(true, $file);
    }

    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function getElementView(string $element): ServiceResult
    {
        $base = modulePath('meta', 'resources/views/components/editor/extra', true);

        $file = $base . DIRECTORY_SEPARATOR . $element . '.blade.php';

        if (!File::exists($file)) return new ServiceResult(false, null);

        $element = str_replace('/', '.', $element);

        return new ServiceResult(true, 'meta::components.editor.extra.' . $element);
    }


    public function getUnregistered(?string $path = null): array
    {
        $files = $this->getFiles($path)->result ?? [];
        $registeredPath = MetaElement::query()->select('element')->get()->pluck('element')->toArray();

        return array_diff($files, $registeredPath);
    }

    /**
     * @throws \Throwable
     */
    public function list(): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(
                                 fn() => MetaElement::query()->select(['id', 'title', 'settings'])->get()
                             )->run();
    }

}

