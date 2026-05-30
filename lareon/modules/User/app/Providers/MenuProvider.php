<?php

namespace Lareon\Modules\User\App\Providers;

use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;
use Lareon\Steward\App\Traits\HasMenu;

class MenuProvider implements MenuRegisteringContract
{

    use HasMenu;

    public function priority(): int
    {
        return 102;
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
                'title'  => trans('users'),
                'order'  => 102,
                'icon'   => 'users',
                'active' => request()->routeIs('admin.users.*'),
            ], 'user')
              ->addManyItem([
                  [
                      'title'  => trans('lareon::global.crud.titles.all', ['attribute' => trans('users')]),
                      'order'  => 1,
                      'route'  => 'admin.users.index',
                      'active' => request()->routeIs('admin.users.index'),
                      'permission' => 'admin.user.read',

                  ],
                  [
                      'title'      => trans('lareon::global.crud.titles.create', ['attribute' => trans('user')]),
                      'order'      => 2,
                      'route'      => 'admin.users.create',
                      'active'     => request()->routeIs('admin.users.create'),
                      'permission' => 'admin.user.create',
                  ],
              ], 'user');
    }

    protected function panel(MenuRegisteringEvent $event): void
    {
        /*
        $event->add([
            'title' => 'panel',
            'url'   => '/tkadmin',
            'route' => 'panel.dashboard',
            'icon'  => 'fas fa-blog',
            'order' => 1,
        ], 'steward');
        */
    }


}
