<?php

namespace Lareon\Modules\Notifier\App\Enums;

enum NotificationEnum: string
{
    case Info    = 'info';
    case Warning = 'warning';
    case Danger  = 'danger';
    case alert   = 'alert';
    case Success = 'success';
    case Failure = 'failure';
    case general = 'general';


    public function icon(): string
    {
        return match ($this) {
            self::Info    => 'info-circle',
            self::Warning => 'alert-hexagon',
            self::Danger  => 'no-enter',
            self::alert   => 'exclamation-circle',
            self::Success => 'tick-circle',
            self::Failure => 'cross-circle',
            default       => 'bell-ring',
        };
    }

    public function classIcon(): string
    {
        return match ($this) {
            self::Info    => 'fill-none stroke-cyan-600',
            self::Warning => 'fill-none stroke-yellow-600',
            self::Danger  => 'fill-none stroke-red-600',
            self::alert   => 'fill-none stroke-amber-600',
            self::Success => 'fill-none stroke-green-600',
            self::Failure => 'fill-none stroke-rose-600',
            default       => 'fill-none stroke-blue-600',
        };
    }


}
