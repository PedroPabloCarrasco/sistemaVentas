<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'impuesto',
        'fecha_hora',
        'estado'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
