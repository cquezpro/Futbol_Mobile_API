<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Language;
use App\LanguageKey;
use App\LanguageKeyItem;

class ResourceController extends Controller
{
    public function listSpecialities(){
         $language = Language::where('iso_code', app()->getLocale())->first(); 
         $speciallities = LanguageKey::with([
                'languageKeyItems' => function ($query) use ($language) {
                    $query->where('language_id', $language->id);
                },
            ])
           ->where('name','collaborator_types')
           ->select('id','code') 
           ->get();
         return response()->json([
            'message' => 'ok',
            'collaborators'=>$speciallities,
            
        ], 200);  
    }

    public function listFuboltypes(){
    	 $language = Language::where('iso_code', app()->getLocale())->first(); 
         $speciallities = LanguageKey::with([
                'languageKeyItems' => function ($query) use ($language) {
                    $query->where('language_id', $language->id);
                },
            ])
           ->where('name','futbol_types')
           ->select('id','code') 
           ->get();
         return response()->json([
            'message' => 'ok',
            'futbol_types'=>$speciallities,
            
        ], 200);  
    }
}
