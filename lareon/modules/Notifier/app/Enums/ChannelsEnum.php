<?php

namespace Lareon\Modules\Notifier\App\Enums;

enum ChannelsEnum: string
{
    case Database = 'database';
    case mail = 'mail';
    case sms = 'sms';
    case Telegram = 'telegram';
}
