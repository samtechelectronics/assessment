<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gamePlayPlayer extends Model
{
    //
    public function game()
    {
        return $this->belongsTo('App\game');
    }
}
