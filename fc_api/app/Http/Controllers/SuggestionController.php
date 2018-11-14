<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\TeamUser;
use App\User;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $teams = $user->teams()->pluck('teams.team_id');

        $usersId = TeamUser::whereIn('team_id', $teams)
            ->where('user_id', '<>', $user->id)
            ->groupBy('user_id')
            ->get(['user_id'])->pluck("user_id");

        $friends = $user->getFriends()->pluck("id");

        $users = User::whereIn('id', $usersId)
            ->whereNotIn('id', $friends)
            ->get();

        return response([
            'users' => new UserCollection($users),
        ]);
    }
}
