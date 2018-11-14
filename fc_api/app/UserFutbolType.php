<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFutbolType extends Model
{
        protected $fillable = [
        "user_id",
        "fut_code",
        "type",
        "status",
       
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function futType()
    {
        return $this->belongsTo(LanguageKey::class);
    }
   

}
