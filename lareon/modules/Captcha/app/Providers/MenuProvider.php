<?php

namespace Lareon\Modules\Captcha\App\Providers;

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
                'title'      => trans('captcha'),
                'order'      => 11,
                'route'      => 'admin.captcha.settings.read',
                'active'     => request()->routeIs('admin.captcha.settings.read'),
                'permission' => 'admin.menu.edit',
            ],
        ], 'settings');
    }

    protected function panel(MenuRegisteringEvent $event,): void {}


}
