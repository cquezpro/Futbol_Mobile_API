<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{

    protected $fillable = ['category_code'];
    protected $hidden = ['user_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function languageKey()
    {
        return $this->hasMany(LanguageKey::class, 'code', 'category_code');
    }

    public function languageKeyItems()
    {
        return $this->hasManyThrough(
            LanguageKeyItem::class,
            LanguageKey::class,
            'code',
            'language_key_id',
            'category_code',
            'id'
        );
    }

    public function scopeKeyItems($query)
    {
        $query->languageKey();
    }
}
