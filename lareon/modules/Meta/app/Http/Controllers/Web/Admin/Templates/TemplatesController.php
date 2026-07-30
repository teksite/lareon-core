<?php

namespace Lareon\Modules\Meta\App\Http\Controllers\Web\Admin\Templates;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Meta\App\Http\Controllers\Controller;
use Lareon\Modules\Meta\App\Http\Requests\Admin\NewTemplateRequest;
use Lareon\Modules\Meta\App\Http\Requests\Admin\UpdateTemplateRequest;
use Lareon\Modules\Meta\App\Logics\MetaTemplateLogic;
use Lareon\Modules\Meta\App\Models\MetaTemplate;
use Teksite\Handler\Facade\Responder;

class TemplatesController extends Controller implements HasMiddleware
{

    public function __construct(public MetaTemplateLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.meta.template.read'),
            new Middleware('can:admin.meta.template.create', only: ['create', 'store']),
            new Middleware('can:admin.meta.template.edit', only: ['edit', 'update']),
            new Middleware('can:admin.meta.template.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Throwable
     */
    public function index()
    {
        $registered = $this->logic->all()->result;
        $unregistered = $this->logic->getUnregistered();

        return view('meta::admin.pages.templates.index', compact('registered' ,'unregistered'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->action([self::class , 'index']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Throwable
     */
    public function store(NewTemplateRequest $request)
    {
        $res = $this->logic->create($request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.created', ['attribute' => __('template')]),
            trans('lareon::global.crud.error.created', ['attribute' => __('template')]),
            route('admin.settings.meta.templates.index')
        )->go();
    }

    /**
     * Display the specified resource.
     */
    public function show(MetaTemplate $template)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MetaTemplate $template)
    {
        return view('meta::admin.pages.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateTemplateRequest $request, MetaTemplate $template)
    {
        $res = $this->logic->update($template, $request->validated());
        return Responder::fromResult($res,
            trans('lareon::global.crud.success.updated', ['attribute' => __('template')]),
            trans('lareon::global.crud.error.updated', ['attribute' => __('template')]),
            route('admin.settings.meta.templates.edit', $res->result)
        )->go();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(MetaTemplate $template)
    {
        $res = $this->logic->delete($template);

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.deleted', ['attribute' => __('template')]),
            trans('lareon::global.crud.error.deleted', ['attribute' => __('template')]),
            route('admin.settings.meta.templates.index')
        )->go();
    }
}
