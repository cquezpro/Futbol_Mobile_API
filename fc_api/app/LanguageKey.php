<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class LanguageKey extends Model
{
    use HashIdFields;

    protected $fillable = [
        'name',
        'code',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    //region Relations
    public function languageKeyItems()
    {
        return $this->hasMany(LanguageKeyItem::class);
    }
    //endregion

    //region Scopes
    /**
     * Método para retornar las llaves de los lenguajes filtrados por nombre o grupo.
     *
     * @param mixed $query
     * @param String $value Valor del nombre del grupo a consultar.
     * @return mixed
     */
    public function scopeKeys($query, $value)
    {
        return $query->where('name', '=', $value);
    }

    /**
     * Método para retornar los items en el idioma configurado en la aplicación.
     * @param string query
     * @param string name
     * @param String code Cadena de texto referente al filtro a utilizar en la consulta de los items
     * @return mixed
     */
    public function scopeKeyItemsByKey($query, $name, $code = null)
    {
        $query->keys($name)
            ->whereHas('LanguageKeyItems', function ($query) use ($code) {
                $query->where('value', 'like', "%$code%");
            })
            ->with([
            'languageKeyItems' => function ($q) use ($code) {
                $language = Language::byLocale()->first();
                if (!$language)
                    abort(500, 'Locale not found');

                $q->where('language_id', $language->id);
                //$q->where('value', 'like', "%$code%");
            },
        ]);

        return $query;
    }
    //endregion
    public function collaborator()
    {
      return $this->belongsTo(Collaborator::class);
    }

    public function player()
    {
      return $this->belongsTo(Player::class);
    }

    public function futCode()
    {
       return $this->hasMany(UserFutbolType::class,'fut_code');
    }

    public function game_post()
    {
      return $this->hasMany(UserGamePosition::class,'game_post_code');
    }


}


