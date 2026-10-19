<?php

namespace Lareon\Modules\Ticketing\App\Enums;

enum AssignmentType: string
{
    case Claim = 'claim';
    case Referral = 'referral';
    case AiHandoff = 'ai_handoff';

    public function label(): string
    {
        return match ($this) {
            self::Claim => 'claim',
            self::Referral => 'referral',
            self::AiHandoff => 'ai handoff',
        };
    }
}
