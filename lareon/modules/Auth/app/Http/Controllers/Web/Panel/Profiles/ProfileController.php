<?php

namespace Lareon\CMS\App\Http\Controllers\Web\Panel\Profiles;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\CMS\App\Http\Controllers\Controller;
use Lareon\CMS\App\Http\Requests\Panel\ProfileRequest;
use Lareon\CMS\App\Logic\UserLogic;
use Lareon\CMS\App\Logic\UserMetaLogic;
use Teksite\Handler\Actions\ServiceResult;
use Teksite\Lareon\Facade\WebResponse;

class ProfileController extends Controller implements HasMiddleware
{

    public function __construct(public UserLogic $logic )
    {

    }

    public static function middleware()
    {
        return [
            new Middleware('can:panel.profile.edit'),

        ];
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user=auth()->user();

        return view('lareon::panel.pages.profiles.edit', compact('user'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request)
    {
        $user=auth()->user();
        $res = $this->logic->change($request->validated() , auth()->user());
        return WebResponse::byResult($res, route('panel.profile.edit', $user))->go();
    }
}
