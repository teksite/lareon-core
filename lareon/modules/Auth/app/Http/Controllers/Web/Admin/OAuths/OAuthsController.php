<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\OAuths;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Http\Requests\Admin\UpdateOAuthRequest;
use Lareon\Modules\Auth\App\Logics\OAuthLogic;
use Teksite\Handler\Facade\Responder;

class OAuthsController extends Controller implements HasMiddleware
{
    public function __construct(public OauthLogic $logic)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.setting.edit'),
        ];
    }

    public function edit()
    {
        $data = $this->logic->getSettings()->result?->value ?? [];
        return view('auth::admin.pages.oauth.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateOAuthRequest $request)
    {
        $result = $this->logic->updateSetting($request->validated());
        return Responder::fromResult($result)->go();
    }
}
