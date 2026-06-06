<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Ajax\Auth;

use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Http\Requests\Auth\OTP\SendOtpAjaxRequest;
use Services\OtpService;
use Teksite\Handler\Facade\Responder;

class VerificationCodeController extends Controller
{
    public function __construct(protected OtpService $service) {}

    public function send(SendOtpAjaxRequest $request)
    {
        $validated = $request->validated('contactType');
        $gateway = $validated['contactType'];
        $action = $validated['action'];
        $user = $request->user;

        $to = match ($gateway) {
            'phone' => $user->phone,
            'email' => $user->email,
            default => null
        };

        if ($to) {
            $codeDate = $this->service->generate($to, $action);
            if ($contactType === ContactType::PHONE) {
                $res = $this->sendService->viaSMS($to, $code['code'], $actionType, $code['expire_at']);
            } elseif ($contactType === ContactType::EMAIL) {
                $res = $this->sendService->viaEmail($to, $code['code'], $actionType, $code['expire_at']);
            }
        }


        return Responder::failed(trans('lareon::errors.server_validation_error'));

    }


    public function verify(Request $request) {}
}
