<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CierreMensual extends Model
{
    /**
     * Nombre de la tabla
     */
    protected $table = 'cierres_mensuales';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'mes',
        'anio',
        'total_ventas',
        'total_impuesto',
        'cantidad_ventas'
    ];

    /**
     * Cast de tipos
     */
    protected $casts = [
        'mes' => 'integer',
        'anio' => 'integer',
        'total_ventas' => 'float',
        'total_impuesto' => 'float',
        'cantidad_ventas' => 'integer'
    ];

    /**
     * Scope para filtrar por mes y año
     */
    public function scopePorPeriodo($query, $mes, $anio)
    {
        return $query->where('mes', $mes)
            ->where('anio', $anio);
    }

    /**
     * Verificar si ya existe un cierre en ese periodo
     */
    public static function existe($mes, $anio)
    {
        return self::where('mes', $mes)
            ->where('anio', $anio)
            ->exists();
    }

    /**
     * Obtener nombre del mes (ej: "abril")
     */
    public function getMesNombreAttribute()
    {
        return \Carbon\Carbon::create()->month($this->mes)
            ->locale('es')
            ->monthName;
    }

    /**
     * Formato CLP para total ventas
     */
    public function getTotalVentasFormateadoAttribute()
    {
        return '$' . number_format($this->total_ventas, 0, ',', '.');
    }

    /**
     * Formato CLP para impuesto
     */
    public function getTotalImpuestoFormateadoAttribute()
    {
        return '$' . number_format($this->total_impuesto, 0, ',', '.');
    }
}
