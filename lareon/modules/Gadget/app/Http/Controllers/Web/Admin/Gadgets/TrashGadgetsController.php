<?php

namespace Lareon\Modules\Gadget\App\Http\Controllers\Web\Admin\Gadgets;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Gadget\App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Lareon\Modules\Questionnaire\App\Logics\FormLogic;
use Lareon\Steward\App\Traits\UseTrashController;

class TrashGadgetsController extends Controller implements HasMiddleware
{
    use UseTrashController;

    public string $attribute = 'gadget';

    public ?string $view = null;
    public string $backTo = 'admin.visual.gadgets.index';

    public string $indexRoute = 'admin.visual.gadgets.index';
    public string $pruneRoute = 'admin.visual.gadgets.prune';
    public string $reinstateRoute = 'admin.visual.gadgets.reinstate';
    public string $flushRoute = 'admin.visual.gadgets.flush';
    public string $restoreRoute = 'admin.visual.gadgets.restore';


    public function __construct(public FormLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.gadget.delete'),
            new Middleware('can:admin.gadget.trash', only: ['prune', 'flush']),
        ];
    }

}
