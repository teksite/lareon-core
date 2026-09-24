<?php

namespace Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Client\Inboxes;

use Lareon\Modules\Questionnaire\App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class SubmitController extends Controller
{

    /**
     * Display  a list of the resource.
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        //
    }

    /**
     * restore one instance from trash
     */
    public function reinstate($id): RedirectResponse
    {
        //
    }

    /**
     * delete one instance from DB forever
     */
    public function prune($id): RedirectResponse
    {
      //;
    }

    /**
     *  restore all instances.
     */
    public function restore(): RedirectResponse
    {
       //
    }

    /**
     * delete all instances forever.
     */
    public function flush(): RedirectResponse
    {
        //
    }


}
