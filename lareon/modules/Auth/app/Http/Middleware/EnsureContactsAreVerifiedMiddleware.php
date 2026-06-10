<?php

namespace Lareon\Modules\Auth\App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\User\App\Models\User;
use Teksite\Handler\Facade\Responder;

class EnsureContactsAreVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('sanctum');

        if (!$user) {
            abort(403, 'Your are not authenticated.');
        }
        if (!$this->verifiedContacts($user)) {
            if ($request->expectsJson()) {
                return Responder::Failed('Your contacts must be verified.', $this->getErrors($user),403)->reply();
            }
            abort(403, 'Your contacts must be verified.');
        }

        return $next($request);
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function getErrors(mixed $user): array
    {
        $errors = [];
        if (!$user->hasVerifiedEmail()) {
            $errors['email'] = trans('auth::messages.auth.contact_not_verified', ['attribute' => __('email')]);
        }
        if (!$user->hasVerifiedPhone()) {
            $errors['phone'] = trans('auth::messages.auth.contact_not_verified', ['attribute' => __('phone')]);
        }
        return $errors;
    }

    public function verifiedContacts(Authenticatable|User $user = null, ContactType|null $contactType = null): bool
    {
        $contactTypes = [
            'phone' => $user->hasVerifiedPhone(),
            'email' => $user->hasVerifiedEmail(),
        ];
        if (is_null($contactType)) return $contactTypes['phone'] && $contactTypes['email'];

        return $contactTypes[$contactType->value];


    }
}
