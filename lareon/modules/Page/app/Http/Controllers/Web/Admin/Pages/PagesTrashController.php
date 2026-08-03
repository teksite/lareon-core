<?php

namespace Lareon\Modules\Page\App\Http\Controllers\Web\Admin\Pages;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Page\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Page\App\Logics\PageLogic;
use Teksite\Handler\Facade\Responder;

class PagesTrashController extends Controller implements HasMiddleware
{

    public function __construct(public PageLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.page.delete'),
            new Middleware('can:admin.page.trash', only: ['prune', 'flush']),
        ];
    }

    public function index()
    {
        $pages = $this->logic->getTrashes()->result;
        return view('page::admin.pages.pages.trash', compact('pages'));
    }


    public function reinstate($id)
    {
        $res = $this->logic->restoreOne($id);

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.restored', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.restored', ['attribute' => __('page')]),
            route('admin.pages.trash.index')
        )->go();

    }


    public function prune($id)
    {
        $res = $this->logic->wipeOne($id);
        return Responder::fromResult($res,
            trans('lareon::global.crud.success.pruned', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.pruned', ['attribute' => __('page')]),
            route('admin.pages.trash.index')
        )->go();
    }

    public function restore()
    {
        $res = $this->logic->restoreAll();
        return Responder::fromResult($res,
            trans('lareon::global.crud.success.allRestored'),
            trans('lareon::global.crud.error.allRestored'),
            route('admin.pages.index')
        )->go();

    }


    public function flush()
    {
        $res = $this->logic->wipeAll();

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.allPruned'),
            trans('lareon::global.crud.error.allPruned'),
            route('admin.pages.index')
        )->go();
    }


}
