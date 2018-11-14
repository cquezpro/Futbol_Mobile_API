<?php

namespace App\Http\Controllers;

use App\SpeakLanguage;
use Illuminate\Http\Request;

class SpeakLanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $speakLanguages = SpeakLanguage::query();

        if ($request->has('q')) {
            $query = strtolower($request->q);
            $speakLanguages->where('name', 'like', "$query%");
        }

        return response([
            'message' => '',
            'data'    => [
                'speak_languages' => $speakLanguages->select('id', 'name')->get(),
            ],
        ]);
    }
}
