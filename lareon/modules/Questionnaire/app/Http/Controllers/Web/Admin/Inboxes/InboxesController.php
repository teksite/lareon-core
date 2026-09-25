<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Inboxes;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Lareon\Modules\Questionnaire\App\Http\Requests\Admin\UpdateInboxRequest;
use Lareon\Modules\Questionnaire\App\Logics\InboxLogic;
use Lareon\Modules\Questionnaire\App\Models\FormInbox;
use Teksite\Handler\Facade\Responder;

class InboxesController extends Controller
{
    public function __construct(public InboxLogic $logic,) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.questionnaire.inbox.read'),
            new Middleware('can:admin.questionnaire.inbox.create', only: ['create', 'store']),
            new Middleware('can:admin.questionnaire.inbox.edit', only: ['edit', 'update']),
            new Middleware('can:admin.questionnaire.inbox.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Throwable
     */
    public function index()
    {
        $formId = request()->input('formId');
        $inboxes = $formId
            ? $this->logic->allByForm((int)$formId)->result
            : $this->logic->all()->result;

        $trashCount = $this->logic->trashCount()?->result;

        return view('questionnaire::admin.pages.inboxes.index', compact('inboxes', 'trashCount'));
    }

    /**
     * Show the inbox for creating a new resource.
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     */
    public function show(FormInbox $inbox,)
    {
        abort(404);
    }

    /**
     * Show the inbox for editing the specified resource.
     */
    public function edit(FormInbox $inbox,)
    {
        $inbox->markAsRead(auth('admin')->user());

        return view('questionnaire::admin.pages.inboxes.edit', compact('inbox'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateInboxRequest $request, FormInbox $inbox,)
    {
        $res = $this->logic->update($inbox, $request->validated());
        return Responder::fromResult($res, success_url: route('admin.questionnaire.inboxes.edit', $inbox))->go();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(FormInbox $inbox,)
    {
        $res = $this->logic->delete($inbox);
        return Responder::fromResult($res, success_url: route('admin.questionnaire.inboxes.index'))->go();
    }
}
