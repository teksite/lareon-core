<?php

namespace Lareon\Modules\Notifier\App\Providers;

use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;
use Lareon\Steward\App\Traits\HasMenu;

class MenuProvider implements MenuRegisteringContract
{

    use HasMenu;

    public function priority(): int
    {
        return 100;
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
                'title'  => 'notifications',
                'order'  => 120,
                'icon'   => 'megaphone',
                'active' => request()->routeIs('admin.notifications.*'),
                'permission'=>'admin.notification.read'
            ],'auth'
        )->addManyItem(
        [
            [
                'title'  => 'notifications list',
                'order'  => 1,
                'route'  => 'admin.notifications.index',
                'active' => request()->routeIs('admin.notifications.index'),
            ],
            [
                'title'  => 'new notification',
                'order'  => 2,
                'route'  => 'admin.authorize.permissions.create',
                'active' => request()->routeIs('admin.authorize.permissions.create'),
            ],
        ], 'auth');
    }

    protected function panel(MenuRegisteringEvent $event): void
    {

    }


}
