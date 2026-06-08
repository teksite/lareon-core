<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Ajax\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Http\Requests\Auth\OTP\SendOtpAjaxRequest;
use Lareon\Modules\Auth\App\Http\Requests\Auth\OTP\VerifyOtpAjaxRequest;
use Lareon\Modules\Auth\App\Services\OtpService;
use Lareon\Modules\Auth\App\Services\SendOtpService;
use Teksite\Handler\Facade\Responder;

class VerificationCodeController extends Controller
{
    public function __construct(protected StatefulGuard $guard , protected OtpService $otpService, protected SendOtpService $sendService) {}

    public function send(SendOtpAjaxRequest $request)
    {
        $contact = $request->contact;
        $actionType = $request->actionType;
        $contactType = $request->contactType;
        $codeDate = $this->otpService->generate($contact, $actionType);

        $res = false;
        if ($contactType === ContactType::PHONE) {
            $res = $this->sendService->viaSMS($contact, $codeDate['code'], $actionType, $codeDate['expires_at']);
        } elseif ($contactType === ContactType::EMAIL) {
            $res = $this->sendService->viaEmail($contact, $codeDate['code'], $actionType, $codeDate['expires_at']);
        }
        return $res ? Responder::success(trans('auth::messages.verification_code.sent_successfully', ['attribute' => __($contactType->value)]))->reply() : Responder::failed(trans('auth::messages.otp.verification_code.sent_failed', ['attribute' => __($contactType->value)]))->reply();
    }

    public function verify(VerifyOtpAjaxRequest $request) {

        $this->guard->login($request->user, $request->remember());

        $request->session()->regenerate();

        return app(TwoFactorLoginResponse::class);
    }
}
