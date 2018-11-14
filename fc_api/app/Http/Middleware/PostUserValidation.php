<?php

namespace App\Http\Middleware;

use App\Post;
use App\User;
use Closure;

class PostUserValidation
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
        /*if ($request->method() != 'GET') {
            $userRoute = $request->route('user');
            $postRoute = $request->route('post');

            $id = null;

            if (!empty($userRoute) || !empty($postRoute)) {
                if ($userRoute instanceof User) {
                    $id = $userRoute->id;
                }elseif ($postRoute instanceof Post){
                    $id = $postRoute->user->id;
                } else {
                    $id = $userRoute;
                }

                if ($id != $request->user()->id)
                    return response(['message' => __('validation.error_users_id')], 409);
            }
        };*/

        return $next($request);
    }
}
