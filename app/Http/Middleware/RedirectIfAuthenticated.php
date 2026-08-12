<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard === 'dropshipper') {
                    return redirect()->route('dropshipper.dashboard');
                }
                // Admin Redirect
                if (
                    auth()->user() &&
                    in_array(auth()->user()->user_type, ['admin', 'staff'])
                ) {
                    return redirect()->route('admin.dashboard');
                }
                // Default Redirect
                return redirect('/');
            }
        }
        return $next($request);
    }
}
