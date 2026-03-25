<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function provedore()
    {
        return $this->hasOne(Proveedore::class);
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
}
