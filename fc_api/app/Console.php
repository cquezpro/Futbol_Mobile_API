<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Console extends Model
{
    protected $fillable = ["name"];
    protected $hidden = ['created_at', 'updated_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'console_users');
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }
}
