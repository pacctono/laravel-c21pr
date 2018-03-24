<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 160);
            $table->string('telefono', 10)->nullable();
            $table->unsignedInteger('veces_telefono')->default(0);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('email', 160)->nullable();
            $table->unsignedInteger('veces_email')->default(0);
            $table->string('direccion', 160)->nullable();
            $table->unsignedInteger('deseo_id');
            $table->foreign('deseo_id')->references('id')->on('deseos');
            $table->unsignedInteger('propiedad_id');
            $table->foreign('propiedad_id')->references('id')->on('propiedads');
            $table->unsignedInteger('zona_id');
            $table->foreign('zona_id')->references('id')->on('zonas');
            $table->unsignedInteger('precio_id');
            $table->foreign('precio_id')->references('id')->on('precios');
            $table->unsignedInteger('origen_id');
            $table->foreign('origen_id')->references('id')->on('origens');
            $table->unsignedInteger('resultado_id');
            $table->foreign('resultado_id')->references('id')->on('resultados');
            $table->string('observaciones', 190)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
