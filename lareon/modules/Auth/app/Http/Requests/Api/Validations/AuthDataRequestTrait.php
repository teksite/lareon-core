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

        $contact = $this->normalizeContact($this->input('contact'));

        if (!$contact) {
            $validator->errors()->add('contact', trans('auth::messages.auth.contact_wrong_pattern'));
            return;
        }

        $this->contactType = $contact['type'];
        $this->contactValue = $contact['value'];


        $this->actionType = ActionType::tryFrom($this->input('action'));
    }

    protected function resolveAltContactData(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $contact = $this->normalizeContact($this->input('contact_alt'));

        if (!$contact) {
            $validator->errors()->add('contact_alt', trans('auth::messages.auth.contact_wrong_pattern'));
            return;
        }

        if ($contact['type'] === $this->contactType) {
            $validator->errors()->add('contact_alt', trans('auth::messages.auth.alternative_contact_same', ['attribute' => $this->contactType?->value, 'alt_attribute' => $contact['type']->value,]));
            return;
        }

        $exists = User::query()->where($contact['type']->value, $contact['value'])->exists();

        if ($exists) {
            $validator->errors()->add('contact_alt', trans('auth::messages.auth.contact_is_used_before', ['attribute' => $contact['type']->value,]));
            return;
        }

        $this->contactAltType = $contact['type'];
        $this->contactAltValue = $contact['value'];
    }


    /**
     * Resolve user from contact.
     */
    protected function resolveUser(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $this->user = User::query()->firstWhere($this->contactType->value, $this->contactValue);
    }


    /**
     * Validate user existence conditions.
     */
    protected function checkExistenceContactCondition(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;
        if ($this->actionType === ActionType::REGISTER && $this->user) {
            $validator->errors()->add('contact', trans('auth::messages.auth.contact_is_used_before', ['attribute' => $this->contactType?->value,]));
            return;
        }
        if ($this->actionType === ActionType::LOGIN && !$this->user) {
            $validator->errors()->add('contact', trans('auth::messages.auth.user_not_found'));
        }
    }


    /**
     * Normalize phone/email.
     */
    private function normalizeContact(?string $contact): ?array
    {
        if (blank($contact))   return null;

        $type = ContactType::detect($contact);

        if (!$type)    return null;


        $value = match ($type) {
            ContactType::PHONE => MobilePatterns::canonical($contact, MobilePatterns::IRAN),
            ContactType::EMAIL => strtolower(trim($contact)),
        };

        if (blank($value))  return null;

        return [
            'type'  => $type,
            'value' => $value,
        ];
    }

}
