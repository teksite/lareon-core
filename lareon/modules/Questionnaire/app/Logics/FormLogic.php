<?php

namespace Lareon\Modules\Questionnaire\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Questionnaire\App\Models\Form;
use Lareon\Steward\App\Service\ContentSaverService;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class FormLogic
{
    use HasTrashLogic;

    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = [],)
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get(Form::class, ['title', 'active'], withCount: ['inbox']),
        )->run();
    }

    /**
     * @throws \Throwable
     */
    public function first(array $inputs = [], bool $any = true,): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = Form::query();
            foreach ($inputs as $key => $value) {
                $query->where($key, $value);
            }
            return $query->first();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = [],)
    {
        return ServiceWrapper::make(true)->do(function () use ($inputs) {

            $inputs = $this->CheckArr($inputs);

            $form = Form::query()->create(Arr::except($inputs, ['rules', 'announcements']));
            $form->validationRules()->create(['rules' => $inputs['rules'] ?? []]);
            $form->announcement()->create($inputs['announcements'] ?? []);
            return $form;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Form $form, array $inputs = [],)
    {
        return ServiceWrapper::make(true)->do(function () use ($form, $inputs) {
            $inputs = $this->CheckArr($inputs);

            $form->update(Arr::except($inputs, ['rules', 'announcements']));
            $form->validationRules()->updateOrCreate(['form_id' => $form->id], ['rules' => $inputs['rules'] ?? []]);
            $form->announcement()->updateOrCreate(['form_id' => $form->id], $inputs['announcements'] ?? []);
            return $form;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Form $form,)
    {
        return ServiceWrapper::make(true)->do(function () use ($form) {
            return $form->delete();
        })->run();
    }

    protected function getModelClass(): string
    {
        return Form::class;
    }

    /**
     * @param array $inputs
     * @return array
     */
    private function CheckArr(array $inputs,): array
    {
        $inputs['active'] = isset($inputs['active']) && $inputs['active'] == 1;
        $inputs['has_file'] = isset($inputs['has_file']) && $inputs['has_file'] == 1;
        $inputs['response_client'] = isset($inputs['response_client']) && $inputs['response_client'] == 1;
        return removeNullValues($inputs);
    }

    /**
     * @throws \Throwable
     */
    public function getFormInboxes(Form $form, mixed $fetchData = [],)
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get($form->inbox()),
        )->run();
    }

}

