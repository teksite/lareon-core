<?php

namespace Lareon\Modules\Gadget\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Lareon\Modules\Gadget\App\Models\Gadget;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class GadgetLogic
{
    use HasTrashLogic;

    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get(Gadget::class, ['title', 'label',]),
        )->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function first(array $inputs = [], bool $any = true,): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = Gadget::query();
            foreach ($inputs as $key => $value) {
                $query->where($key, $value);
            }
            return $query->first();
        })->run();
    }


    /**
     * @throws \Throwable
     */
    public function load(array $inputs,)
    {
        $label = $inputs['attributes']['id'];
        return View::exists("widgets.$label") ? view("widgets.$label", compact('inputs'))->render() : Gadget::query()->firstWhere('label', $label)?->body;
    }

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            return Gadget::query()->create($inputs);
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Gadget $gadget, array $inputs = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($gadget, $inputs) {
            $gadget->update($inputs);
            $gadget->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Gadget $gadget,): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($gadget) {
            return $gadget->delete();
        })->run();
    }

    protected function getModelClass(): string
    {
        return Gadget::class;
    }

}

