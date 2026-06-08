<?php
namespace Lareon\Modules\Auth\App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Lareon\Modules\Auth\App\Enums\ActionType;
use Lareon\Modules\Auth\App\Http\Requests\Api\Validations\AuthDataRequestTrait;
use Lareon\Modules\Auth\App\Http\Requests\Api\Validations\PasswordRequestTrait;
use Lareon\Modules\Auth\App\Http\Requests\Api\Validations\TokenCodeRequestTrait;
use Lareon\Modules\Auth\App\Http\Requests\Api\Validations\ActionTokenRequestTrait;
use Teksite\Module\Foundations\ApiFormRequest;

class BaseApiRequest extends ApiFormRequest
{
    use AuthDataRequestTrait , ActionTokenRequestTrait , TokenCodeRequestTrait , PasswordRequestTrait;
}
