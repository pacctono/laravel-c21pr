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
            $table->string('telefono', 10);
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('email', 160)->nullable();
            $table->string('direccion', 160)->nullable();
            $table->string('deseo', 10);
            $table->string('propiedad', 10);
            $table->unsignedInteger('zona_id')->nullable();
            $table->foreign('zona_id')->references('id')->on('zonas');
            $table->unsignedInteger('precio_id')->nullable();
            $table->foreign('precio_id')->references('id')->on('precios');
            $table->unsignedInteger('origen_id')->nullable();
            $table->foreign('origen_id')->references('id')->on('origens');
            $table->unsignedInteger('resultado_id')->nullable();
            $table->foreign('resultado_id')->references('id')->on('resultados');
            $table->string('observaciones', 190);
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
