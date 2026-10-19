<?php

namespace Lareon\Modules\Ticketing\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class EnsureAdminCanViewTicket
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = $request->user('admin');
        $ticket = $request->route('ticket');

        if ($admin === null || $ticket === null) abort(403);

        $adminId = (int) $admin->getAuthIdentifier();

        $canView = $ticket->isUnassigned()
            || $ticket->isHeldBy($adminId)
            || $ticket->isPendingFor($adminId);

        abort_unless($canView, 403, 'شما اجازه‌ی مشاهده‌ی این تیکت را ندارید.');

        return $next($request);
    }
}
