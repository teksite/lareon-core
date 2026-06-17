<?php

namespace Lareon\Steward\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;
use Teksite\Handler\Actions\ServiceResult;
use Teksite\Handler\Actions\ServiceWrapper;


class LogLogic
{
    private const string LOG_EXTENSION = 'log';
    private const string LOG_DIRECTORY = 'logs';


    private function getLogPath(?string $name): string
    {
        return $name
            ? storage_path(self::LOG_DIRECTORY . DIRECTORY_SEPARATOR . $name )
            : storage_path(self::LOG_DIRECTORY);
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function getLogFiles(): ServiceResult
    {
        return ServiceWrapper::make(hasTransaction: false)->do(
            fn() => collect(File::files($this->getLogPath(null)))
                ->filter(fn($file) => $file->isFile() && $file->getExtension() === self::LOG_EXTENSION)
                ->map(fn(SplFileInfo $file) => $file->getFilename())
            ->toArray()
        )->run();
    }

    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function getLogContent(string $name = 'laravel'): ServiceResult
    {
        return ServiceWrapper::make(hasTransaction: false)->do(function () use ($name) {
            $path = $this->getLogPath($name);
            return file_exists($path) ? file_get_contents($path) : '';
        })->run();
    }


    public function clearContent(string $name): ServiceResult
    {
        return ServiceWrapper::make(hasTransaction: false)->do(function () use ($name) {
            $path = $this->getLogPath($name);
            if (file_exists($path)) file_put_contents($path, '');
        })->run();
    }


    public function delete(string $name): ServiceResult
    {
        return ServiceWrapper::make(hasTransaction: false)->do(function () use ($name) {
            $path = $this->getLogPath($name);
            if (file_exists($path)) File::delete($path);
        })->run();
    }


}


