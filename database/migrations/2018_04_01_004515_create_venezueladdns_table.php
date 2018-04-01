<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenezueladdnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venezueladdns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('estado_zona', 20);
            $table->string('ciudad_sector', 190);
            $table->string('ddn', 3)->index;
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venezueladdns');
    }
}
