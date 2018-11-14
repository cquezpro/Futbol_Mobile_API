<?php
/**
 * Created by PhpStorm.
 * User: Ing Kevin Cifuentes
 * Date: 23/03/2018
 * Time: 02:47 PM
 */

namespace App\Traits;


use Vinkla\Hashids\Facades\Hashids;

trait HashIdFields
{
    public function getHashIdAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }
}