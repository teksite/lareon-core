<?php

namespace Lareon\Modules\Seo\App\Enums;

enum ChangeFrequencyEnum: string
{
    case Yearly = 'yearly';
    case Monthly = 'monthly';
    case Weekly = 'weekly';
    case Daily = 'daily';
    case Hourly = 'hourly';

    case Always = 'always';
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
