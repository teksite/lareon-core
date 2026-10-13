<?php

namespace Lareon\Modules\Questionnaire\App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Lareon\Modules\Questionnaire\App\Mail\ReceivedAnnouncementMail;
use Lareon\Modules\Questionnaire\App\Models\FormInbox;
use Lareon\Modules\Questionnaire\App\Notifications\AdminNewFormSubmitNotification;

class NewInboxListener
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
    public int  $tries       = 1;

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $inbox = $event->inbox->loadMissing('form.announcement');
        $form = $inbox->form;
        $announcement = $form->announcement;
        $data = $this->inboxData($inbox);

        $emails = $this->parseCommaSeparated($announcement?->emails);
        $apiUrls = $this->parseCommaSeparated($announcement?->urls);


        if (!empty($emails)) $this->sendEmailsToAdmins($emails, $form->title, $inbox, $data);
        if (!empty($apiUrls)) $this->sendByApi($apiUrls, $data);

        $clientEmail = $data['email'] ?? null;
        if ($form->response_client && is_string($clientEmail) && filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
            $this->sendEmailToClient($clientEmail, $data);
        }
    }

    private function inboxData(FormInbox $inbox): array
    {
        $data = $inbox->data;
        if (is_array($data)) return $data;
        return method_exists($data, 'toArray') ? $data->toArray() : (array)$data;
    }

    /**
     * Parse comma-separated string into array.
     */
    private function parseCommaSeparated(?string $value): array
    {
        if (!$value) return [];
        return array_filter(array_map('trim', exploding($value)->toArray()));
    }


    private function sendEmailsToAdmins(array $emails, string $formTitle, FormInbox $inbox, array $data): void
    {
        $content = [
            'subject'      => __(config('app.name')).' - '.__('a new :title form submitted', ['title' => $formTitle]),
            'heading'      => __('hi'),
            'introduction' => [
                __('a new form has been submitted: :title', ['title' => $formTitle]),
                __('please check the inbox and take the necessary actions'),
            ],
            'action'       => [
                'title' => __('check it'),
                'url'   => route('admin.questionnaire.inboxes.edit', $inbox),
            ],
            'explanation'  => $this->generateEmailTable($data),
        ];

        $notification = new AdminNewFormSubmitNotification($content);

        foreach ($emails as $to) {
            try {
                Notification::route('mail', $to)->notify($notification);
            } catch (\Throwable $e) {
                Log::error("sending mail to (admin side) $to failed", ['exception' => $e]);
            }
        }
    }


    private function sendEmailToClient(string $email, array $data): void
    {
        $content = [
            'subject'     => __(config('app.name')).' - '.__('your form is submitted'),
            'heading'     => __('hi'),
            'explanation' => $this->generateEmailTable($data),
        ];

        try {
            Mail::to($email)->queue(new ReceivedAnnouncementMail($content));
        } catch (\Throwable $e) {
            Log::error("sending mail to (client side) $email failed", ['exception' => $e]);
        }
    }


    private function sendByApi(array $urls, array $data): void
    {
        foreach ($urls as $url) {
            try {
                $response = Http::acceptJson()->timeout(10)
                                ->retry(5, 200, null, false)
                                ->post($url, $data);

                if (!$response->successful()) {
                    Log::error('HTTP Request Failed', [
                        'url'      => $url,
                        'status'   => $response->status(),
                        'response' => $response->body(),
                        'time'     => now()->format('Y-m-d H:i:s'),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error("sending data to ($url) or connecting to it failed", ['exception' => $e]);
            }
        }
    }

    private function generateEmailTable(array $details): string
    {
        $tableRows = '';
        $isEvenRow = true;

        foreach ($details as $key => $value) {
            $background = $isEvenRow ? '#ecf5ff' : '#fff';
            $label = e(__((string)$key));
            $text = $this->formatValue($value);

            $tableRows .= "<tr style='background:{$background};border-bottom:1px solid #fff'>"
                ."<th style='padding:12px'>{$label}</th>"
                ."<td style='padding:12px'>{$text}</td>"
                .'</tr>';

            $isEvenRow = !$isEvenRow;
        }

        return "<table style='width:100%;border:1px solid #ccc'><tbody>{$tableRows}</tbody></table>";
    }

    private function formatValue(mixed $value): string
    {
        if (is_array($value)) return implode(', ', array_map(fn($v) => $this->formatValue($v), $value));

        if (is_bool($value)) return e($value ? __('yes') : __('no'));

        if ($value === null || is_scalar($value)) return e((string)$value);

        return e(json_encode($value, JSON_UNESCAPED_UNICODE));
    }

}
