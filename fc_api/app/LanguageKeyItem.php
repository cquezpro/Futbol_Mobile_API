<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @property  Integer language_id
 * @property  Integer language_key_id
 */
class LanguageKeyItem extends Model
{

    protected $fillable = ['value'];
    protected $hidden = ['language_id', 'language_key_id', 'created_at', 'updated_at', 'hash_id'];

    public function LanguageKey()
    {
        return $this->belongsTo(LanguageKey::class);
    }

    public function Language()
    {
        return $this->belongsTo(Language::class);
    }

    public function scopeByLanguage($query)
    {
        $language_id = Language::where('iso_code', app()->getLocale())->first()->id;
        $query->where('language_id', $language_id);
    }

    //region Mutator & Accessors
    public function getValueAttribute($value)
    {
        return ucfirst($value);
    }
    //endregion
}
