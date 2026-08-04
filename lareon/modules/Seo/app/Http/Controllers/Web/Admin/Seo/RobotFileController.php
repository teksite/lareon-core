<?php

namespace Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Seo\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Seo\App\Http\Requests\Admin\UpdateRobotRequest;
use Lareon\Modules\Seo\App\Logics\RobotLogic;
use Teksite\Handler\Facade\Responder;

class RobotFileController extends Controller implements HasMiddleware
{

    public function __construct(public RobotLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.seo.robot.edit', only: ['edit', 'update']),
        ];
    }

    public function edit()
    {
        $content=$this->logic->getContent()->result;
        return view('seo::admin.pages.robot_txt.edit', compact('content'));
    }

    public function update(UpdateRobotRequest $request)
    {
        $res = $this->logic->changeContent($request->validated());
        return Responder::fromResult($res)->go();
    }
}
