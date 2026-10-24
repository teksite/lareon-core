<?php

namespace Lareon\Modules\Ticketing\App\Providers;

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
                'title'      => trans('tickets'),
                'order'      => 120,
                'icon'       => 'chat-gear',
                'active'     => request()->routeIs('admin.tickets.*') || request()->routeIs('admin.ticketing.tickets.*'),
                'permission' => 'admin.ticket.read',
            ], 'tickets')
              ->addManyItem([
                  [
                      'title'      => trans('lareon::global.crud.titles.all', ['attribute' => trans('tickets')]),
                      'order'      => 1,
                      'route'      => 'admin.ticketing.tickets.index',
                      'active'     => request()->routeIs('admin.ticketing.tickets.*'),
                      'permission' => 'admin.ticket.read.all',

                  ],
                  [
                      'title'      => trans('lareon::global.crud.titles.list', ['attribute' => trans('tickets')]),
                      'order'      => 2,
                      'route'      => 'admin.tickets.index',
                      'active'     => request()->routeIs('admin.tickets.index'),
                      'permission' => 'admin.ticket.read',
                  ],
                  [
                      'title'      => trans('lareon::global.crud.titles.create', ['attribute' => trans('ticket')]),
                      'order'      => 3,
                      'route'      => 'admin.tickets.create',
                      'active'     => request()->routeIs('admin.tickets.create'),
                      'permission' => 'admin.ticket.create',
                  ],
              ], 'tickets');
    }

    protected function panel(MenuRegisteringEvent $event): void
    {
        $event->add(
            [
                'title'      => trans('tickets'),
                'order'      => 120,
                'icon'       => 'chat-gear',
                'active'     => request()->routeIs('panel.tickets.*'),
                'route'      => 'panel.tickets.index',
                'permission' => 'panel.ticket.read',
            ], 'tickets'
        );
    }


}
