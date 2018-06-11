<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Closure;

class AuthMiddleware
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
        $authHeader = $request->header('Authorization') ?: '';
        $tokens = explode(' ', $authHeader);
        if (count($tokens) == 2) {
            $request->merge(['token' => $tokens[1]]);
            return $next($request);
        }
        return new Response([ 'errors' => [[ 'message' => 'Unauthorized' ]]], 401);
    }
}
