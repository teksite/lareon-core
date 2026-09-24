<?php

namespace Lareon\Modules\Questionnaire\App\Providers;

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
        $event->add(
            [
                'title'  => trans('questionnaires'),
                'order'  => 102,
                'icon'   => 'paper-board',
                'active' => request()->routeIs('admin.questionnaires.*'),
            ], 'questionnaire')
              ->addManyItem([
                  [
                      'title'      => trans('lareon::global.crud.titles.all', ['attribute' => trans('questionnaires')]),
                      'order'      => 1,
                      'route'      => 'admin.questionnaire.forms.index',
                      'active'     => request()->routeIs('admin.questionnaire.forms.*'),
                      'permission' => 'admin.questionnaire.form.read',

                  ], [
                      'title'      => trans('lareon::global.crud.titles.all', ['attribute' => trans('inboxes')]),
                      'order'      => 2,
                      'route'      => 'admin.questionnaire.inboxes.index',
                      'active'     => request()->routeIs('admin.questionnaire.inboxes.*'),
                      'permission' => 'admin.questionnaire.inbox.read',

                  ], [
                      'title'      => trans('lareon::global.crud.titles.all', ['attribute' => trans('inboxes')]),
                      'order'      => 2,
                      'route'      => 'admin.questionnaire.analytics.show',
                      'active'     => request()->routeIs('admin.questionnaire.analytics.*'),
                      'permission' => 'admin.questionnaire.inbox.export',
                  ],

              ], 'questionnaire');
    }

    protected function panel(MenuRegisteringEvent $event,): void {}


}
