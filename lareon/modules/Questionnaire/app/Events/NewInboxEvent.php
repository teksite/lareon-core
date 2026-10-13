<?php

namespace Lareon\Modules\Questionnaire\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lareon\Modules\Questionnaire\App\Models\FormInbox;

class NewInboxEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public FormInbox $inbox)
    {
    }
}
