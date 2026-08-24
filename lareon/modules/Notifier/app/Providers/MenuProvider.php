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
                'title'      => trans('notifications'),
                'order'      => 120,
                'icon'       => 'megaphone',
                'active'     => request()->routeIs('admin.notifications.*'),
                'permission' => 'admin.notification.read',
            ], 'notifications'
        )->addManyItem(
            [
                [
                    'title'  => trans('lareon::global.crud.titles.all', ['attribute' => trans('notifications')]),
                    'order'  => 1,
                    'route'  => 'admin.notifications.index',
                    'active' => request()->routeIs('admin.notifications.index'),
                ],
                [
                    'title'  => trans('lareon::global.crud.titles.create', ['attribute' => trans('notification')]),
                    'order'  => 2,
                    'route'  => 'admin.notifications.create',
                    'active' => request()->routeIs('admin.notifications.create'),
                ],
            ], 'notifications');
    }

    protected function panel(MenuRegisteringEvent $event): void {}


}
