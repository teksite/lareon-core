<?php

namespace Lareon\Modules\Gadget\App\Providers;

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
                      'title'      => trans('lareon::global.crud.titles.all', ['attribute' => trans('gadgets')]),
                      'order'      => 1,
                      'route'      => 'admin.visual.gadgets.index',
                      'active'     => request()->routeIs('admin.visual.gadgets.index'),
                      'permission' => 'admin.gadget.read',

                  ],
              ], 'visual');
    }

    protected function panel(MenuRegisteringEvent $event,): void {}


}
