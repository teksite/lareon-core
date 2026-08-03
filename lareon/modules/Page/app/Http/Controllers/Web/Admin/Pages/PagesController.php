<?php

namespace Lareon\Modules\Page\App\Http\Controllers\Web\Admin\Pages;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Page\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Page\App\Http\Requests\Admin\NewPageRequest;
use Lareon\Modules\Page\App\Http\Requests\Admin\UpdatePageRequest;
use Lareon\Modules\Page\App\Logics\PageLogic;
use Lareon\Modules\Page\App\Models\Page;
use Teksite\Handler\Facade\Responder;

class PagesController extends Controller implements HasMiddleware
{

    public function __construct(public PageLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.page.read'),
            new Middleware('can:admin.page.create', only: ['create', 'store']),
            new Middleware('can:admin.page.edit', only: ['edit', 'update']),
            new Middleware('can:admin.page.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Throwable
     */
    public function index()
    {
        $pages = $this->logic->all()->result;
        $trashCount = $this->logic->trashCount()->result;

        return view('page::admin.pages.pages.index', compact('pages', 'trashCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page::admin.pages.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Throwable
     */
    public function store(NewPageRequest $request)
    {
        $res = $this->logic->create($request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.created', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.created', ['attribute' => __('page')]),
            route('admin.pages.edit', $res->result)
        )->go();

    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return $page->path();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {

        return view('page::admin.pages.pages.edit', compact('page'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $res = $this->logic->update($page, $request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.updated', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.updated', ['attribute' => __('page')]),
            route('admin.pages.edit', $res->result)
        )->go();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(Page $page)
    {
        $res = $this->logic->delete($page);

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.deleted', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.deleted', ['attribute' => __('page')]),
            route('admin.pages.index', $res->result)
        )->go();
    }
}
