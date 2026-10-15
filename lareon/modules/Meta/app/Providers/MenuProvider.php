<?php

namespace Lareon\Modules\Meta\App\Providers;

use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;
use Lareon\Steward\App\Traits\HasMenu;

class MenuProvider implements MenuRegisteringContract
{

    use HasMenu;

    public function priority(): int
    {
        return 1;
    }

    public function areas(): array
    {
        return [MenuAreaType::ADMIN, MenuAreaType::PANEL];
    }

    public function register(MenuRegisteringEvent $event): void
    {
        match ($event->area) {
            MenuAreaType::ADMIN => $this->admin($event),
            MenuAreaType::PANEL => $this->panel($event),
        };
    }

    protected function admin(MenuRegisteringEvent $event): void
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
                      'title'      => trans('template and elements'),
                      'order'      => 5,
                      'route'      => 'admin.visual.meta.templates.index',
                      'active'     => request()->routeIs('admin.visual.meta.*'),
                      'permission' => 'admin.meta.template.read',
                  ],
              ], 'visual');

    }

    protected function panel(MenuRegisteringEvent $event): void {}
}
