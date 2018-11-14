<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class Skill extends Model
{
	public function skillable()
	 {
	   return $this->morphTo();	
	 }
}


