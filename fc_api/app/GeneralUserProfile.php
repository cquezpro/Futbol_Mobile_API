<?php

namespace App;

use App\Helpers\Helpers;
use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;


/**
 * <b>Name=</b>GeneralUserProfile<br>
 * <b>Description=</b>Información general (perfil) del usuario (nacionalidad, género, ciudad, universidad, escuela)
 *
 */
class GeneralUserProfile extends Model
{
    protected $fillable = [
        'biography',
        'birthday',
        'gender',
        'city_of_birth',
        'city_of_residence',
        'nationality',
        'college',
        'school',
    ];

    protected $hidden = [];

    //region Mutator && Accessors
    public function getNationalityAttribute($value)
    {
        $nationality = null;
        $language = Language::where('iso_code', app()->getLocale())->first();
        if (!empty($value)) {
            $nationality = LanguageKey::with([
                'languageKeyItems' => function ($query) use ($language) {
                    $query->where('language_id', $language->id);
                },
            ])
                ->select('code', 'id')
                ->find($value);

            return $nationality;
        }

        return null;
    }

    public function setGenderAttribute($value)
    {
        $gender = LanguageKey::KeyItemsByKey('genders', $value)->get();

        if ($gender->count() <= 0)
            return abort(422, trans('app.validates.gender.exists'));

        $this->attributes['gender'] = $value;
    }

    public function getGenderAttribute()
    {
        $gender = LanguageKey::where('code', $this->attributes['gender'])
            ->with([
                'languageKeyItems' => function ($q) {
                    $language = Language::byLocale()->first();
                    if (!$language)
                        abort(500, 'Locale not found');

                    $q->where('language_id', $language->id);
                },
            ])->select('code', 'id')
            ->first();
        return $gender;
    }

    //endregion

    //region Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cityOfBirth()
    {
        return $this->hasOne(City::class, 'id', 'city_of_birth');
    }

    public function cityOfResidence()
    {
        return $this->hasOne(City::class, 'id', 'city_of_residence');
    }
    //endregion
}