<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class SpeakLanguage extends Model
{
    use HashIdFields;

    protected $fillable = [
        'name',
    ];

    protected $appends = [
        'hash_id',
    ];

    protected $hidden = ['id', 'pivot'];

    //region Relations
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_speak_languages');
    }
    //endregion
}
