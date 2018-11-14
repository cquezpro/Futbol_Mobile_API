<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HashIdFields;

    protected $fillable = ['body'];
    protected $appends = ['hash_id'];

    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
