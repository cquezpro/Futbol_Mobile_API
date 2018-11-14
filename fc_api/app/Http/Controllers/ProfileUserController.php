<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;
use App\ProfileUser;
use App\Http\Resources\ProfileUser AS ProfileUserResource;
use App\Http\Resources\ProfileUserCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Crear rutina en el resourse de usuario para traer el perfil de usuario.
        //En este controlador cambiar el estado
    }

    public function updateUserProfile(Request $request, Profile $profile)
    {
        $user = $request->user();

        if(!$profile){
            return response()->json([
                'message' => __('app.users.invalid'),
                'user'    => null,
            ], 422);
        }
        
        ProfileUser::where('user_id', $user->id)->update(['status' => false]);

        $user->profiles()->attach($profile,[
            'status'              => true,
        ]);
        return response()->json([
            'message' => 'Profile changed',
            'user'    => $user->profiles()->where('status',true)->first()
        ], 200); 
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
