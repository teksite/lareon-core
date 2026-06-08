<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Http\Requests\Api\RegisterApiRequest;
use Lareon\Modules\Auth\App\Http\Requests\Api\SendVerificationCodeApiRequest;
use Lareon\Modules\Auth\App\Services\ActionTokenService;
use Lareon\Modules\Auth\App\Services\OtpService;
use Lareon\Modules\Auth\App\Services\SendOtpService;
use Lareon\Modules\User\App\Logics\UserLogic;
use Teksite\Handler\Facade\Responder;


class TokenController extends Controller
{
    public function __construct(protected OtpService $otpService) {}


    /**
     * @throws \Throwable
     */
    public function send(SendVerificationCodeApiRequest $request)
    {

        $contact = $request->contactValue;
        $contactType=$request->contactType;
        $actionType=$request->actionType;
        $codeData= $this->otpService->generate($contact, $actionType, );
        if ($codeData === false) {
            Responder::failed('steward::error.server_error_unknown' ,)->reply();
        }

        $res = false;
        $sendService = new SendOtpService;
        if ($contactType === ContactType::PHONE) {
            $res = $sendService->viaSMS($contact, $codeData['code'], $actionType, $codeData['expires_at']);
        } elseif ($contactType === ContactType::EMAIL) {
            $res = $sendService->viaEmail($contact, $codeData['code'], $actionType, $codeData['expires_at']);
        }

        return $res ? Responder::success( trans('auth::messages.verification_code.sent_successfully' , ['attribute'=>__($contactType->value)]))->reply() : Responder::Failed('auth::messages.verification_code.sent_failed')->reply();


    }


    public function verify(RegisterApiRequest $request)
    {

    }

}
