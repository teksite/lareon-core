<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Api\CheckUserApiRequest;
use Lareon\Modules\Auth\App\Http\Requests\Api\LoginApiRequest;
use Lareon\Modules\Auth\App\Services\AuthTokenService;
use Lareon\Modules\User\App\Http\Resources\UserResource;
use Lareon\Modules\User\App\Models\User;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Facade\Responder;


class CheckUserController extends Controller
{
    public function __construct() {}


    /**
     * @throws \Throwable
     */
    public function check(CheckUserApiRequest $request)
    {
       $user = $request->user;
       $result = !!$user ? trans('auth::messages.auth.user_exists') : trans('auth::messages.auth.user_not_found');
       return Responder::success($result ,[])->reply();

    }


    public function verifyingContactType(User|Authenticatable $user , ?ContactType $contactType = null, bool $overwrite = false, ?Carbon $date = null): void
    {
        if (is_null($contactType)) return;
        $date ??= Carbon::now();

        $way = $contactType->value . '_verified_at';
        if ($overwrite || is_null($user->$way)) {
            $user->forceFill([$way => $date])->save();
        }
    }
}
