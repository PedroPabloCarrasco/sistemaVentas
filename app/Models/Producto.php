<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria_id',
        'estado'
    ];

    //  RELACIÓN CORRECTA
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Compras
    public function compras()
    {
        return $this->belongsToMany(Compra::class, 'detalle_compras')
            ->withPivot('cantidad', 'precio');
    }

    // Ventas
    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'detalle_ventas')
            ->withPivot('cantidad', 'precio');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
