<?php

namespace App\Http\Controllers;

use App\Language;
use App\LanguageKey;
use Illuminate\Http\Request;

class UserGamePositionController extends Controller
{
    /**
     * Posión o posiciones de juego que indique el usuario
     */
    public function index(Request $request)
    {
        $gamePositions = LanguageKey::whereHas('languageKeyItems', function ($query) use ($request) {
            if ($request->has('q') && !empty($request->q))
                $query->where('value', 'like', "$request->q%");

            $query->select('id', 'value');
        })->where('name', 'game_positions')->with([
            'languageKeyItems' => function ($query) use ($request) {
                if ($request->has('q') && !empty($request->q))
                    $query->where('value', 'like', "$request->q%");
                $query->where('language_id', Language::where('iso_code', app()->getLocale())->first()->id);
            },
        ])->select('id', 'code');

        if ($request->has("per_page")) {
            $gamePositions = $gamePositions->paginate($request->per_page);
        } else {
            $gamePositions = $gamePositions->get();
        }

        return response()->json([
            'message' => '',
            'data'    => $gamePositions,
        ], 200);
    }
}
