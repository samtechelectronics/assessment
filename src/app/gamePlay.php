<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gamePlay extends Model
{
    //
    public function players()
    {
        return $this->hasMany('App\gamePlayPlayer');
    }
    public function game()
    {
        return $this->belongsTo('App\game');
    }
}
