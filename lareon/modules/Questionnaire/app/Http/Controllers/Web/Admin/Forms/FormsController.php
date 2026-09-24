<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Forms;

use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Questionnaire\App\Http\Requests\Admin\NewFormRequest;
use Lareon\Modules\Questionnaire\App\Http\Requests\Admin\UpdateFormRequest;
use Lareon\Modules\Questionnaire\App\Logics\FormLogic;
use Lareon\Modules\Questionnaire\App\Models\Form;
use Teksite\Handler\Facade\Responder;

class FormsController extends Controller
{
    public function __construct(public FormLogic $logic,) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.questionnaire.form.read'),
            new Middleware('can:admin.questionnaire.form.create', only: ['create', 'store']),
            new Middleware('can:admin.questionnaire.form.edit', only: ['edit', 'update']),
            new Middleware('can:admin.questionnaire.form.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = $this->logic->get()->result;
        $count = $this->logic->trashCount()?->result;

        return view('questionnaire::admin.pages.forms.index', compact('forms', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('questionnaire::admin.pages.forms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewFormRequest $request,)
    {
        $res = $this->logic->register($request->validated());
        return Responder::fromResult($res, route('admin.questionnaire.forms.edit', $res->result))->go();
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form,)
    {
        $inboxes = $this->logic->getInboxes($form)->result;
        $count = $this->logic->trashCount()?->result;

        return view('questionnaire::admin.pages.forms.inbox', compact('form', 'inboxes', 'count'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form,)
    {
        return view('questionnaire::admin.pages.forms.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, Form $form,)
    {
        $res = $this->logic->change($request->validated(), $form);
        return Responder::fromResult($res, route('admin.questionnaire.forms.edit', $form))->go();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form,)
    {
        $res = $this->logic->delete($form);
        return Responder::fromResult($res, route('admin.questionnaire.forms.index'))->go();
    }
}
