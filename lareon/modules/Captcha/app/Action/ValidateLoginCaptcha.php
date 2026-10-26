<?php

namespace Lareon\Modules\Captcha\App\Action;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Lareon\Modules\Captcha\App\Rules\CaptchaRule;

class ValidateLoginCaptcha {
    public function __invoke(Request $request, Closure $next): mixed
    {
        $validator = Validator::make($request->all(), [
            'captcha' => ['required', new CaptchaRule()],
        ]);

        if ($validator->fails())  throw new ValidationException($validator);

        return $next($request);
    }
}
