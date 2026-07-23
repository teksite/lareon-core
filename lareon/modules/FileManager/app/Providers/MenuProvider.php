<?php

namespace Lareon\Modules\FileManager\App\Providers;

use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;
use Lareon\Steward\App\Traits\HasMenu;

class MenuProvider implements MenuRegisteringContract
{

    use HasMenu;

    public function priority(): int
    {
        return 101;
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
                'title'  => 'media',
                'order'  => 2,
                'icon'   => 'home',
                'active' => request()->routeIs('admin.media.*'),
            ], 'media')->addManyItem([
            [
                'title'  => 'browser',
                'order'  => 1,
                'route'  => 'admin.media.browser.index',
                'active' => request()->routeIs('admin.media.browser.index'),
            ], [
                'title'  => 'icons library',
                'order'  => 2,
                'route'  => 'admin.media.icons.index',
                'active' => request()->routeIs('admin.media.icons.index'),
            ],

        ], 'media');
    }

    protected function panel(MenuRegisteringEvent $event): void
    {
        /* $event->add([
             'title' => 'panel',
             'url'   => '/tkadmin',
             'route' => 'panel.dashboard',
             'icon'  => 'fas fa-blog',
             'order' => 1,
         ], 'steward');*/
    }


}
