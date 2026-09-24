<?php

namespace Lareon\Modules\Questionnaire\App\Listeners;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Lareon\Modules\Questionnaire\App\Models\Form;
use Lareon\Modules\Questionnaire\App\Models\FormInbox;

class NewInboxListener
{
    private const string QUEUE_NAME = 'emails';

    /**
     * Handle the event.
     */
    public function handle(object $event,): void
    {
        $inbox = $event->inbox;

        $form = $event->form;

        $announcements = $form->announcement;

        $emails = $this->parseCommaSeparated($announcements?->emails);
        $apiUrls = $this->parseCommaSeparated($announcements?->urls);
        if (!empty($emails)) $this->sendEmailsToAdmins($emails, $form, $inbox);
        if (!empty($apiUrls)) $this->sendByApi($apiUrls, $form, $inbox);

        if ($form->response_client && isset(($inbox->data->array())['email'])) $this->sendEmailToClient($emails, $form, $inbox);
    }

    /**
     * Parse comma-separated string into array.
     */
    private function parseCommaSeparated(?string $value,): array
    {
        return $value ? array_filter(array_map('trim', exploding($value)->toArray())) : [];
    }

    /**
     * Send notification emails.
     */
    private function sendEmailsToAdmins(array $emails, Form $form, FormInbox $inbox,): void
    {
        $subject = __(config('app.name')).' - '.__('a new :title form submitted', ['title' => $form->title]);
        $heading = "#".__('hi');
        $introduction = [
            __('a new form has been submitted: :title', ['title' => $form->title]),
            __('please check the inbox and take the necessary actions'),
        ];
        $action = [
            'title' => __('check it'),
            'url'   => route('admin.questionnaire.inboxes.edit', $inbox),
        ];

        $explanation = $this->generateEmailTable($inbox->data);
        $content = [
            'subject'      => $subject,
            'heading'      => $heading,
            'introduction' => $introduction,
            'action'       => $action,
            'explanation'  => $explanation,
        ];

        foreach ($emails as $to) {
            try {
                Notification::route('mail', $to)->notify(new NewFormSubmitNotifiaction($content));

            } catch (Exception $exception) {
                Log::error("sending mail to (admin side) $to failed");
                Log::error($exception);
            }
        }
    }

    private function sendEmailToClient(array $emails, Form $form, FormInbox $inbox,): void
    {
        $subject = __(config('app.name')).' - '.__('your form is submitted');
        $heading = __('hi');
        $introduction = [
            __('we\'ve received your form submission on :site', ['site' => __(config('app.name'))]),
            __('our team will check your request and get in touch with you if a call is needed'),
        ];
        $explanation = $this->generateEmailTable($inbox->data->toArray());
        $content = [
            'subject'      => $subject,
            'heading'      => $heading,
            'introduction' => $introduction,
            'explanation'  => $explanation,
        ];
        $mail = new ReceivedAnnouncementMail($content);
        $mail->onQueue(self::QUEUE_NAME);
        foreach ($emails as $email) {
            try {
                Mail::to($email)->queue($mail);

            } catch (Exception $exception) {
                Log::error("sending mail to (client side) $email failed");
                Log::error($exception);
            }
        }
    }

    /**
     * Generate HTML table for email content.
     */
    private function generateEmailTable(array $details,): string
    {
        $tableRows = '';
        $isEvenRow = true;

        foreach ($details as $key => $value) {
            if ($key === 'formpot') continue;

            $rowStyle = $isEvenRow
                ? 'background:#9cb9d9;border-bottom:1px solid #fff'
                : 'background:#fff;border-bottom:1px solid #fff';

            $data = is_array($value) ? implode(', ', $value) : e($value);
            $label = __(e($key));
            $tableRows .= "<tr style='{$rowStyle}'><th style='padding:12px'>{$label}</th><td style='padding:12px'>{$data}</td></tr>";

            $isEvenRow = !$isEvenRow;
        }
        return "<table style='width:100%;border:1px solid #ccc'><tbody>{$tableRows}</tbody></table>";
    }


    public function sendByApi($urls, $form, $inbox,)
    {
        $data = $inbox->data;

        foreach ($urls as $url) {
            try {
                $response = Http::withHeaders([
                    'accept' => 'application/json',
                ])->post($url, $data);

                if (!in_array($response->status(), [200, 201])) {
                    Log::error('HTTP Request Failed', [
                        'url'      => $url,
                        'status'   => $response->status(),
                        'response' => $response->body(),
                    ]);
                }
            } catch (Exception|ConnectionException $exception) {
                Log::error("sending data to SAKOO ($url) or connecting to it failed");
                Log::error($exception);
            }
        }

    }
}
