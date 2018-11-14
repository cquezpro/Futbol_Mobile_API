<?php

namespace App;

use App\Helpers\Helpers;
use App\Traits\DateFormatString;
use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class ClubName extends Model
{
    use HashIdFields, DateFormatString;
    
    protected $fillable = [
        "description",
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'user_teams');
    }


}
