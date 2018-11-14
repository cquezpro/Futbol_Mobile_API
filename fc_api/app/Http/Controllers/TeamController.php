<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::query();

        if ($request->has('q')) {
            $teams->where('name', 'like', "%$request->q%");
        }

        $teams->select(['team_id', 'name', 'logo_path']);

        if ($request->has("per_page")) {
            $teams = $teams->paginate($request->per_page);
        } else {
            $teams = $teams->get();
        }
        return response()->json([
            'message' => '',
            'count' => count($teams),
            'teams' => $teams,
        ], 200);
    }

    public function schedulle()
    {
        $urlNews = [
            'https://www.eyefootball.com/football_news.xml',
            "http://futbol.as.com/rss/futbol/internacional.xml",
            'https://www.eyefootball.com/rss_news_transfers.xml',
            "http://estaticos.marca.com/rss/futbol/futbol-internacional.xml",
        ];

        $count = 0;
        $data = [];
        foreach ($urlNews as $url) {
            //$file = file_get_contents($url);
            $feed = simplexml_load_file($url, null, LIBXML_NOCDATA);
            $data[$count] = $feed->channel;
            $count++;
        }

        return response()->json($data);
        //return response()->json($feed->get_title());
    }

}
