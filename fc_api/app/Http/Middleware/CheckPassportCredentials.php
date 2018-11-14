<?php

namespace App\Http\Middleware;

use Closure;

class CheckPassportCredentials
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
        $api_key = ($request->hasHeader('X-Api-Key')) ?
            $request->header('X-Api-Key') : false;

        $client_id = ($request->hasHeader('X-Client-Id')) ?
            $request->header('X-Client-Id') : false;

        if (!$api_key || !$client_id)
            return response(['message' => 'Access denied for this client'], 401);

        switch ($api_key) {
            case env('ANDROID_SECRET'):
            case env('IOS_SECRET'):
            case env('WEB_SECRET'):
                return $next($request);
        }

        return response([
            'message' => 'Access denied for this client',
        ], 403);
    }
}
