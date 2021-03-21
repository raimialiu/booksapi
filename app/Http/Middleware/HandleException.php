<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class HandleException
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        }
        catch(Exception $es) {
            return response(json_encode(['status'=>'failed', 'data'=>$es->getMessage()]), 500, ['Content-Type'=>'application/json']);
        }
        
    }
}
