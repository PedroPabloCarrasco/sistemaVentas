<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla categorias
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();

            // 👇 ESTA LÍNEA TE FALTA
            $table->foreignId('caracteristicas_id')->constrained()->onDelete('cascade');

            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Eliminar tabla categorias
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
