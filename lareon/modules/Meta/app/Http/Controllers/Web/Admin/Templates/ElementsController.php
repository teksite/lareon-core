<?php

namespace Lareon\Modules\Meta\App\Http\Controllers\Web\Admin\Templates;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Meta\App\Http\Controllers\Controller;
use Lareon\Modules\Meta\App\Http\Requests\Admin\NewElementRequest;
use Lareon\Modules\Meta\App\Http\Requests\Admin\UpdateElementRequest;
use Lareon\Modules\Meta\App\Logics\MeteaElementLogic;
use Lareon\Modules\Meta\App\Models\MetaElement;
use Teksite\Handler\Facade\Responder;

class ElementsController extends Controller implements HasMiddleware
{

    public function __construct(public MeteaElementLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.meta.element.read'),
            new Middleware('can:admin.meta.element.create', only: ['create', 'store']),
            new Middleware('can:admin.meta.element.edit', only: ['edit', 'update']),
            new Middleware('can:admin.meta.element.delete', only: ['destroy']),
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
        $unregistered = $this->logic->getFiles()->result;


        return view('meta::admin.pages.elements.index', compact('registered' ,'unregistered'));
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
    public function store(NewElementRequest $request)
    {
        $res = $this->logic->create($request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.created', ['attribute' => __('element')]),
            trans('lareon::global.crud.error.created', ['attribute' => __('element')]),
            route('admin.settings.meta.elements.index')
        )->go();
    }

    /**
     * Display the specified resource.
     */
    public function show(MetaElement $element)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MetaElement $element)
    {
        return view('meta::admin.pages.elements.edit', compact('element'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateElementRequest $request, MetaElement $element)
    {
        $res = $this->logic->update($element, $request->validated());
        return Responder::fromResult($res,
            trans('lareon::global.crud.success.updated', ['attribute' => __('element')]),
            trans('lareon::global.crud.error.updated', ['attribute' => __('element')]),
            route('admin.settings.meta.elemetns.edit', $res->result)
        )->go();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(MetaElement $element)
    {
        $res = $this->logic->delete($element);

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.deleted', ['attribute' => __('element')]),
            trans('lareon::global.crud.error.deleted', ['attribute' => __('element')]),
            route('admin.settings.meta.elemetns.index', $res->result)
        )->go();
    }
}
