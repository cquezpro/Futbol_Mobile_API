<?php

namespace App;

use App\Traits\DateFormatString;
use Illuminate\Database\Eloquent\Model;

class ProfileUser extends Model
{
	use DateFormatString;

    protected $table = 'profile_user';
    protected $fillable = [
        'user_id',
        'profile_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
