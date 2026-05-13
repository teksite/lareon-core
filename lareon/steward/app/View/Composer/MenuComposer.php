<?php

namespace Lareon\Steward\App\View\Composer;

use Illuminate\View\View;
use Lareon\Steward\App\Service\MenuService;

class MenuComposer
{
    public function __construct(protected MenuService $menuService)
    {
    }

    public function compose(View $view): void
    {
        $view->with('mainMenus', $this->menuService->getAllMenus());
    }
}
