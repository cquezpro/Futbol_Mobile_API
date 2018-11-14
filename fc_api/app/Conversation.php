<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'data',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function messages(){
        return $this->hasMany(ChatMessage::class);
    }
}
