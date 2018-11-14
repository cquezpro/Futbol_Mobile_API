<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserImage extends Model
{
    use SoftDeletes, HashIdFields;

    protected $fillable = ['path', 'form_device'];
    protected $appends = ['hash_id'];
    protected $hidden = ['id', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['deleted_at'];

    //region Mutator & Accessors
    public function getPathAttribute($value)
    {
        return env('DO_SPACES_URL') . $value;
    }
    //endregion

    //region Relations
    public function avatars()
    {
        return $this->hasMany(UserAvatar::class);
    }

    public function covers()
    {
        return $this->hasMany(UserCover::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //endregion
}
