<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Forms;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Lareon\Modules\Questionnaire\App\Logics\FormLogic;
use Lareon\Steward\App\Traits\UseTrashController;

class TrashFormsController extends Controller implements HasMiddleware
{
    use UseTrashController;

    public string $attribute = 'form';

    public ?string $view = null;
    public string $backTo = 'admin.questionnaire.inboxes.trash.index';

    public string $indexRoute = 'admin.questionnaire.inboxes.trash.index';
    public string $pruneRoute = 'admin.questionnaire.inboxes.trash.prune';
    public string $reinstateRoute = 'admin.questionnaire.inboxes.trash.reinstate';
    public string $flushRoute = 'admin.questionnaire.inboxes.trash.flush';
    public string $restoreRoute = 'admin.questionnaire.inboxes.trash.restore';


    public function __construct(public FormLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.questionnaire.form.delete'),
            new Middleware('can:admin.questionnaire.form.trash', only: ['prune', 'flush']),
        ];
    }

}
