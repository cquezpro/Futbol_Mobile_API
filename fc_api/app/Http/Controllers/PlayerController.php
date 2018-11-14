<?php

namespace App\Http\Controllers;

use App\Http\Resources\Player as PlayerResource;
use App\Http\Resources\PlayerCollection;
use App\Player;
use App\User;
use App\UserFutbolType;
use App\UserGamePosition;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $player = $request->user()->players()->orderBy('created_at', 'desc')->get();

        return response(new PlayerCollection($player));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(Request $request, User $user)
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'message' => __('app.users.invalid'),
                'user' => null,
            ], 422);
        }

        $player = $user->players;

        if ($player->count() > 0) {
            $player = $user->players()->first();
        } else {
            $player = new Player();
        }

        if ($request->has("description")) {
            $player->descripcion = $request->description;
        }

        if ($request->has("player_number")) {
            $player->player_number = $request->player_number;
        }

        if ($request->has("futbol_sports")) {
            $player->futbol_sports = $request->futbol_sports;
        }

        if ($request->has("strong_foot")) {
            $player->pie_fuerte = $request->strong_foot;
        }
        
        if ($request->has("weak_foot")) {
            $player->pie_debil = $request->weak_foot;
        }
        //Utilizare este por el momento pero debe de ser cambiado por strong_foot
        if ($request->has("height")) {
            $player->altura = $request->height;
        }
        //Se usara este pero debe ser height
        if ($request->has("weight")) {
            $player->peso = $request->weight;
        }
        //Se usara este pero debe ser weight
        $user->players()->save($player);
        //Agregar posiciones de juego al perfil jugador
        if($request->has("gamepositions")){
            $gamepositions = $request->gamepositions;
            $user->gamepositions()->delete();
            
            foreach($gamepositions as $gameposition){
                $user_game = new UserGamePosition([
                    'game_post_code' => $gameposition,
                    'type'           => 'P',
                    'status'         => 1,
                ]);
                $user->gamepositions()->save($user_game);
            }
        }

        if ($request->has("futboltypes")) {
            $futboltypes = $request->futboltypes;
            $user->futboltypes()->delete();

            foreach ($futboltypes as $futboltype) {
                $user_futbol_type = new UserFutbolType([
                    'fut_code' => $futboltype,
                    'type' => 'P',
                    'status' => 1,
                ]);
                $user->futboltypes()->save($user_futbol_type);
            }
        }
        return response()->json(new PlayerResource($player), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        return new PlayerResource($player);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePlayer(Request $request, Player $player)
    {
        $player->descripcion = $request->descripcion;
        $player->player_number = $request->player_number;
        $player->pie_fuerte = $request->pie_fuerte;
        $player->pie_debil = $request->pie_debil;
        $player->altura = $request->altura;
        $player->peso = $request->peso;

        $player->save();

        return new PlayerResource($player);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Player $player)
    {
        $player->delete();

        return response([
            'message' => __('app.coach.delete'),
            'data' => null,
        ]);
    }
}
