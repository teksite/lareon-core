<?php

namespace Lareon\Steward\App\Contracts;

use Lareon\Steward\App\Events\MenuRegisteringEvent;

interface MenuRegisteringContract
{
    public function register(MenuRegisteringEvent $event): void;
    public function priority(): int;
    public function areas(): array;
}
