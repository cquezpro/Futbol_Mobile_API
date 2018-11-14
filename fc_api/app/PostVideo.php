<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class PostVideo extends Model
{
    use HashIdFields;

    protected $appends = ['hash_id'];

    //region Relations
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function userVideo()
    {
        return $this->belongsTo(UserVideo::class);
    }
    //endregion
}
