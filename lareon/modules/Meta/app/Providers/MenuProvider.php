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
        };
    }

    protected function admin(MenuRegisteringEvent $event): void
    {

        $event->addManyItem([
            [
                'title'      => trans('template editor'),
                'order'      => 5,
                'route'      => 'admin.settings.meta.elements.index',
                'active'     => request()->routeIs('admin.settings.meta.elements.index'),
                'permission' => 'admin.meta.element.read',
            ],
        ], 'settings');

    }
}
