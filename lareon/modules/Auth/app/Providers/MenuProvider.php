<?php

namespace Lareon\Modules\Auth\App\Providers;

use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;
use Lareon\Steward\App\Traits\HasMenu;

class MenuProvider implements MenuRegisteringContract
{

    use HasMenu;

    public function priority(): int
    {
        return 10;
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
                'title'  => trans('authorization'),
                'order'  => 1,
                'parent' =>null,
                'icon' =>'lock-closed',
            ],
        );
        $event->addMany([
            [
                'title'  => trans('roles'),
                'route'  => 'admin.authorize.roles.index',
                'order'  => 2,
                'parent' => trans('authorization'),


            ],
            [
                'title'  => trans('permissions'),
                'route'  => 'admin.authorize.permissions.index',
                'order'  => 1,
                'parent' => trans('authorization'),

            ],
        ], 'auth');
    }

    protected function panel(MenuRegisteringEvent $event): void
    {
        $event->add([
            'title' => 'panel',
            'url'   => '/tkadmin',
            'route' => 'panel.dashboard',
            'icon'  => 'fas fa-blog',
            'order' => 1,
        ], 'steward');
    }


}
