<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        "id",
        "name",
        "league_id",
        "is_current_season",
        "current_round_id",
        "current_stage_id",
    ];

    public $timestamps = false;
    public $incrementing = false;

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }
}
