<?php

namespace Lareon\Modules\Captcha\App\Http\Controllers\Ajax\Client\Captcha;

use InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lareon\Modules\Captcha\App\Http\Controllers\Controller;
use Lareon\Modules\Captcha\App\Services\CaptchaService;

class LocalCaptchaController extends Controller
{
    public function __construct(private readonly CaptchaService $captcha,) {}

    /**
     * Serve the image of an existing captcha (used as <img src>).
     */
    public function image(string $token,): Response
    {
        $jpeg = $this->captcha->image($token);

        abort_if($jpeg === null, 404);

        return response($jpeg, 200, [
            'Content-Type'           => 'image/jpeg',
            'Content-Disposition'    => 'inline; filename="captcha.jpg"',
            'Cache-Control'          => 'no-store, no-cache, must-revalidate, private',
            'Pragma'                 => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Create a fresh captcha (the "new code" button) and drop the old one.
     */
    public function reload(Request $request,): JsonResponse
    {
        abort_unless($request->ajax() || $request->expectsJson(), 404);

        $this->captcha->discard(is_string($request->query('old')) ? $request->query('old') : null);

        try {
            $challenge = $this->captcha->make((string)$request->query('preset', 'default'));
        } catch (InvalidArgumentException) {
            abort(404);
        }

        return response()->json([
            'message' => 'success',
            'data'    => $challenge,
            'code'    => 200,
        ], 200, ['Cache-Control' => 'no-store, private']);
    }
}
