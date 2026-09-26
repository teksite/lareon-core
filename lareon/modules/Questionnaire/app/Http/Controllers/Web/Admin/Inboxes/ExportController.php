<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Inboxes;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Questionnaire\App\Http\Requests\Admin\ExportInboxRequest;
use Lareon\Modules\Questionnaire\App\Logics\ExportInboxLogic;
use Lareon\Modules\Questionnaire\App\Models\Form;

class ExportController extends Controller implements HasMiddleware
{
    public function __construct(public ExportInboxLogic $logic)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.questionnaire.inbox.export'),
        ];
    }


    public function index()
    {
        $forms = Form::all();
        return view('questionnaire::admin.pages.export.index', compact('forms'));
    }


    /**
     * @throws \Throwable
     */
    public function export(ExportInboxRequest $request)
    {
        $res = $this->logic->export($request->validated());
        return $res->result;
    }
}
