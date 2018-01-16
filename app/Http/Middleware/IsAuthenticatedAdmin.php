<?php

namespace App\Http\Middleware;

use App\Models\DB\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAuthenticatedAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if ($user->role != User::USER_ROLE_ADMIN) {
            return redirect('/');
        }

        return $next($request);
    }
}
