<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
        "id",
        "name",
        "is_cup",
        "current_season_id",
        "current_round_id",
        "current_stage_id",
        "country_id",
    ];

    public $timestamps = false;
    public $incrementing = false;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }
}
