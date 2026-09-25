<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Client\Inboxes;

use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Questionnaire\App\Http\Requests\NewSubmitRequest;
use Lareon\Modules\Questionnaire\App\Logics\InboxLogic;
use Teksite\Handler\Facade\Responder;

class SubmitController extends Controller
{
    public function __construct(public InboxLogic $logic)
    {
    }

    public function store(NewSubmitRequest $request)
    {
        $res = $this->logic->create($request->form, $request->validated());
        return Responder::fromResult($res)->go();
    }
}
