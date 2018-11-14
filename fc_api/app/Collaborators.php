<?php

namespace App;

use App\Traits\DateFormatString;
use Illuminate\Database\Eloquent\Model;

class Collaborators extends Model
{
    use DateFormatString;

    protected $table = 'collaborators';
    protected $fillable = [
        "description",
        "city_id",
        "link_linkedin",
        "link_blog",
        "link_facebook",
        "link_youtube",
        "link_instagram",
        "futbol_type",
        "collaborator_type",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
    	return $this->morphMany(Skill::class,'skillable');
    }

    public function langKey()
    {
        return $this->hasOne(LanguageKey::class,'collaborator_type');
    } 

    public function city()
    {
        return $this->belongsTo(City::class);
    }


}
