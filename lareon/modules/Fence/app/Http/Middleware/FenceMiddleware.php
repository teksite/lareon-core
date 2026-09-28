<?php

namespace Lareon\Modules\Fence\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lareon\Modules\Fence\App\Contracts\FenceStoreContract;
use Lareon\Modules\Fence\App\Enums\GuardType;
use Symfony\Component\HttpFoundation\Response;

class FenceMiddleware
{
    public function __construct(protected FenceStoreContract $store,) {}

    public function handle(Request $request, Closure $next,): Response
    {
        $ip = (string)$request->ip();
        $mode = config('fence.mode', 'black');
        $record = $this->store->first($this->normalize($ip));
        $type = $record?->type;

        $blocked = match ($mode) {
            'black' => $type === GuardType::BLACK,
            'white' => $type !== GuardType::WHITE,
            default => false,
        };

        if ($blocked) abort(403, trans('Access denied by your IP address.'));

        return $next($request);
    }

    private function normalize(string $ip,): string
    {
        return filter_var($ip, FILTER_VALIDATE_IP) ? inet_ntop(inet_pton($ip)) : $ip;
    }

}
