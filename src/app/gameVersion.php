<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class gameVersion extends Model
{
    //
    public function game()
    {
        return $this->belongsTo('App\game');
    }
}
