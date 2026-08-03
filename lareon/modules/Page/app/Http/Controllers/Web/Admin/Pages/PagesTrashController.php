<?php

namespace Lareon\Modules\Page\App\Http\Controllers\Web\Admin\Pages;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Page\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Page\App\Logics\PageLogic;

class PagesTrashController extends Controller  implements HasMiddleware
{

    public function __construct(public PageLogic $logic)
    {

    }

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
        $result = $this->logic->restoreOne($id);
        return WebResponse::byResult($result, route('admin.pages.trash.index'))->go();
    }


    public function prune($id)
    {
        $result = $this->logic->wipeOne($id);
        return WebResponse::byResult($result, route('admin.pages.trash.index'))->go();
    }

    public function restore()
    {
        $result = $this->logic->restoreAll();
        return WebResponse::byResult($result, route('admin.pages.index'))->go();
    }


    public function flush()
    {
        $result = $this->logic->wipeAll();
        return WebResponse::byResult($result, route('admin.pages.index'))->go();
    }


}
