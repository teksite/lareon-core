<?php

namespace Lareon\Modules\Page\App\Http\Controllers\Web\Admin\Pages;

use Illuminate\Http\RedirectResponse;
use Lareon\Modules\Page\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagesTrashController extends Controller
{

    /**
     * Display  a list of the resource.
     */
    public function index(): \Illuminate\Contracts\View\View
    {

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
