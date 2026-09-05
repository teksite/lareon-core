<?php

namespace Lareon\Modules\Seo\App\Providers;

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
                'title'  => trans('SEO'),
                'order'  => 20,
                'icon'   => 'magnifier',
                'active' => request()->routeIs('admin.seo.*'),
            ], 'seo')
              ->addManyItem([
                  [
                      'title'  => 'site',
                      'order'  => 1,
                      'route'  => 'admin.seo.site.edit',
                      'active' => request()->routeIs('admin.seo.site.edit'),
                      'permission' => 'admin.seo.site.edit',


                  ], [
                      'title'  => 'sitemap',
                      'route'  => 'admin.seo.sitemaps.index',
                      'order'  => 2,
                      'active' => request()->routeIs('admin.seo.sitemaps.index'),
                      'permission' => 'admin.seo.sitemap.edit',


                  ], [
                      'title'  => 'robot.txt',
                      'route'  => 'admin.seo.robot.edit',
                      'order'  => 3,
                      'active' => request()->routeIs('admin.seo.robot.edit'),
                      'permission' => 'admin.seo.robot.edit',

                  ], [
                      'title'  => 'redirects',
                      'route'  => 'admin.seo.redirects.index',
                      'order'  => 4,
                      'active' => request()->routeIs('admin.seo.redirects.index'),
                      'permission' => 'admin.seo.redirect.edit',

                  ],
              ], 'seo');

    }

    protected function panel(MenuRegisteringEvent $event): void
    {

    }


}
