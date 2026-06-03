<?php

namespace Lareon\Modules\Auth\App\Actions\Fortify;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Lareon\Modules\User\App\Events\UserCrudEvent;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Teksite\Handler\Facade\Responder;

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
        $data = Validator::make($input, User::rules('create'))->validate();

        $res = (new UserLogic())->create($data);

        if ($res->success) {
            return $res->result;
        }
        return null;
    }
}
