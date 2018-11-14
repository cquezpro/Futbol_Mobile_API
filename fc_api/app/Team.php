<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        "id",
        "name",
        "short_code",
        "twitter",
        "founded",
        "logo_path",
    ];

    public $timestamps = false;
    public $incrementing = false;

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function seasons()
    {
        return $this->belongsToMany(Season::class);
    }

}
