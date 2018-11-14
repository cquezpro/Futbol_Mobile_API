<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    use HashIdFields;

    protected $appends = ['hash_id'];
    protected $hidden = ['id', 'user_image_id', 'post_id', 'pivot'];

    //region Relations
    public function userImage()
    {
        return $this->belongsTo(UserImage::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    //endregion
}
