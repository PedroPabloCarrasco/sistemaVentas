<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class Producto extends Model
{
    use HasFactory;

    public function compras()
    {
        return $this->belongsToMany(Compra::class, 'detalle_compras')
            ->withPivot('cantidad', 'precio');
    }

    public function ventas()
    {
        return $this->belongsToMany(ventas::class, 'detalle_ventas')
            ->withPivot('cantidad', 'precio');
    }

    public function categorias()
    {
        return $this->belongsTo(Categoria::class)->withTimestamps();
    }
}
