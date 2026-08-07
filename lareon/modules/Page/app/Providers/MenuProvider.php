<?php

namespace Lareon\Modules\Page\App\Providers;

use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;
use Lareon\Steward\App\Traits\HasMenu;

class MenuProvider implements MenuRegisteringContract
{

    use HasMenu;

    public function priority(): int
    {
        return 105;
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
                'title'  => trans('pages'),
                'order'  => 102,
                'icon'   => 'paper-blank',
                'active' => request()->routeIs('admin.pages.*'),
            ], 'page')
              ->addManyItem([
                  [
                      'title'      => trans('lareon::global.crud.titles.all', ['attribute' => trans('pages')]),
                      'order'      => 1,
                      'route'      => 'admin.pages.index',
                      'active'     => request()->routeIs('admin.pages.index'),
                      'permission' => 'admin.page.read',

                  ],
                  [
                      'title'      => trans('lareon::global.crud.titles.create', ['attribute' => trans('page')]),
                      'order'      => 2,
                      'route'      => 'admin.pages.create',
                      'active'     => request()->routeIs('admin.pages.create'),
                      'permission' => 'admin.page.create',
                  ],
              ], 'page');
    }

    protected function panel(MenuRegisteringEvent $event): void
    {

    }


}
