<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'iso_code',
    ];

    public function languageKeyItems()
    {
        return $this->hasMany(LanguageKeyItem::class);
    }


    //region Scopes
    public function scopeByLocale($query)
    {
        return $query->where('iso_code', app()->getLocale());
    }
    //endregion
}
