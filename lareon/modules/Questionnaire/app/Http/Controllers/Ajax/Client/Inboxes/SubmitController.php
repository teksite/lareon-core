<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Ajax\Client\Inboxes;

use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Questionnaire\App\Http\Requests\ApiNewSubmitRequest;
use Lareon\Modules\Questionnaire\App\Logics\InboxLogic;
use Teksite\Handler\Facade\Responder;

class SubmitController extends Controller
{
    public function __construct(public InboxLogic $logic)
    {
    }

    /**
     * @throws \Throwable
     */
    public function store(ApiNewSubmitRequest $request)
    {
        $res = $this->logic->create($request->form, $request->validated());
        return Responder::fromResult($res)->reply();
    }
}
