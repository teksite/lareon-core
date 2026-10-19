<?php

namespace Lareon\Modules\Ticketing\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminOwnsTicket
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = $request->user(config('ticketing.guards.admin'));
        $ticket = $request->route('ticket');

        if ($admin === null || $ticket === null) {
            abort(403);
        }

        abort_unless(
            $ticket->isHeldBy((int) $admin->getAuthIdentifier()),
            403,
            'این تیکت در اختیار شما نیست.',
        );

        return $next($request);
    }
}
