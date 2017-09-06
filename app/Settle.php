<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settle extends Model
{
    protected $fillable = ['amount', 'user_owns', 'user_lent'];

    protected $dates = [
      'date',
    ];

    public function owningUser()
    {
        return $this->hasOne('App\User', 'id', 'user_owns');
    }

    public function leaningUser()
    {
        return $this->hasOne('App\User', 'id', 'user_lent');
    }
}
