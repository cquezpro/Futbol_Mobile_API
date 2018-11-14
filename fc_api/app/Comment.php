<?php

namespace App;

use App\Traits\DateFormatString;
use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HashIdFields, DateFormatString;

    protected $fillable = ['body'];
    protected $appends = ['hash_id'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
