<?php

namespace Lareon\Modules\Questionnaire\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Questionnaire\App\Models\Form;
use Lareon\Modules\Questionnaire\App\Models\FormInbox;
use Lareon\Steward\App\Traits\HasTrashLogic;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class InboxLogic
{
    use HasTrashLogic;

    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = [],)
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get(FormInbox::class, ['form.title',], withCount: ['form']),
        )->run();
    }

    /**
     * @throws \Throwable
     */
    public function allByForm(Form|int $form, mixed $fetchData = [],)
    {
        return ServiceWrapper::make(false)->do(
            fn() => FetchData::get($form->inbox(), ['id',]),
        )->run();
    }


    /**
     * @throws \Throwable
     */
    public function first(array $inputs = [], bool $any = true,): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = FormInbox::query();
            foreach ($inputs as $key => $value) {
                $query->where($key, $value);
            }
            return $query->first();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function create(Form $form, array $inputs = [],)
    {
        return ServiceWrapper::make(true)->do(function () use ($form, $inputs) {

            //Todo add Upload file if ($form->has_file && isset($inputs['file']))

            $honeypotField = config('extralaravel.honeypot.field_name', 'honeypot');
            $data = Arr::except($inputs, ['data_info', $honeypotField, 'g-recaptcha-response']);
            $ip = $inputs['data_info']['ip_address'] ?? request()->ip();
            $pageUrl = $inputs['data_info']['url'] ?? request()->fullUrl();

            $inbox = $form->inbox()->create([
                'data'       => $data,
                'ip_address' => $ip,
                'url'        => $pageUrl,
            ]);

            return ['form' => $form, 'inbox' => $inbox];
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(FormInbox $inbox, array $inputs = [],)
    {
        return ServiceWrapper::make(true)->do(function () use ($inbox, $inputs) {
            $preNote = $inbox->note;
            $newNote = [
                'author' => auth()->user()->name.' '.auth()->id(),
                'note'   => $inputs['note'],
            ];

            $inbox->note = array_push($preNote, $newNote);
            $inbox->save();

            return $inbox;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(FormInbox $inbox,)
    {
        return ServiceWrapper::make(true)->do(function () use ($inbox) {
            return $inbox->delete();
        })->run();
    }

    protected function getModelClass(): string
    {
        return FormInbox::class;
    }


}

