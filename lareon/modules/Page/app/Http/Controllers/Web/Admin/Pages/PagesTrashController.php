<?php

namespace Lareon\Modules\Page\App\Http\Controllers\Web\Admin\Pages;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Page\App\Http\Controllers\Controller;
use Lareon\Modules\Page\App\Logics\PageLogic;
use Lareon\Steward\App\Traits\UseTrashController;
use Teksite\Handler\Facade\Responder;

class PagesTrashController extends Controller implements HasMiddleware
{
    use UseTrashController;

    public string $attribute = 'page';
    public string $trashIndex = 'admin.pages.trashes.index';
    public string $backTo = 'admin.pages.index';

    public function __construct(public PageLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.page.delete'),
            new Middleware('can:admin.page.trash', only: ['prune', 'flush']),
        ];
    }


}
