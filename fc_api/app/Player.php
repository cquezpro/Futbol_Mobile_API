<?php

namespace App;

use App\Traits\DateFormatString;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use DateFormatString;
    
    protected $table = 'players';
    protected $fillable = [
        "descripcion",
        "player_number",
        "pie_fuerte",
        "pie_debil",
        "altura",
        "peso",
        "user_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function playergame()
    {
        return $this->hasMany(PlayerGamePosition::class, 'players_id','id');
    }
    public function consoles()
    {
        return $this->morphMany(Console::class, 'consoles');
    }
    public function favfutype()
    {
        return $this->morphMany(FutbolUser::class, 'favfutype');
    }
    public function favbrand()
    {
        return $this->morphMany(UserShoe::class, 'favbrand');
    }
    public function speciality()
    {
        return $this->morphMany(PlayerSpeciality::class, 'speciality');
    }
     public function skills(){
        return $this->morphMany(Skill::class,'skillable');
    }
    public function favclubes()
    {
        return $this->morphMany(UserClub::class, 'favclubes');
    }
    public function acknow()
    {
        return $this->morphMany(Acknowledgments::class, 'acknow');
    }

}
