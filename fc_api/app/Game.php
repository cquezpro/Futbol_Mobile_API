<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ["name"];
    protected $hidden = ['created_at', 'updated_at','pivot'];


    public function consoles(){
   	    return $this->belongsToMany(Console::class,'console_game');
    }

    public function setNameAttribute($value){
        $this->attributes['name'] = strtolower($value);
    }

    public function users()
    {
    	return $this->belongsToMany(User::class);
    }

}
