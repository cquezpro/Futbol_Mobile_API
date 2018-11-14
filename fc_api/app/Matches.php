<?php

namespace App;

use App\Traits\DateFormatString;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use DateFormatString;

    protected $table = 'matches';
    protected $fillable = [
        'game_id',
        'status',
        'localteam_id',
        'localteam_name',
        'localteam_patch',
        'visitorteam_id',
        'visitorteam_name',
        'visitorteam_patch',
        'formations',
        'scores',
        'statistics',
        'events',
        'time',
    ];

    public function getStatisticsAttribute($value)
    {
        return json_decode($value);
    }

    public function getTimeAttribute($value)
    {
        return json_decode($value);
    }

    public function getEventsAttribute($value)
    {
        return json_decode($value);
    }

    public function getScoresAttribute($value)
    {
        return json_decode($value);
    }

    public function getfFormationsAttribute($value)
    {
        return json_decode($value);
    }
}
