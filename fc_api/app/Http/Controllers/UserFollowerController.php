<?php

namespace App\Http\Controllers;

use App\Events\FollowerEvent;
use App\Notifications\FollowerNotification;
use App\User;
use Illuminate\Http\Request;

class UserFollowerController extends Controller
{
    /**
     * Ruta para seguir a un usuario
     * Se instancia de FollowerNotification y FollowerEvent
     *
     * @param Request $request
     * @param User $user
     * @return \Exception|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request, User $user)
    {
        try {
            if (!$user)
                return response([
                    'message' => __('validate'),
                    'data'    => null,
                ], 422);

            $user->followers()->attach($request->user()->id);

            $user->notify(new FollowerNotification($user, $request->user()));
            event(new FollowerEvent($user, $request->user()));

            return response([
                'message' => __('app.follow.follow_user'),
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function delete(Request $request, User $user)
    {
        if (!$user)
            return response([
                'message' => __('validate'),
                'data'    => null,
            ], 422);

        $user->followers()->detach($request->user()->id);

        $user->notify(new FollowerNotification($user, $request->user(), 'unfollow'));
        event(new FollowerEvent($user, $request->user(), 'unfollow'));

        return response([
            'message' => __('app.follow.unfollow_user'),
            'data'    => null,
        ]);
    }
}
