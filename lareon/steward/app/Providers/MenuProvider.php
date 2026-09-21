<?php

namespace Lareon\Steward\App\Providers;

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
                'title'  => trans('dashboard'),
                'order'  => 1,
                'icon'   => 'home',
                'route'  => 'admin.dashboard',
                'active' => request()->routeIs('admin.dashboard'),
            ]);

        $event->add([
            'title'  => trans('settings'),
            'order'  => 10,
            'icon'   => 'gears',
            'active' => request()->routeIs('admin.settings.*'),
        ], 'settings')
              ->addManyItem([
                  [
                      'title'      => trans('information'),
                      'order'      => 1,
                      'route'      => 'admin.settings.information.index',
                      'active'     => request()->routeIs('admin.settings.information.index'),
                      'permission' => 'admin.setting.read',

                  ], [
                      'title'      => trans('cache'),
                      'order'      => 2,
                      'route'      => 'admin.settings.cache.index',
                      'active'     => request()->routeIs('admin.settings.cache.index'),
                      'permission' => 'admin.setting.cache.read',
                  ], [
                      'title'      => trans('log'),
                      'order'      => 3,
                      'route'      => 'admin.settings.logs.index',
                      'active'     => request()->routeIs('admin.settings.logs.index'),
                      'permission' => 'admin.setting.log.read',
                  ], [
                      'title'      => trans('maintenance'),
                      'order'      => 3,
                      'route'      => 'admin.settings.maintenance.edit',
                      'active'     => request()->routeIs('admin.settings.maintenance.edit'),
                      'permission' => 'admin.setting.maintenance.edit',
                  ],
              ], 'settings');


        $event->add([
            'title'  => trans('admins'),
            'order'  => 11,
            'icon'   => 'laurel-wreath',
            'active' => request()->routeIs('admin.admins.*'),
        ], 'admins')
              ->addManyItem([
                  [
                      'title'      => trans('lareon::global.crud.titles.all', ['attribute' => trans('admins')]),
                      'order'      => 1,
                      'route'      => 'admin.admins.index',
                      'active'     => request()->routeIs('admin.admins.index'),
                      'permission' => 'admin.admin.read',

                  ], [
                      'title'      => trans('lareon::global.crud.titles.create', ['attribute' => trans('admin')]),
                      'order'      => 2,
                      'route'      => 'admin.admins.create',
                      'active'     => request()->routeIs('admin.admins.create'),
                      'permission' => 'admin.admin.create',
                  ],
              ], 'admins');

    }

    protected function panel(MenuRegisteringEvent $event,): void
    {
        $event->add(
            [
                'title'  => trans('dashboard'),
                'order'  => 1,
                'icon'   => 'home',
                'route'  => 'panel.dashboard',
                'active' => request()->routeIs('panel.dashboard'),
            ], 'dashboard');
        $event->add(
            [
                'title'  => trans('admin panel'),
                'order'  => 2,
                'icon'   => 'gear',
                'route'  => 'admin.dashboard',
                'active' => request()->routeIs('admin.dashboard'),
            ], 'adminpanel');
    }


}
