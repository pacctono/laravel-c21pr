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
            $table->string('email', 100)->unique();
            $table->string('email_c21', 100)->nullable()->unique();
            $table->string('licencia_mls', 6)->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('password', 160);
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
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
