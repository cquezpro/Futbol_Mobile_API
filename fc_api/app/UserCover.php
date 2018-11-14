<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class UserCover extends Model
{
    use HashIdFields;
    protected $fillable = ['active'];
    protected $appends = ['hash_id', 'cover_path'];
    protected $hidden = ['id', 'user_id', 'user_image_id', 'created_at', 'updated_at', 'deleted_at'];

    //Region Relations
    public function userImage()
    {
        return $this->belongsTo(UserImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //endregion Relations

    //region Scopes
    public function getCoverPathAttribute()
    {
        return $this->userImage()->first()->path;
    }

    public function scopeActiveCover($query)
    {
        $query->where('active', '=', true);
    }
    //endregion
}
