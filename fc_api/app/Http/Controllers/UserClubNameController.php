      <?php

      namespace App\Http\Controllers;

      use Illuminate\Http\Request;
      use App\User;
      use App\UserTeam;
      use App\ClubName;
      use App\Http\Resources\User as UserResources;
      use Illuminate\Support\Facades\Validator;

      class UserClubNameController extends Controller
      {
         
         public function create(Request $request){
               $request->validate([
                      'teams' => 'required',
                  ]);
               $teams = $request->teams;
               $user = $request->user();
               $user->favoriteClubes()->delete();
               foreach ($teams as $team) {
                      $user_team = new UserTeam([
                        "team_id"   => $team['id'],
                        "team_name" => $team['name'],
                      ]);
                      $user->favoriteClubes()->save($user_team);
                }
                 return response()->json([
                    "mensaje" => "Proceso exitoso"
                  ], 200);
            
         }

         public function list(Request $request){
              $user = $request->user();
             
              $clubes = $user->favoriteClubes;

            
               
              return response()->json([
                    "mensaje" => "Lista de clubes por usuario",
                    "data" => $clubes
              ], 200); 
             
         }
         
      }
