<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserClub extends Model
{
    
        protected $table = 'user_clubes';
    
     public function favclubes()
    {
        return $this->morphTo();
    }
}
