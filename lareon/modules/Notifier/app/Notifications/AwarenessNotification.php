<?php

namespace Lareon\Modules\Notifier\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Lareon\Modules\Notifier\App\Enums\ChannelsEnum;
use Lareon\Modules\Notifier\App\Enums\NotificationEnum;
use Lareon\Modules\Notifier\App\Notifications\Channels\SmsChannel;
use Lareon\Modules\Notifier\App\Notifications\Channels\TelegramChannel;

class AwarenessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    private const array EXTRA_CHANNELS = [
        'sms'      => SmsChannel::class,
        'telegram' => TelegramChannel::class,
    ];

    public function __construct(public string $title, public string $message, public NotificationEnum $type, public array $channels = [], public array $meta = [],) {}

    public function backoff(): array
    {
        return [5, 30, 60];
    }

    public function via(object $notifiable): array
    {
        $via = ['database'];

        foreach ($this->channels as $channel) {
            if ($channel === 'database' || ! ChannelsEnum::tryFrom($channel))  continue;
            $via[] = self::EXTRA_CHANNELS[$channel] ?? $channel;
        }

        return array_values(array_unique($via));
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->view(['notifier::emails.awareness', 'notifier::emails.awareness-text'],
                [
                    'title' => $this->title,
                    'body'  => $this->message,
                    'type'  => $this->type,
                    'name'  => data_get($notifiable, 'name'),
                ],
            );
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => [
                'title' => $this->title,
                'body'  => $this->message,
            ],
            'meta' => [
                ...$this->meta,
                'type'     => $this->type->value,
                'channels' => $this->channels,
            ],
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title'   => $this->title,
            'message' => $this->message,
            'type'    => $this->type->value,
        ]);
    }
}
