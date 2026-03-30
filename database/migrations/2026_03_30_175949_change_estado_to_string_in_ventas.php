<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEstadoToStringInVentas extends Migration
{
    public function up()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('estado')->default('completada')->change();
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->integer('estado')->change();
        });
    }
}
