<?php

namespace App\Http\Middleware;

use App\Traits\CommonResponse;
use Closure;

class ApiToken
{
    use CommonResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization') != env('API_KEY')) {
            return $this->errorResponse('Unauthorized', 401);
        }
        return $next($request);
    }
}
