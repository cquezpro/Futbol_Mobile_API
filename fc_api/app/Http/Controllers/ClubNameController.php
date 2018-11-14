<?php

namespace App\Http\Controllers;

use App\ClubName;
use App\Http\Resources\ClubCollection;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Hash;


class ClubNameController extends Controller
{

    public function __construct()
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $club = null;
        $club = new ClubName([
            'description' => $request->description,
        ]);
         
        $club->save();

        return response()->json([
            'message' => 'Saved',
            'club'    => $club,
        ], 200);   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ClubName $club)
    {
        return new ClubCollection($club); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $club
     * @return \Illuminate\Http\Response
     */
    public function updateClubName(Request $request, $club)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $club = ClubName::find($club);

        if(!$club)
            return response()->json([
                'message' => 'Not Found',
                'club'    => null,
            ],422);

        $club->description = $request->description;
        $club->save();

        return response()->json([
            'message' => 'Success',
            'club'    => $club,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $club
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, ClubName $club)
    {
        $club->delete();

        return response([
            'message' => 'Se eliminó correctamente',
            'data'    => null,
        ]);
    }
}
