<?php

namespace Lareon\Modules\Auth\App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $code ,public string $expireAt) {}

    /**
     * Build the message.
     */


    public function envelope() :Envelope
    {
        return new Envelope(
            subject: trans('auth::messages.verification_code.email_subject'),
        );
    }

    public function Content() : Content
    {
        $view = View::exists('mails.otp') ? 'mails.otp' : 'auth::mails.otp';

        return new Content(
            markdown: $view,
            with: [
                'code' => $this->code,
                'expireAt' => $this->expireAt,
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
        return [];
    }
}
