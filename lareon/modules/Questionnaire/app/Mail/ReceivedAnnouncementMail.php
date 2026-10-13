<?php

namespace Lareon\Modules\Questionnaire\App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReceivedAnnouncementMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param array{
     *     subject: string,
     *     heading?: string,
     *     explanation?: string
     * } $content
     */
    public function __construct(public array $content)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->content['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'questionnaire::emails.received-announcement',
            with: [
                'appName'     => __(config('app.name')),
                'heading'     => $this->content['heading'] ?? __('hi'),
                'explanation' => $this->content['explanation'] ?? '',
            ],
        );
    }
}
