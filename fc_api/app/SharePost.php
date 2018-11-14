<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class SharePost extends Model
{
    use HashIdFields;

    protected $fillable = ['body'];
    protected $appends = ['hash_id'];
    protected $hidden = ['id', 'post_id', 'user_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
