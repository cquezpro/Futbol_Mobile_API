<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

/**
 * Información técnica del usuario.
 */
class TechnicalInformation extends Model
{
    use HashIdFields;

    protected $fillable = [
        'weight',
        'height',
        'right_foot_strength',
        'left_foot_strength',
        'professional_contract',
    ];

    protected $appends = [
        'hash_id',
    ];

    protected $hidden = ['id', 'user_id', 'created_at', 'updated_at'];

    //region Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //endregion
}
