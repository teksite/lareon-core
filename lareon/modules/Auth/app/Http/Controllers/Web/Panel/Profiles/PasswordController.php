<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Panel\Profiles;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\CMS\App\Http\Controllers\Controller;
use Lareon\CMS\App\Http\Requests\Panel\UpdatePassword;
use Lareon\CMS\App\Logic\UserLogic;
use Lareon\CMS\App\Logic\UserMetaLogic;
use Teksite\Lareon\Facade\WebResponse;

class PasswordController extends Controller implements HasMiddleware
{
    public function __construct(public UserLogic $logic)
    {

    }

    public static function middleware()
    {
        return [
            new Middleware('can:panel.profile.edit'),

        ];
    }

    public function edit()
    {
        return view('lareon::panel.pages.profiles.change-password');
    }

    public function update(UpdatePassword $request)
    {
        $user=auth()->user();
        $res=$this->logic->changePassword($user , $request->validated());
        return WebResponse::byResult($res, route('panel.profile.edit', $user))->go();

    }
}
