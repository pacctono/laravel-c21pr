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
            $table->string('codigo', 8);
            $table->date('fecha_reserva')->nullable();
            $table->date('fecha_firma')->nullable();
            $table->enum('negociacion', ['V', 'A'])->comment('[V]enta,[A]lquiler');
            $table->string('nombre', 160);
            $table->boolean('exclusividad')->default(true)
                    ->comment('Define si la propiedad es dada en exclusiva para la negociacion.');
            $table->unsignedInteger('tipo_id')->default(6)->comment('Tipo de construccion');
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->float('metraje', 10, 2)->nullable()->comment('Metraje de construccion');
            $table->smallInteger('habitaciones')->nullable()->comment('Numero de habitaciones');
            $table->smallInteger('banos')->nullable()->comment('Numero de banos');
            $table->smallInteger('niveles')->nullable()->comment('Numero de niveles');
            $table->smallInteger('puestos')->nullable()
                ->comment('Numero de puestos de estacionamiento');
            $table->smallInteger('anoc')->nullable()->comment('A#os de construcccion');
            $table->unsignedInteger('caracteristica_id')->default(1)
                ->comment('Caracteristicas de la construcccion');
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');
            $table->string('descripcion', 5000)->nullable()
                ->comment('Descripcion de la propiedad');
            $table->string('direccion', 190)->nullable()
                ->comment('Direccion de la propiedad');
            $table->unsignedInteger('ciudad_id')->default(1);
            $table->foreign('ciudad_id')->references('id')->on('ciudades');
            $table->smallInteger('codigo_postal')->nullable()->comment('Codigo postal');
            $table->unsignedInteger('municipio_id')->default(1)->comment('municipio');
            $table->foreign('municipio_id')->references('id')->on('municipios');
            $table->unsignedInteger('estado_id')->default(2)->comment('estado geografico');
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->unsignedInteger('cliente_id')->default(1)
                ->comment('Informacion del cliente: cedula, rif, telefono, nombre, fecha de nacimiento');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->enum('estatus', ['A', 'I', 'P', 'C', 'S'])
                ->comment('[A]ctivo,[I]nmueble pendiente,[P]agos pendientes,inmueble [C]errado y pagos realizados,[S]negociacion caida')
                ->default('A');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('moneda', ['$', 'Eu', 'Bs', 'Rf'])->default('$')->comment('$,Eu,Bs,Rf');
            $table->float('precio', 20, 2)->comment('Precio del inmueble');
            $table->float('comision', 4, 2)->default(5.00)->comment('Porcentaje de comision');
            $table->float('iva', 4, 2)->default(16.00)->comment('Porcenteje IVA');
            $table->smallInteger('lados')->default(1)->nullable()
                ->comment('Inicialmente no se sabe si ambos lados quedaran en la oficina');
            $table->float('porc_franquicia', 4, 2)
                ->default(10.00)->comment('Porcentaje para franquicia');
/*            $table->boolean('aplicar_porc_franquicia')->default(False)
                ->comment('Si se selecciona, se calcula <Franquicia de reserva sin IVA(O)> ' . 
                            'como el %Franquicia[10%] de <Compartido sin IVA(M)>; sino se ' . 
                            'usa <Compartido con IVA(L)>.');*/
/*            $table->boolean('aplicar_porc_franquicia_pagar_reportada')->default(False)
                ->comment('Si se selecciona, <Franquicia a pagar reportada(Q)> ' .
                            'será el %Franquicia[10%] X %<Reportado a casa nacional(R)>[5%] ' .
                            'X <precio(G)> dividido entre 1 o 2, dependiendo de <lados(N)>; ' .
                            'sino se usa %<Franquicia>[10%] X <Compartido sin IVA(M)>.');*/
/*            $table->boolean('aplicar_franquicia_pagar_reportada_bruto')->default(False)
                ->comment('Si se selecciona, <Oficina Bruto Real(U)> es ' .
                            '<Compartido con IVA(L)> - <Franquicia a pagar reportada(Q)>; ' .
                            'si no, es <Compartido con IVA(L)> - <Franquicia de reserva sin IVA(O)>.');*/
            $table->float('reportado_casa_nacional', 4, 2)
                ->default(5.00)->comment('Porcentaje reportado a casa nacional');
            $table->float('porc_regalia', 4, 2)
                ->default(80.00)->comment('Porcentaje para regalia');
            $table->float('porc_compartido', 4, 2)->default(50.00)
                ->comment('Porcentaje del captador. El captador puede decidir que porcentaje va a dividir.');
            $table->float('porc_captador_prbr', 4, 2)
                ->default(20.00)->comment('Porcentaje para captador');
/*            $table->boolean('aplicar_porc_captador')->default(true)
                ->comment('Si se selecciona, se calcula <Captador PRBR(X)> ' .
                            'como el %Captador [20%] de <Oficina Bruto Real(U)>; ' .
                            'sino se usa una expresión más larga basada en ' .
                            '<Base para honorarios(W)[W*%-(0,2*W)+W*%*16%]>.');*/
            $table->float('porc_gerente', 4, 2)
                ->default(10.00)->comment('Porcentaje para gerente');
/*            $table->boolean('aplicar_porc_gerente')->default(true)
                ->comment('Si se selecciona, se calcula <Gerente(Y)> ' .
                            'como el %Gerente [10%] de <Oficina Bruto Real(U)>; ' .
                            'sino se calcula el % de <Base para honorarios(W)>[W*%-(0,2*W)+W*%*16%].');*/
            $table->float('porc_cerrador_prbr', 4, 2)
                ->default(20.00)->comment('Porcentaje para cerrador');
/*            $table->boolean('aplicar_porc_cerrador')->default(true)
                ->comment('Si se selecciona, se calcula <Cerrador PRBR(Z)> ' .
                            'como el %Cerrador [20%] de <Oficina Bruto Real(U)>; ' .
                            'sino se usa una expresión más larga basada en ' .
                            '<Base para honorarios(W)>[W*%-(0,2*W)+W*%*16%].');*/
            $table->float('porc_bonificacion', 4, 2)
                ->default(0.00)->comment('Porcentaje para bonificacion');
/*            $table->boolean('aplicar_porc_bonificacion')->default(False)
                ->comment('Si se selecciona, se calcula <Binificaciones> como el ' .
                            '%bonificacion[5%] de <Base para honorarios(W)>; si no es 0.00.');*/
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
            $table->enum('estatus_sistema_c21', ['V', 'A', 'P'])->default('P')
                ->comment('[V]endido,(A)ctivo,[P]endiente');
            $table->string('reporte_casa_nacional', 10)->nullable()
                ->comment('Número de reporte a casa nacional');
            $table->string('comentarios', 600)->nullable();
            $table->string('factura_AyS', 100)->nullable();
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
        Schema::dropIfExists('propiedads');
    }
}
