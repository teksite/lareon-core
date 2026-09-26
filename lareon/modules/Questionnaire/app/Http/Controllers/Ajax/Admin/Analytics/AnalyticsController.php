<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Ajax\Admin\Analytics;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Questionnaire\App\Logics\AnalyticInboxLogic;
use Teksite\Handler\Facade\Responder;

class AnalyticsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.questionnaire.inbox.export'),
        ];
    }

    public function __construct(public AnalyticInboxLogic $logic,) {}

    /**
     * @throws \Throwable
     */
    public function get(Request $request,)
    {
        $res = $this->logic->getForChart($request->input('fromDate'), $request->input('toDate'), $request->input('range', 'day'));
        return Responder::fromResult($res)->reply();
    }
}
