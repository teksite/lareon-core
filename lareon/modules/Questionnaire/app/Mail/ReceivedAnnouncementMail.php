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
     * Create a new message instance.
     */
    public function __construct(public array $content =[] , array|null $attachments=[])
    {
        $this->queue = 'emails';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'no-reply@barsasof.com',
            subject: $this->content['subject'] ?? __('new form has been submitted in :site' ,['site'=>__(config('app.name'))]),

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.formAnnouncement',
            with: [
                'content' => $this->content,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return is_array($this->attachments) ? $this->attachments : [$this->attachments];
    }
}
