<?php

namespace App\Http\Middleware;

use Closure;

class YangnanMiddleware
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
        echo "ok";
        return $next($request);
    }
}
