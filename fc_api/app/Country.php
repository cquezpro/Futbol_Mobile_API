<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HashIdFields;
    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'iso_num',
        'phone_code',
    ];

    protected $appends=['hash_id'];
    protected $hidden = ['id'];
    public function states()
    {
        return $this->hasMany(State::class);
    }
}
