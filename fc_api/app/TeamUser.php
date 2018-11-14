<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    protected $table = 'team_user';
    protected $fillable = [
        "user_id",
        "team_id",
    ];


}
