<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acknowledgments extends Model
{
    public function acknow()
    {
        return $this->morphTo();
    }
}
