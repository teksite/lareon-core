<?php

namespace Lareon\Modules\Seo\App\Enums;

enum ChangeFrequencyEnum: string
{
    case Always = 'always';
    case Hourly = 'hourly';
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Yearly = 'yearly';
    case Never = 'never';

    public function label(): string
    {
        return match ($this) {
            self::Yearly  => 'yearly',
            self::Monthly => 'monthly',
            self::Weekly  => 'weekly',
            self::Daily   => 'daily',
            self::Hourly  => 'hourly',
            self::Always  => 'always',
            self::Never   => 'never',
        };
    }

}
