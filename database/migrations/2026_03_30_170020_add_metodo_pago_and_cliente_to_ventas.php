<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {

            $table->string('metodo_pago')
                ->default('efectivo')
                ->after('estado');

            $table->foreignId('cliente_id')
                ->nullable()
                ->after('metodo_pago')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropColumn(['metodo_pago', 'cliente_id']);
        });
    }
};
