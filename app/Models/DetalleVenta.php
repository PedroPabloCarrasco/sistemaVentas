<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio',
        'costo'
    ];

    /**
     * Casts (IMPORTANTE para cálculos)
     */
    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'float',
        'costo' => 'float',
    ];

    /**
     * =========================
     * RELACIONES
     * =========================
     */

    // Un detalle pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Un detalle pertenece a una venta
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    /**
     * =========================
     * ACCESORES (CÁLCULOS)
     * =========================
     */

    // Subtotal = precio * cantidad
    public function getSubtotalAttribute()
    {
        return $this->precio * $this->cantidad;
    }

    // Ganancia = (precio - costo) * cantidad
    public function getGananciaAttribute()
    {
        return ($this->precio - $this->costo) * $this->cantidad;
    }

    /**
     * =========================
     * FORMATEOS (PARA VISTAS)
     * =========================
     */

    public function getPrecioFormateadoAttribute()
    {
        return number_format($this->precio, 0, ',', '.');
    }

    public function getSubtotalFormateadoAttribute()
    {
        return number_format($this->subtotal, 0, ',', '.');
    }

    public function getGananciaFormateadaAttribute()
    {
        return number_format($this->ganancia, 0, ',', '.');
    }
}
