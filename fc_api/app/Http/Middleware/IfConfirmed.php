<?php

namespace App\Http\Middleware;

use Closure;

class IfConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()->confirmed)
            return response()->json([
                'message' => 'account not confirmed',
                'data'    => [
                    'token' => '',
                    'user' => $request->user(),
                ],
            ], 401);

        return $next($request);
    }
}
