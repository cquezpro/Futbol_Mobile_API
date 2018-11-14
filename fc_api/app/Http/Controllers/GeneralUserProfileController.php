<?php

namespace App\Http\Controllers;

use App\GeneralUserProfile;
use App\Http\Resources\User as UserResources;
use App\Team;
use App\User;
use Illuminate\Http\Request;

/**
 * Información general del usuario, los datos que se requieren no son obligatorios
 */
class GeneralUserProfileController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return array
     */
    public function update(Request $request)
    {
        $generalInfo = null;
        $user = $request->user();
        $generalInfo = !$user->generalInformation()->exists()
        ? new GeneralUserProfile
        : $user->generalInformation()->first();

        if ($request->has('gender')) {
            $generalInfo->gender = $request->gender;
        }

        if ($request->has('birthday')) {
            $generalInfo->birthday = $request->birthday;
        }

        if ($request->has('city_of_birth')) {
            $generalInfo->city_of_birth = $request->city_of_birth;
        }

        if ($request->has('city_of_residence')) {
            $generalInfo->city_of_residence = $request->city_of_residence;
        }

        if ($request->has('nationality')) {
            $generalInfo->nationality = $request->nationality;
        }

        if ($request->has('college')) {
            $generalInfo->college = $request->college;
        }

        if ($request->has('school')) {
            $generalInfo->school = $request->school;
        }

        $user->generalInformation()->save($generalInfo);

        if ($request->has('teams')) {
            $teams = $request->teams;

            $user = $request->user();
            if (count($user->teams) > 0) {
                $user->teams()->detach();
            }

            foreach ($teams as $team) {
                $team = Team::where('team_id', $team['id'])->first();

                $user->teams()->attach($team);
            }

        }

        $user->load("teams");
        $user->load('generalInformation');

        return response()->json([
            "user" => new UserResources($user),
        ], 200);
    }
}
