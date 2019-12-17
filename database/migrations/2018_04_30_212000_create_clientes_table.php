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
            $table->string('cedula', 8)->nullable();
            $table->string('rif', 10)->nullable();
            $table->string('name', 160);
            $table->enum('tipo', ['C','V','A','F','O'])
                ->comment('[C]omprador,[V]endedor,[A]mbos,[F]amiliar,[O]tro');
            $table->string('telefono', 10)->nullable();
            $table->string('otro_telefono', 20)->nullable()
                ->comment('Otro numero de telefono adicional, podria ser un numero internacional.');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('email', 160)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('direccion', 160)->nullable();
            $table->string('observaciones', 190)->nullable();
            $table->unsignedInteger('contacto_id')->nullable()
                ->comment('Si es diferente a nulo, representa el id del contacto del que fueron obtenidos los datos');
            $table->foreign('contacto_id')->references('id')->on('contactos');
            $table->unsignedInteger('user_actualizo')->nullable();
            $table->unsignedInteger('user_borro')->nullable();
            $table->softDeletes();
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
