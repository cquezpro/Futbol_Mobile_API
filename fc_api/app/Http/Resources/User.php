<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $isFollowing = false;
        $Following = false;
        $friendPending = false;
        $pendingAcceptation = false;
        $isFriend = false;
        $profile = false;
        $type = false;

        if ($request->user()) {
            $profile = $this->profiles()->wherePivot('status', true)->first();
            $type = $this->futboltypes()->where('user_id', $request->user()->id)->count() >= 1 ? true : false;
            $isFollowing = $this->followers()->where('follower_id', $request->user()->id)->count() >= 1 ? true : false;
            $Following = $this->followings()->where('follower_id', $request->user()->id)->count() >= 1 ? true : false;

            if ($request->user()->hasSentFriendRequestTo(User::find($this->id))) {
                $friend = User::find($request->user()->id);
                $friendPending = $this->hasFriendRequestFrom($friend);
            } else if ($request->user()->hasFriendRequestFrom(User::find($this->id))) {
                $pendingAcceptation = true;
            }

            $isFriend = $this->isFriendWith(User::find($request->user()->id));
        }

        $avatar = $this->avatar()->activeAvatar()->first();
        $cover = $this->cover()->activeCover()->first();

        $generalInformation = $this->generalInformation()->with([
            'cityOfBirth' => function ($query) {
                $query->select('id', 'name', "format_string");
            },
        ])->with([
            'cityOfResidence' => function ($query) {
                $query->select('id', 'name', "format_string");
            },
        ])->first();

        $friends = $this->getFriends();

        //juegos por usuario
        //$usersgames = User::has('games')->with('games')->get();
        $usergames = $this->games;
        $favoriteClubes = $this->teams;
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "phone" => $this->phone,
            "provider" => $this->provider,
            "deleted_at" => $this->deleted_at,
            "city_id" => $this->city_id,
            "full_name" => $this->full_name,
            "nick_name" => $this->nick_name,
            "esport_futbol" => $this->esport_futbol,
            "has_preferences" => $this->has_preferences,
            "avatar" => $avatar,
            "cover" => $cover,
            "friendPending" => $friendPending,
            "pendingAcceptation" => $pendingAcceptation,
            "isFriend" => $isFriend,
            "Friends" => $friends,
            "isFollow" => $isFollowing,
            'followers' => [
                'total' => $this->followers()->count(),
                'data' => $this->followers,
            ],
            "favorite_clubes" => $favoriteClubes,
            'followings' => [
                'total' => $this->followings()->count(),
                'data' => $this->followings,
            ],
            'generalInformation' => $generalInformation,
            'technicalInformation' => $this->technicalInformation,
            'representative' => $this->representative,
            'speakLanguages' => $this->speakLanguages,
            'profile' => $profile,
            "futbol_type" => $this->futboltypes,
            'gamePositions' => $this->gamePositions,
            "link" => [
                "user_show" => route('api.users.show', $this->id),
            ],
            "games_user" => $usergames,
        ];
    }
}
