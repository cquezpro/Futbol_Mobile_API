<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['description'];

    public function users()
    {
       return $this->belongsToMany(User::class);
    }
}


