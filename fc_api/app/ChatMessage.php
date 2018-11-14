<?php

namespace App;

use App\Traits\DateFormatString;
use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HashIdFields, DateFormatString;

    protected $fillable = [
        'from',
        'body',
        'is_read',
    ];


    public function getFromAttribute($value)
    {
        return new \App\Http\Resources\User(User::find($value));
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function fromUser()
    {

    }
}
