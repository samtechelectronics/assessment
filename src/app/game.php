<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class game extends Model
{
    //
    public function versions()
    {
        return $this->hasMany(gameVersion::class);
    }
}
