<?php
/**
 * Created by PhpStorm.
 * User: Ing Kevin Cifuentes
 * Date: 24/05/2018
 * Time: 4:07 PM
 */

namespace App\Traits;


use Jenssegers\Date\Date;

trait DateFormatString
{
    public function getCreatedAtAttribute($date)
    {
        Date::setLocale(app()->getLocale());
        return new Date($date);
    }

    public function getUpdatedAtAttribute($date)
    {
        Date::setLocale(app()->getLocale());
        return new Date($date);
    }
}