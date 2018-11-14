<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsoleName extends Model
{
    protected $table = 'console_names';

    public function consoles(){
    	return $this->hasMany(Console::class,'console_names_id');
    }
}
