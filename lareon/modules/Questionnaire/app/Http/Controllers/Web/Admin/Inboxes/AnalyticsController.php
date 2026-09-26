<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Inboxes;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Lareon\Modules\Questionnaire\App\Logics\AnalyticInboxLogic;

class AnalyticsController extends Controller implements HasMiddleware
{
    public function __construct(public AnalyticInboxLogic $logic)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.questionnaire.inbox.read')
        ];
    }

    public function show()
    {
        return view('questionnaire::admin.pages.analytics.index');
    }
}
