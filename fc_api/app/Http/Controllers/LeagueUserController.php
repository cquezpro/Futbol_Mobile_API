<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeagueUserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $leagues = $user->leagues;

        return response()->json($leagues);
    }
    public function store(Request $request)
    {
        $user = $request->user();
        $userLeagues = $user->leagues;

        if ($userLeagues && $userLeagues->count() > 0) {
            $user->leagues()->detach();
        }

        $leagues = $request->leagues;

        foreach ($leagues as $league) {
            $user->leagues()->attach($league["id"]);
        }

        $user->load("leagues");
        return response()->json([
            'leagues' => $user->leagues,
        ]);
    }
}
