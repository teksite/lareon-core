<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Ajax\Admin\Analytics;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function get(Request $request,)
    {
        $res = $this->logic->getForChart($request->get('fromDate'), $request->get('toDate'), $request->get('range', 'day'));
        return Responder::fromResult($res)->reply();
    }
}
