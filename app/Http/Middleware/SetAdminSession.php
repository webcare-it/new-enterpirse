<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class SetAdminSession
{
    /**
     * Handle an incoming request.
     *
     * This middleware should run as early as possible (ideally in the global middleware stack)
     * to ensure the correct session cookie name is set BEFORE the session starts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = $request->getPathInfo();

        $backendPrefixes = ['/admin', '/staff', '/aiz-uploader'];

        $isAdminPath = false;
        foreach ($backendPrefixes as $prefix) {
            if (Str::startsWith($path, $prefix)) {
                $isAdminPath = true;
                break;
            }
        }

        // Set the appropriate session cookie name based on the path
        if ($isAdminPath) {
            $cookieName = env('ADMIN_SESSION_COOKIE', 'admin_session');
        } else {
            $cookieName = env('SESSION_COOKIE', 'web_session');
        }

        // Update the configuration
        Config::set('session.cookie', $cookieName);
        if (app()->resolved('session')) {
            $sessionManager = app('session');
        }

        return $next($request);
    }
}
