<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'caracteristicas_id',
    ];

    //  UNA categoría tiene UNA característica
    public function caracteristicas()
    {
        return $this->belongsTo(Caracteristicas::class, 'caracteristicas_id');
    }
}
