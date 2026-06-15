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
                'title'  => trans('dashboard'),
                'order'  => 1,
                'icon'   => 'home',
                'route'  => 'admin.dashboard',
                'active' => request()->routeIs('admin.dashboard'),
            ]);

        $event->add([
            'title'  => trans('settings'),
            'order'  => 2,
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
                      'title'      => trans('caching'),
                      'order'      => 3,
                      'route'      => 'admin.settings.cache.index',
                      'active'     => request()->routeIs('admin.settings.cache.index'),
                      'permission' => 'admin.setting.cache.read',
                  ], [
                      'title'      => trans('log'),
                      'order'      => 2,
                      'route'      => 'admin.settings.logs.index',
                      'active'     => request()->routeIs('admin.settings.logs.index'),
                      'permission' => 'admin.setting.log.read',
                  ],
              ], 'settings');

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
