<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cedula', 8)->nullable();
            $table->string('name', 160);
            $table->unsignedInteger('veces_name')->default(0);
            $table->string('telefono', 10)->nullable();
            $table->unsignedInteger('veces_telefono')->default(0);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('email', 160)->nullable();
            $table->unsignedInteger('veces_email')->default(0);
            $table->string('direccion', 160)->nullable();
            $table->unsignedInteger('deseo_id');
            $table->foreign('deseo_id')->references('id')->on('deseos');
            $table->unsignedInteger('tipo_id');
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->unsignedInteger('zona_id');
            $table->foreign('zona_id')->references('id')->on('zonas');
            $table->unsignedInteger('precio_id');
            $table->foreign('precio_id')->references('id')->on('precios');
            $table->unsignedInteger('origen_id');
            $table->foreign('origen_id')->references('id')->on('origens');
            $table->unsignedInteger('resultado_id');
            $table->foreign('resultado_id')->references('id')->on('resultados');
            $table->datetime('fecha_evento')->nullable();
            $table->string('observaciones', 190)->nullable();
            $table->unsignedInteger('user_actualizo')->nullable();
            $table->unsignedInteger('user_borro')->nullable();
            $table->datetime('borrado_at')->nullable();
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
        Schema::dropIfExists('contactos');
    }
}
