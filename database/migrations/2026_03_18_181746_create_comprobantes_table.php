<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla comprobantes
     */
    public function up(): void
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_comprobante', 50); // Ej: Boleta, Factura
            $table->tinyInteger('estado')->default(1); // 1 = activo, 0 = inactivo
            $table->timestamps();
        });
    }

    /**
     * Eliminar tabla comprobantes
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
