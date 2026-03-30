<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'total',
        'impuesto',
        'fecha_hora',
        'estado',
        'metodo_pago',   // 🔥 NUEVO
        'cliente_id'     // 🔥 NUEVO
    ];

    /**
     * Casts
     */
    protected $casts = [
        'total' => 'float',
        'impuesto' => 'float',
        'fecha_hora' => 'datetime',
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'estado' => 'completada',
        'metodo_pago' => 'efectivo',
    ];

    /**
     * RELACIONES
     */

    // 🔥 Detalles de la venta
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    // 🔥 Cliente (opcional)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * ACCESORES
     */

    public function getTotalFormateadoAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getFechaFormateadaAttribute()
    {
        return $this->fecha_hora
            ? Carbon::parse($this->fecha_hora)->format('d/m/Y H:i')
            : null;
    }

    public function getMetodoPagoTextoAttribute()
    {
        return ucfirst($this->metodo_pago);
    }
}
