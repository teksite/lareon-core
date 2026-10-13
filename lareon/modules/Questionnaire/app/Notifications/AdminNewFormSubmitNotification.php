<?php

namespace Lareon\Modules\Questionnaire\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AdminNewFormSubmitNotification extends Notification
{
    use Queueable;

    /**
     * @param array{
     *     subject: string,
     *     heading: string,
     *     introduction: array<int, string>,
     *     action: array{title: string, url: string},
     *     explanation: string
     * } $content
     */
    public function __construct(protected array $content)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->content['subject'])
            ->greeting($this->content['heading']);

        foreach ($this->content['introduction'] ?? [] as $line) {
            $mail->line($line);
        }

        if (!empty($this->content['explanation'])) {
            $mail->line(new HtmlString($this->content['explanation']));
        }

        if (!empty($this->content['action'])) {
            $mail->action($this->content['action']['title'], $this->content['action']['url']);
        }

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'subject' => $this->content['subject'],
        ];
    }
}
