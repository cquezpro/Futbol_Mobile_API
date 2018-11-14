<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class UserGamePosition extends Model
{
    use HashIdFields;

    protected $table = 'player_field_positions';
    protected $fillable = [
        "user_id",
        "game_post_code",
        "type",
        "status",
    ];

    public function getGamePositionValueAttribute()
    {
        $value = LanguageKey::where('code', $this->code)->first()
            ->languageKeyItems()
            ->byLanguage()
            ->first()->value;

        return $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game_post()
    {
        return $this->belongsTo(LanguageKey::class);
    }
    
}
