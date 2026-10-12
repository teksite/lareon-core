<?php

namespace Lareon\Modules\Notifier\App\Enums;

enum NotificationEnum: string
{
    case Info = 'info';
    case Warning = 'warning';
    case Danger = 'danger';
    case Alert = 'alert';
    case Success = 'success';
    case Failure = 'failure';
    case General = 'general';

    public function icon(): string
    {
        return match ($this) {
            self::Info => 'info-circle',
            self::Warning => 'alert-hexagon',
            self::Danger => 'no-enter',
            self::Alert => 'exclamation-circle',
            self::Success => 'tick-circle',
            self::Failure => 'cross-circle',
            self::General => 'bell-ring',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Info => 'bg-cyan-600',
            self::Warning => 'bg-yellow-600',
            self::Danger => 'bg-red-600',
            self::Alert => 'bg-amber-600',
            self::Success => 'bg-green-600',
            self::Failure => 'bg-rose-600',
            self::General => 'bg-blue-600',
        };
    }

    public function iconClass(): string
    {
        return match ($this) {
            self::Info => 'fill-none stroke-cyan-600',
            self::Warning => 'fill-none stroke-yellow-600',
            self::Danger => 'fill-none stroke-red-600',
            self::Alert => 'fill-none stroke-amber-600',
            self::Success => 'fill-none stroke-green-600',
            self::Failure => 'fill-none stroke-rose-600',
            self::General => 'fill-none stroke-blue-600',
        };
    }
}
