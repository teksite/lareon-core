<?php

namespace Lareon\Modules\Auth\App\Actions\Fortify;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Lareon\Modules\Captcha\App\Rules\CaptchaRule;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Modules\User\App\Models\User;

class CreateNewUser implements CreatesNewUsers
{

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     *
     * @throws ValidationException
     * @throws \Throwable
     */
    public function create(array $input): ?User
    {
        $data = Validator::make($input, array_merge(User::rules('create'), ['captcha' => [new CaptchaRule()]]))->validate();
        $res = (new UserLogic())->create(Arr::except($data, ['captcha']));

        if ($res->success) return $res->result;

        return null;
    }
}
