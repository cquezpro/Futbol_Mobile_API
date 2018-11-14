<?php

namespace App\Http\Controllers;

use App\Http\Resources\Collaborator AS CollaboratorResource;
use App\Http\Resources\CollaboratorCollection;
use App\User;
use App\Collaborators;
use App\UserFutbolType;
use Illuminate\Http\Request;

class CollaboratorsController extends Controller
{
    public function index(Request $request)
    {
        $collaborator = $request->user()->collaborator()->orderBy('created_at', 'desc')->get();

        return new CollaboratorCollection($collaborator);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if(!$user)
          return response()->json([
              'message' => __('app.users.invalid'),
              'user' => null,
          ], 422);

          $coll = $user->collaborator;
          
          if ($coll->count() > 0) {
             $coll = $user->collaborator()->first();
          } else {
             $coll = new Collaborators();
          }
          //Validar los campos que se envían en el request
          if($request->has("description")){
            $coll->description = $request->description;
          }
          if($request->has("city_id")){
            $coll->city_id = $request->city_id;
          }
          if($request->has("link_linkedin")){
            $coll->link_linkedin = $request->link_linkedin;
          }
          if($request->has("link_blog")){
            $coll->link_blog = $request->link_blog;
          }
          if($request->has("link_facebook")){
            $coll->link_facebook = $request->link_facebook;
          }
          if($request->has("link_youtube")){
            $coll->link_youtube = $request->link_youtube;
          }
          if($request->has("link_instagram")){
            $coll->link_instagram = $request->link_instagram;
          }
          if($request->has("collaborator_type")){
            $coll->collaborator_type = $request->collaborator_type;
          }

          $user->collaborator()->save($coll);
          if($request->has("futboltypes")) {
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
        return response()->json(new CollaboratorResource($coll), 200);  
    }

    public function show(Collaborators $collaborator)
    {
        return new CollaboratorResource($collaborator);
    }

    public function update(Request $request, Collaborators $collaborator)
    {
        $collaborator->description         = $request->description;
        $collaborator->city_id             = $request->city_id;
        $collaborator->link_linkedin       = $request->link_linkedin;
        $collaborator->link_blog           = $request->link_blog;
        $collaborator->link_facebook       = $request->link_facebook;
        $collaborator->link_youtube        = $request->link_youtube;
        $collaborator->link_instagram      = $request->link_instagram;
        $collaborator->collaborator_type   = $request->collaborator_type;

        $collaborator->save();

        return new CollaboratorResource($collaborator);
    }

    public function delete()
    {
        # code...
    }
}
