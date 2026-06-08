<?php

namespace Lareon\Modules\Auth\App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Lareon\Modules\Auth\App\Actions\Otp\DetectContactType;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Enums\ActionType;
use Lareon\Modules\Auth\App\Mail\OtpMail;

class SendOtpService {

    public function viaSMS(string $to, string $code, ActionType $action, ?string $expireAt=null): bool
    {
        $apiKey = config('services.msgway');
        $params = [
            "mobile"     => $to,
            "method"     => "sms",
            "templateID" => 16625,
            "params"     => [
                $code,
                $expireAt
            ]
        ];
        try {
            $res = Http::withHeaders(['apiKey'=> $apiKey, 'Accept' => 'application/json'])
                       ->post('https://api.msgway.com/send', $params);
            if ($res->successful()) return true;
            throw new \Exception($res->clientError());
        } catch (\Exception $exception) {
            (new OtpService())->forget($to, $action);
            Log::error($exception);
            return false;
        }
    }
    public function viaEmail(string $to, string $code, ActionType $action, ?string $expireAt=null): bool
    {

        try {
            $res = Mail::to($to)->send(new OtpMail($code, $expireAt));
            if (!!$res) return true;
            throw new \Exception();
        } catch (\Exception $exception) {
            (new OtpService())->forget($to, $action);
            Log::error($exception);
            return false;
        }
    }

}
