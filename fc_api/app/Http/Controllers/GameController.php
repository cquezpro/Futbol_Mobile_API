<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Console;
use App\Game;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  /*  public function index($consoleId)
    {
       $consola = Console::find($consoleId);



       $games = Game::whereHas('consoles',function($q) use ($consoleId){
          $q->where('console_id','=',$consoleId);
       })->get();

          return response()->json([
                    'message' => 'Lista de juegos por consola',
                    'Console_name'=>$consola->description,
                    'Console_id'=>$consola->id,
                    'Games'   => $games,
                
                    
                ], 200);
    }*/

    public function index(){
           /*$games =Game::all(); 
                return response()->json([
                    'message' => 'Lista de juegos',
                    'consolas'   => $games,
                
                    
                ], 200);*/

           $games = Game::has('consoles')->with('consoles')->get();
           return response()->json([
                    'message' => 'Lista de Juegos',
                    'Juegos'   => $games,
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
         var_dump($request->game_nick_name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
