<?php

namespace Lareon\Modules\Questionnaire\App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Lareon\Modules\Questionnaire\App\Events\NewInboxEvent;
use Lareon\Modules\Questionnaire\App\Listeners\NewInboxListener;

class EventServiceProvider  extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        NewInboxEvent::class => [
            NewInboxListener::class,
        ]
    ];


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
