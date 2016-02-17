<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class SystemExecute
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
        if( Carbon::now()->gte(timeFilter()) ){
            return response()->view('lostinnight.index');
        }

        return $next($request);
    }
}
