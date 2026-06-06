<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Ajax\Auth;

use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Http\Requests\Auth\OTP\SendOtpAjaxRequest;
use Services\OtpService;
use Services\SendOtpService;
use Teksite\Handler\Facade\Responder;

class VerificationCodeController extends Controller
{
    public function __construct(protected OtpService $otpService , protected SendOtpService $sendService) {}

    public function send(SendOtpAjaxRequest $request)
    {
        $validated = $request->validated('contactType');
        $gateway = $validated['contactType'];
        $action = $validated['action'];
        $user = $request->user;
        
        if ($gateway === 'phone') {
            $codeDate = $this->otpService->generate( $user->phone, $action);
            $res = $this->sendService->viaSMS($user->phone, $codeDate['code'], $action, $codeDate['expire_at']);
        }else if ($gateway === 'email') {
            $codeDate = $this->otpService->generate( $user->email, $action);
            $res = $this->sendService->viaEmail($user->email, $codeDate['code'], $action, $codeDate['expire_at']);
        }else{
            return Responder::failed(trans('lareon::errors.server_validation_error'));
        }

        if ($res){
            return Responder::success(trans('lareon::global.crud.success.general'));
        }else{
            return Responder::success(trans('lareon::global.crud.error.general'));

        }
    }
    public function verify(Request $request) {}
}
