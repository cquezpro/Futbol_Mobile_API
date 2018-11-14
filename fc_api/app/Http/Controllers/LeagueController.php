<?php

namespace App\Http\Controllers;

use App\League;
use App\Team;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index(Request $request)
    {
        $leagues = League::all();
        return response()->json($leagues);
    }

    public function getStandings(Request $request)
    {
        $seasson = $request->seassion_id;

        $client = new Client([
            'base_uri' => 'https://sportickr.com',
        ]);

        $response = $client->request('GET', "api/v1.0/standings/season/{$seasson}?api-key=d24ba528d78707c5a23e36abf9675beb&include=league,stage");

        $info = json_decode($response->getBody()->getContents());

        if (is_object($info->data)) {
            self::setTeam($info->data->standings);
            return response()->json($info->data);
        } else if (!is_object($info->data) && count((array) $info->data) > 1) {

            foreach ($info->data as $inf) {
                self::setTeam($inf->standings);
            }
        }
        return response()->json($info->data);
    }

    private function setTeam($standings)
    {
        foreach ($standings as $standing) {
            $team = Team::where("team_id", $standing->team_id)->first();
            $standing->team = $team;
        }
    }
}
