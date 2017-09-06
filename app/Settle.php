<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settle extends Model
{
    public function owningUser()
    {
        return $this->hasOne('App\User', 'user_owns');
    }

    public function leaningUser()
    {
        return $this->hasOne('App\User', 'user_lent');
    }
}
