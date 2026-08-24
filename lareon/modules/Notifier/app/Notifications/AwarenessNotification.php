<?php

namespace Lareon\Modules\Notifier\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lareon\Modules\Notifier\App\Enums\ChannelsEnum;
use Lareon\Modules\Notifier\App\Notifications\Channels\SmsChannel;
use Lareon\Modules\Notifier\App\Notifications\Channels\TelegramChannel;

class AwarenessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $title, public string $message, public array $channels)
    {
        //
    }


    public function backoff(): int
    {
        return 5;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {

        $via = [];
//        $via = ['broadcast'];
        foreach ($this->channels as $channel) {
            if (ChannelsEnum::tryFrom($channel)) $via[] = $channel;
        }
        return $via;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
        ];
    }

    /**
     * Broadcast notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title'   => $this->title,
            'message' => $this->message,
        ]);
    }
}
