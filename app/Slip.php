<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{

    public function items()
    {
        return $this->belongsToMany('App\Item', 'slips_items')
          ->withPivot('description')
          ->withTimestamps();
    }
}
