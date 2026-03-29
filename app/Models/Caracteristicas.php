<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristicas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // ✅ UNA característica pertenece a UNA categoría
    public function categoria()
    {
        return $this->hasOne(Categoria::class, 'caracteristicas_id');
    }

    public function marca()
    {
        return $this->hasOne(Marca::class);
    }

    public function presentacione()
    {
        return $this->hasOne(Presentacione::class);
    }
}
