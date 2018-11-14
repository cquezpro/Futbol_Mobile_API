<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HashIdFields;

    protected $fillable = ['name'];
    protected $appends = ['hash_id'];
    protected $hidden = ['id', 'country_id'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
