<?php

namespace Lareon\Modules\Captcha\App\Http\Controllers\Ajax\Client\Captcha;

use Lareon\Modules\Captcha\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Captcha\App\Services\Facade\Captcha;

class LocalCaptchaController extends Controller
{
    /**
     * @throws \Exception
     */
    public function getCaptcha(Captcha $captcha, string $config = 'custom')
    {
        if (request()->ajax()) abort(404);
        if (ob_get_contents()) {
            ob_clean();
        }
        return Captcha::create($config);
    }

    public function reload(Request $request, $config = 'custom')
    {
        if (!$request->ajax()) abort(404);
        if (ob_get_contents()) {
            ob_clean();
        }

        return response()->json([
            'message' => 'success',
            'data' => Captcha::src($config),
            'code' => 200
        ])->setStatusCode(200);
    }
}
