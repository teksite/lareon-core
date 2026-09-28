<?php

namespace Lareon\Modules\Gadget\App\Http\Controllers\Web\Admin\Gadgets;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Gadget\App\Http\Requests\Admin\NewGadgetRequest;
use Lareon\Modules\Gadget\App\Http\Requests\Admin\UpdateGadgetRequest;
use Lareon\Modules\Gadget\App\Logics\GadgetLogic;
use Lareon\Modules\Gadget\App\Models\Gadget;
use Lareon\Modules\Gadget\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Teksite\Handler\Facade\Responder;

class GadgetsController extends Controller implements HasMiddleware
{

    public function __construct(public GadgetLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.gadget.read'),
            new Middleware('can:admin.gadget.create', only: ['create', 'store']),
            new Middleware('can:admin.gadget.edit', only: ['edit', 'update']),
            new Middleware('can:admin.gadget.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Throwable
     */
    public function index()
    {
        $gadgets = $this->logic->all()->result;
        $trashCount = $this->logic->trashCount()->result;
        return view('page::admin.pages.gadgets.index', compact('gadgets', 'trashCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gadget= new Gadget();
        return view('page::admin.pages.gadgets.create' , compact('gadget'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Throwable
     */
    public function store(NewGadgetRequest $request)
    {
        $res = $this->logic->create($request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.created', ['attribute' => __('gadget')]),
            trans('lareon::global.crud.error.created', ['attribute' => __('gadget')]),
            route('admin.visual.gadgets.edit', $res->result)
        )->go();

    }

    /**
     * Display the specified resource.
     */
    public function show(Gadget $gadget)
    {
      abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gadget $gadget)
    {
        return view('page::admin.pages.gadgets.edit', compact('gadget'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateGadgetRequest $request, Gadget $gadget)
    {
        $res = $this->logic->update($gadget, $request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.updated', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.updated', ['attribute' => __('page')]),
        )->go();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(Gadget $page)
    {
        $res = $this->logic->delete($page);

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.deleted', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.deleted', ['attribute' => __('page')]),
            route('admin.visual.gadgets.index', $res->result)
        )->go();
    }
}
