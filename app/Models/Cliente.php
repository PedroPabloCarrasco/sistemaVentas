<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function ventas()
    {
        return $this->hasMany(Ventas::class);
    }
}
