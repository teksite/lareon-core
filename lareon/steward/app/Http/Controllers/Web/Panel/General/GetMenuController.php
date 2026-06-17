<?php

namespace Lareon\Steward\App\Http\Controllers\Web\Panel\General;

use Lareon\Steward\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Steward\App\Service\MenuService;
use Teksite\Handler\Facade\Responder;

class GetMenuController extends Controller
{
    public function get()
    {
        $menu = app(MenuService::class)->adminTree();
        return Responder::success()->data(compact('menu'))->reply();
    }
}
