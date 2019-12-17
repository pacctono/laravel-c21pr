<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avisos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('turno_id');
            $table->foreign('turno_id')->references('id')->on('turnos');
            $table->enum('tipo', ['T','C','A'])
                ->comment('[T]arde,No se [C]onecto,[A]monestacion');
            $table->datetime('fecha')
                ->comment('Fecha de la amonestacion o cuando llego tarde.');
            $table->string('descripcion', 160)->nullable()
                ->comment("Descripcion de la amonestacion. Si es nulo, 'LLego tarde'.");
            $table->unsignedInteger('user_creo');
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
        Schema::dropIfExists('avisos');
    }
}
