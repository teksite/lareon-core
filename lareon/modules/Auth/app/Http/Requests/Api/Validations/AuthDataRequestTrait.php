<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Api\Validations;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\Validator;
use Lareon\Modules\Auth\App\Enums\ActionType;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\User\App\Models\User;
use Teksite\Extralaravel\Enums\MobilePatterns;

trait AuthDataRequestTrait
{
    public User|Authenticatable|null $user = null;
    public ActionType|null $actionType = null;

    public ContactType|null $contactType = null;
    public string|null $contactValue = null;

    public ContactType|null $contactAltType = null;
    public string|null $contactAltValue = null;


    /**
     * @param Validator $validator
     * @return void
     */
    protected function resolveContactData(Validator $validator): void
    {

        if ($validator->errors()->isNotEmpty()) return;

        $contact= $this->input('contact');

        $contactType = ContactType::detect($contact);

        if (!$contactType) {
            $validator->errors()->add( 'contact', trans('auth::messages.auth.contact_wrong_pattern') );
            return;
        }


        $this->contactType = $contactType;

        if ($contactType === ContactType::PHONE) {
            $contactValue = MobilePatterns::canonical($contact, MobilePatterns::IRAN);
            if ($contactValue === null) {
                $validator->errors()->add('contact', trans('auth::messages.auth.contact_phone_pattern'));
                return;
            };


        }
        if ($contactType === ContactType::EMAIL) {
            $contactValue = strtolower($contact);
        }

        $this->contactValue = $contactValue;

        $this->actionType = ActionType::tryFrom($this->input('action'));
    }


    protected function resolveUser(Validator $validator): void
    {
        $this->user = User::query()->firstWhere($this->contactType->value, $this->contactValue);
    }


    /**
     * @param Validator $validator
     * @return void
     */
    protected function checkExistenceContactCondition(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $user = $this->user;

        if ($this->actionType === ActionType::REGISTER && $user) {
            $validator->errors()->add($this->contactType->value, trans('auth::messages.auth.contact_is_used_before', ['attribute' => $this->contactType->value]));
            return;
        }

        if ($this->actionType === ActionType::LOGIN->value && !$user) {
            $validator->errors()->add($this->actionType, trans('auth::messages.auth.user_not_found'));
            return;
        }
    }





/*
    protected function appendAltContactData(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        if (is_null($this->contactType) || is_null($this->contactValue)) {
            $validator->errors()->add('overall', trans('auth::messages.auth.troubles'));
            return;
        }

        $contactAltType = $this->contactType === ContactType::PHONE ? ContactType::EMAIL : ContactType::PHONE;

        $existenceUserByAltContact = User::query()->where($contactAltType->value, $this->contactValue)->exists();
        if ($existenceUserByAltContact) {
            $validator->errors()->add($contactAltType->value, trans('auth::messages.auth.contact_is_used_before', ['attribute' => $contactAltType->value]));
            return;
        }

        $this->contactAltType = $contactAltType;
        $this->contactAltValue = NormalizeContact::handle($this->input('contact_alt'));
        return;
    }*/



}
