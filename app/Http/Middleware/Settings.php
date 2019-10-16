<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Support\Facades\Auth;

class Settings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(Setting::where('name','lockdown')->where('setting','true')->count()>0){
            if(!Auth::user()->moderator) {
                return abort(405);
            }
        }

        if(Auth::user()->blocked)
        {
            return abort(402);
        }


        return $next($request);
    }
}
