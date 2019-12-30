<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendaPersonalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_personals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('user_id');
            $table->date('fecha_cita');
            $table->time('hora_cita');
            $table->string('descripcion', 500);
            $table->unsignedinteger('contacto_id');
            $table->unsignedinteger('cliente_id');
            $table->string('name', 160)->nullable();
            $table->string('telefono', 10)->nullable();
            $table->string('email', 160)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->date('fecha_evento');
            $table->time('hora_evento');
            $table->string('comentarios', 500)->nullable();
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
        Schema::dropIfExists('agenda_personals');
    }
}
