<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{

    public function items()
    {
        return $this->belongsToMany('App\Item')
          ->withPivot('description')
          ->withTimestamps();
    }
}
