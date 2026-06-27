<?php

namespace Lareon\Modules\User\App\Http\Controllers\Web\Panel\Profile;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Laravel\Passkeys\Passkey;
use Lareon\Modules\User\App\Http\Controllers\Controller;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Modules\User\App\Models\User;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Facade\Responder;

class PasskeyController extends Controller implements HasMiddleware
{
    public User|Authenticatable $user;

    public function __construct(public UserLogic $logic)
    {
        $this->user = auth()->user();
    }

    public static function middleware()
    {
        return [
            new Middleware('can:panel.profile.passkey'),
        ];
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function index()
    {
        $passkeys = $this->user->passkeys;
        return view('user::panel.pages.profile.passkey', ['user' => $this->user, 'passkeys' => $passkeys]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Passkey $passkeys)
    {
        $res = ServiceWrapper::make()->do(fn() => $passkeys->delete());
        return Responder::fromResult($res)->go();
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function destroy(Passkey $passkeys)
    {
        $res = ServiceWrapper::make()->do(fn() => $passkeys->delete())->run();
        return Responder::fromResult($res)->go();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function flush()
    {
        $res = ServiceWrapper::make()->do(fn() => $this->user->passkeys()->delete());
        return Responder::fromResult($res)->go();
    }
}
