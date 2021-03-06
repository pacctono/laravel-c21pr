<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('cedula', 8, 0)->nullable();
            $table->string('name', 160);
            $table->string('telefono', 10);
            $table->string('email', 160)->unique();
            $table->string('email_c21', 160)->nullable()->unique();
            $table->string('licencia_mls', 6)->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['F', 'M'])->nullable();
            $table->enum('estado_civil', ['C', 'S'])->nullable();
            $table->string('profesion', 100)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('password', 160);
            $table->boolean('is_admin')->default(false);
            $table->boolean('socio')->default(false);
            $table->boolean('activo')->default(true);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
