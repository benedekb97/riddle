<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, $next, ...$guards)
    {
        if(Setting::where('name','lockdown')->where('setting','true')->count()>0){
            if(!Auth::user()->moderator) {
                return abort(405);
            }
        }

        return $next($request);
    }
}
