<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameUser extends Model
{
     
    
       protected $fillable = [
        "game_id",
        "user_id",
      
    ];

     protected $hidden = [
        'id',
    ];
}
