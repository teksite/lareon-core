<?php

namespace Lareon\Modules\Fence\App\Providers;

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
        $event->addManyItem([
                  [
                      'title'      => trans('fence'),
                      'order'      => 10,
                      'route'      => 'admin.settings.ips.index',
                      'active'     => request()->routeIs('admin.settings.ips.index'),
                      'permission' => 'admin.menu.edit',

                  ],
              ], 'settings');
    }

    protected function panel(MenuRegisteringEvent $event,): void {}


}
