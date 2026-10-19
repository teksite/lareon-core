<?php

namespace Lareon\Modules\Ticketing\App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Lareon\Modules\Ticketing\App\Events\TicketAssigned;
use Lareon\Modules\Ticketing\App\Events\TicketAssignmentResponded;
use Lareon\Modules\Ticketing\App\Events\TicketClosed;
use Lareon\Modules\Ticketing\App\Events\TicketCreated;
use Lareon\Modules\Ticketing\App\Events\TicketMessageSent;

class EventServiceProvider  extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        TicketCreated::class => [
//            DispatchTicketToAi::class,
        ],
        TicketMessageSent::class => [
            // جای مناسب برای لیسنر اعلان بلادرنگ (broadcast/notification) آینده
        ],
        TicketAssigned::class => [
            // جای مناسب برای اطلاع‌رسانی به ادمین مقصد ارجاع/claim
        ],
        TicketAssignmentResponded::class => [
            // جای مناسب برای اطلاع‌رسانی نتیجه‌ی ارجاع به ادمین ارجاع‌دهنده
        ],
        TicketClosed::class => [
            // جای مناسب برای اطلاع‌رسانی بسته شدن تیکت به کاربر
        ],
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
