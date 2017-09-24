<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{

    protected $dates = [
      'date',
    ];

    protected $fillable = [
      'date'
    ];

    public function items()
    {
        return $this->belongsToMany('App\Item', 'slips_items')
          ->withPivot('description')
          ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
