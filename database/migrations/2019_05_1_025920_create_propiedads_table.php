<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropiedadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propiedads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 6)->unique();
            $table->date('fecha_reserva')->nullable();
            $table->date('fecha_firma')->nullable();
            $table->enum('negociacion', ['V', 'A'])->comment('[V]enta, [A]luiler');
            $table->string('nombre', 160);
            $table->enum('estatus', ['I', 'P', 'C', 'S'])
                ->comment('[I]nmueble pendiente, Pagos pendientes, inmueble [C]errado y pagos realizados, [S]negociacion caida')
                ->default('I');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('moneda', ['$', 'Bs'])->default('$')->comment('$, Bs');
            $table->float('precio', 20, 2)->comment('Precio del inmueble');
            $table->float('comision', 4, 2)->default(5.00)->comment('Porcentaje de comision');
//            $table->float('reserva_sin_iva', 18, 2)->nullable();
            $table->float('iva', 4, 2)->default(16.00)->comment('Porcenteje IVA');
//            $table->float('reserva_con_iva', 18, 2)->nullable();
            $table->smallInteger('lados')->default(2)->nullable()
                ->comment('Inicialmente no se sabe si ambos lados quedaran en la oficina');
/*            $table->float('compartido_con_iva', 18, 2)->nullable()
                ->comment('Compartido con otra oficina con IVA');
            $table->float('compartido_sin_iva', 18, 2)->nullable()
                ->comment('Compartido con otra oficina sin IVA');*/
            $table->float('porc_franquicia', 4, 2)
                ->default(10.00)->comment('Porcentaje para franquicia');
            $table->boolean('aplicar_porc_franquicia')->default(true);
/*            $table->float('franquicia_reservado_sin_iva', 18, 2)->nullable()
                ->comment('Monto sin iva');
            $table->float('franquicia_pagar_reportada', 18, 2)->nullable()
                ->comment('Monto depende del porc_franquicia y % reportado_casa_nacional');*/
            $table->float('reportado_casa_nacional', 4, 2)
                ->default(5.00)->comment('Porcentaje reportado a casa nacional');
            $table->float('porc_regalia', 4, 2)
                ->default(80.00)->comment('Porcentaje para regalia');
//            $table->float('regalia', 18, 2)->nullable();
            $table->float('sanaf_5_porciento', 18, 2)->nullable();
//            $table->float('oficina_bruto_real', 18, 2)->nullable();
//            $table->float('base_para_honorarios', 18, 2)->nullable();
            $table->float('porc_captador_prbr', 4, 2)
                ->default(20.00)->comment('Porcentaje para captador');
            $table->boolean('aplicar_porc_captador')->default(true);
//            $table->float('captador_prbr', 18, 2)->nullable();
            $table->float('porc_gerente', 4, 2)
                ->default(10.00)->comment('Porcentaje para gerente');
            $table->boolean('aplicar_porc_gerente')->default(true);
//            $table->float('gerente', 18, 2)->nullable();
            $table->float('porc_cerrador_prbr', 4, 2)
                ->default(20.00)->comment('Porcentaje para cerrador');
            $table->boolean('aplicar_porc_cerrador')->default(true);
//            $table->float('cerrador_prbr', 18, 2)->nullable();
            $table->float('porc_bonificacion', 4, 2)
                ->default(5.00)->comment('Porcentaje para bonificacion');
            $table->boolean('aplicar_porc_bonificacion')->default(true);
//            $table->float('bonificacion', 18, 2)->nullable();
            $table->float('comision_bancaria', 14, 2)->nullable()
                ->comment('Comision bancaria descontada');
            $table->string('numero_recibo', 30)->nullable()
                ->comment('Número del recibo');
            $table->unsignedInteger('asesor_captador_id');
            $table->foreign('asesor_captador_id')->references('id')->on('users');
            $table->string('asesor_captador', 160)->nullable()
                ->comment('Nombre del asesor captador, cuando es de otra oficina');
            $table->unsignedInteger('asesor_cerrador_id');
            $table->foreign('asesor_cerrador_id')->references('id')->on('users');
            $table->string('asesor_cerrador', 160)->nullable()
                ->comment('Nombre del asesor cerrador, cuando es de otra oficina');
            $table->string('pago_gerente', 100)->nullable()
                ->comment('Forma de pago al gerente');
            $table->string('factura_gerente', 100)->nullable()
                ->comment('Factura de pago al gerente');
            $table->string('pago_asesores', 100)->nullable()
                ->comment('Forma de pago a los asesores');
            $table->string('factura_asesores', 100)->nullable()
                ->comment('Factura de pago al(a los) asesor(es)');
            $table->string('pago_otra_oficina', 100)->nullable()
                ->comment('Forma de pago a la(s) otra(s) oficina(s)');
            $table->boolean('pagado_casa_nacional')->default(false);
            $table->enum('estatus_sistema_c21', ['V', 'P'])->default('P')
                ->comment('[V]endido, [P]endiente');
            $table->string('reporte_casa_nacional', 10)->nullable()
                ->comment('Número de reporte a casa nacional');
            $table->string('comentarios', 600)->nullable();
            $table->string('factura_AyS', 100)->nullable();
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
        Schema::dropIfExists('propiedads');
    }
}
