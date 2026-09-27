<?php

namespace Lareon\Modules\Menu\App\Providers;

use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;
use Lareon\Steward\App\Traits\HasMenu;

class MenuProvider implements MenuRegisteringContract
{

    use HasMenu;

    public function priority(): int
    {
        return 110;
    }

    public function areas(): array
    {
        return [MenuAreaType::ADMIN, MenuAreaType::PANEL];
    }

    public function register(MenuRegisteringEvent $event,): void
    {
        match ($event->area) {
            MenuAreaType::ADMIN => $this->admin($event),
            MenuAreaType::PANEL => $this->panel($event),
        };
    }

    protected function admin(MenuRegisteringEvent $event,): void
    {
        $event->add(
            [
                'title'  => trans('visual'),
                'order'  => 102,
                'icon'   => 'eye',
                'active' => request()->routeIs('admin.visual.*'),
            ], 'visual')
              ->addManyItem([
                  [
                      'title'      => trans('lareon::global.crud.titles.all', ['attribute' => trans('menus')]),
                      'order'      => 1,
                      'route'      => 'admin.visual.menus.index',
                      'active'     => request()->routeIs('admin.visual.menus.index'),
                      'permission' => 'admin.menu.read',

                  ],
              ], 'visual');
    }

    protected function panel(MenuRegisteringEvent $event,): void {}


}
