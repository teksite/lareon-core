<?php

namespace Lareon\Steward\App\Service;

use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;

class MenuService
{
    public function __construct(protected MenuDiscoveryService $discovery)
    {
    }

    public function get(MenuAreaType $area, bool $fresh = false): array
    {
        $event = new MenuRegisteringEvent($area);

        foreach ($this->discovery->get($area, $fresh) as $provider) {
            $provider->register($event);
        }

        return $event->visible();
    }

    public function tree(MenuAreaType $area, bool $fresh = false): array
    {
        $event = new MenuRegisteringEvent($area);

        foreach ($this->discovery->get($area, $fresh) as $provider) {
            $provider->register($event);
        }
        return $event->tree();
    }

    public function admin(bool $fresh = false): array
    {
        return $this->get(MenuAreaType::ADMIN, $fresh);
    }

    public function panel(bool $fresh = false): array
    {
        return $this->get(MenuAreaType::PANEL, $fresh);
    }

    public function adminTree(bool $fresh = false): array
    {
        return $this->tree(MenuAreaType::ADMIN, $fresh);
    }

    public function panelTree(bool $fresh = false): array
    {
        return $this->tree(MenuAreaType::PANEL, $fresh);
    }

    public function clear(): void
    {
        $this->discovery->clear();
    }
}
