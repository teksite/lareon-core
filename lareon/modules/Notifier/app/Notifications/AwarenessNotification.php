<?php

namespace Lareon\Modules\Notifier\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lareon\Modules\Notifier\App\Notifications\Channels\SmsChannel;
use Lareon\Modules\Notifier\App\Notifications\Channels\TelegramChannel;

class AwarenessNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $title, public string $message, public array $channels)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {

        $channels = ['database',];

        $channels[] = 'mail';


        if ($notifiable->sms_notifications) $channels[] = SmsChannel::class;

        if ($notifiable->telegram_notifications) $channels[] = TelegramChannel::class;

        return $channels;
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
}
