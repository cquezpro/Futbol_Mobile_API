<?php

namespace App\Http\Controllers;

use App\Http\Resources\Matches as MatchesResource;
use App\Matches;
use Illuminate\Http\Request;

class MatchesController extends Controller
{
    public function index(Request $request)
    {
        $match = $request->match;

        $match = Matches::where('game_id', $match)->first();

        if ($request->has('type')) {
            return response()->json($match);
        } else {
            return new MatchesResource($match);

        }
    }

    public function getMatches()
    {
        $matches = Matches::where('status', '<>', 'FINISHED')->get();
        $respData = [];
        foreach ($matches as $matche) {
            $m = [
                'game_id' => $matche->game_id,
                'status' => $matche->status,
                'localteam_id' => $matche->localteam_id,
                'localteam_name' => $matche->localteam_name,
                'localteam_patch' => $matche->localteam_patch,
                'localteam_score' => $matche->scores->localteam_score,
                'visitorteam_id' => $matche->visitorteam_id,
                'visitorteam_name' => $matche->visitorteam_name,
                'visitorteam_patch' => $matche->visitorteam_patch,
                'visitorteam_score' => $matche->scores->visitorteam_score,
            ];

            array_push($respData, $m);
        }

        return response()->json($respData);
    }

}
