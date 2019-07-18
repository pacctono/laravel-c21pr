@extends('layouts.app')

@section('content')
<div>
    <form method="POST" class="form-horizontal" action="{{ route('propiedades.post') }}"
          onSubmit="return alertaCampoRequerido()">
          {{-- onSubmit="return confirm('Desde:|'+document.getElementById('fecha_desde').value+'|'+
                            'Captador:|'+document.getElementById('captador').value+'|');"> --}}
      {!! csrf_field() !!}

      <div class="form-group col-md-12">
      {{-- @foreach (['hoy', 'ayer', 'manana', 'esta_semana', 'semana_pasada', 'proxima_semana',
                'este_mes', 'mes_pasado', 'proximo_mes', 'todo', 'intervalo'] as $intervalo)
        <input type="radio" required name="periodo" id="_{{ $intervalo }}"
                value="{{ $intervalo }}"
        @if ($rPeriodo == $intervalo)
          checked
        @endif
        >
        <label>
        @if ('manana' == $intervalo)
        Mañana
        @else
        {{ str_replace('_', ' ', ucfirst($intervalo)) }}
        @endif
        </label>
      @endforeach
        <br> --}}
        <label>Fecha de reserva desde:</label>
        <input type="date" name="fecha_desde" id="fecha_desde" max="{{ now() }}"
            value="{{ old('fecha_desde', $fecha_desde) }}">
        {{-- $fecha_desde --}}
        <label>Hasta:</label>
        <input type="date" name="fecha_hasta" id="fecha_hasta" max="{{ now() }}"
            value="{{ old('fecha_hasta', $fecha_hasta) }}">
        @if (Auth::user()->is_admin)
        <select name="captador" id="captador">
        <option value="0">Captador</option>
          @foreach ($users as $user)
            <option value="{{ $user->id }}"
            @if (old("captador", $captador) == $user->id)
              selected
            @endif
            >
              {{ $user->name }}
            </option>
          @endforeach
        </select>
        @endif
        @if (Auth::user()->is_admin)
        <select name="cerrador" id="cerrador">
          <option value="0">Cerrador</option>
          @foreach ($users as $user)
            <option value="{{ $user->id }}"
            @if (old("cerrador", $cerrador) == $user->id)
              selected
            @endif
            >
              {{ $user->name }}
            </option>
          @endforeach
        </select>
        @endif
        <button type="submit" class="btn btn-success">Mostrar</button>
        <br>
        {{ $filas }} props:
        <spam class="alert-success" title="Precio">{{ $tPrecio }}</spam>
        <spam class="alert-info" title="Franquicia reservada sin IVA">
            {{ $tFranquiciaSinIva }}</spam>
        <spam class="alert-success" title="Franquicia reservada con IVA">
            {{ $tFranquiciaConIva }}</spam>
        <spam class="alert-info" title="Franquicia a pagar reportada">
            {{ $tFranquiciaPagarR }}</spam>
        <spam class="alert-success" title="Regalia">
            {{ $tRegalia }}</spam>
        <spam class="alert-info" title="Sanaf - 5%">
            {{ $tSanaf5PorCiento }}</spam>
        <spam class="alert-success" title="Oficina Bruto Real">
            {{ $tOficinaBrutoReal }}</spam>
        <spam class="alert-success" title="Base para los honorarios de los socios">
            {{ $tBaseHonorariosSo }}</spam>
        <spam class="alert-info" title="Base para los honorarios">
            {{ $tBaseParaHonorari }}</spam>
        <spam class="alert-success" title="Ingreso neto de la oficina">
            {{ $tIngresoNetoOfici }}</spam>
        <spam class="alert-info" title="Captador PRBR">
            {{ $tCaptadorPrbr }}</spam>
        <spam class="alert-success" title="Gerente">
            {{ $tGerente }}</spam>
        <spam class="alert-info" title="Cerrador PRBR">
            {{ $tCerradorPrbr }}</spam>
        <spam class="alert-success" title="Bonificaciones">
            {{ $tBonificaciones }}</spam>
        <spam class="alert-info" title="Comision bancaria">
            {{ $tComisionBancaria }}</spam>
      </div>
    </form>
</div>

    <div class="d-flex justify-content-between align-items-end mb-1">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('propiedades.create') }}" class="btn btn-primary">Crear Propiedad</a>
        </p>
    </div>
    @if ($propiedades->isNotEmpty())
    <table class="table table-striped table-hover table-bordered table-sm">
        <thead class="thead-dark">
        <tr>
            <!-- th scope="col">#</th -->
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'codigo') }}" class="btn btn-link">
                    C&oacute;digo
                </a>
            </th>
            <th scope="col" title="Fecha de reserva y
            Fecha de la firma">
                <a href="{{ route('propiedades.orden', 'fecha_reserva') }}" class="btn btn-link">
                    Fechas
                </a>
            </th>
            <th scope="col" title="Tipo de negociaci&oacute;n">
                <a href="{{ route('propiedades.orden', 'negociacion') }}" class="btn btn-link">
                    N
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'nombre') }}" class="btn btn-link">
                    Nombre
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('propiedades.orden', 'precio') }}" class="btn btn-link">
                    Precio
                </a>
            </th>
            <th scope="col" title="Lados">
                <a href="{{ route('propiedades.orden', 'lados') }}" class="btn btn-link">
                    L
                </a>
            </th>
            <th scope="col" title="Franquicia">
                Franquic
            </th>
            <th scope="col">
                Regalia
                SANAF-5%
            </th>
            <th scope="col">
                Montos
                Base
            </th>
            <th scope="col" title="Pago asesor captador, gerente y cerrador.">
                Comis
            </th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>

        <?php $var = 0; // Variable para manejar el estilo de cada fila. ?>

        @foreach ($propiedades as $propiedad)
        <?php $var++;   // Variable para manejar el estilo de cada fila. ?>
        <tr class="
        @if ('I' == $propiedad->estatus)
            table-light
        @elseif ('P' == $propiedad->estatus)
            table-warning
        @elseif ('S' == $propiedad->estatus)
            table-danger
        @else
            @if (0 == ($var % 2))
                table-primary
            @else
                table-info
            @endif
        @endif
        ">
            <td title="{{ $propiedad->id }}) {{ $propiedad->estatus_alfa }}
Reporte en casa nacional: {{ $propiedad->reporte_casa_nacional }}
Estatus en sistema C21: {{ $propiedad->estatus_c21_alfa.(($propiedad->pagado_casa_nacional)?' y PAGADO A CASA NACIONAL':'') }}
{{ (($propiedad->factura_AyS)?'Factura A & S: '.$propiedad->factura_AyS.'.':'') }}">
                <spam class="float-right"> {{ $propiedad->codigo }} </spam>
            </td>

            <td>
                <spam title="Fecha de reserva">{{ $propiedad->reserva_en }}</spam><br>
                <spam title="Fecha de la firma">{{ $propiedad->firma_en }}</spam>
            </td>

            <td title="{{ $propiedad->negociacion_alfa }}">
                {{ $propiedad->negociacion }}</td>

            <td title="{{ $propiedad->comentarios }}">{{ $propiedad->nombre }}</td>

            <td title="Comisi&oacute;n: {{ $propiedad->comision_p }}
 Reserva s/IVA(I):{{ $propiedad->reserva_sin_iva }};
                  IVA:{{ $propiedad->iva_p }};
 Reserva c/IVA(K):{{ $propiedad->reserva_con_iva }}"
            >

        <?php $propiedad->mMoZero = false;  // Si el monto es 0, mostrar 'espacio vacio'. ?>
        <?php $propiedad->espMonB = false;  // Eliminar espacio entre simbolo de la moneda y el monto. ?>

                <spam class="float-right">
                    {{ $propiedad->precio_ven }}
                </spam>
            </td>

            <td title="Compartido con otra oficina
                  s/IVA(M):{{ $propiedad->compartido_sin_iva }};
                  IVA:{{ $propiedad->iva_p }};
 Compartido c/IVA(L):{{ $propiedad->compartido_con_iva }}"
            >
                {{ $propiedad->lados }}
            </td>

            <td title="Porcentaje reportado a casa nacional: {{ $propiedad->reportado_casa_nacional_p }}">
                <spam class="float-right" title="Franquicia de reserva sin IVA(O) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_sin_iva }} 
                </spam><br>
                <spam class="float-right" title="Franquicia de reserva con IVA(P) ({{ $propiedad->porc_franquicia_p }})">
                    {{ $propiedad->franquicia_reservado_con_iva }}
                </spam><br>
                <spam class="float-right" title="Franquicia a pagar reportada(Q) ({{ $propiedad->reportado_casa_nacional_p }})">
                    {{ $propiedad->franquicia_pagar_reportada }}
                </spam>
            </td>

            <td>
                <spam class="float-right" title="Porcentaje de REGALIA(S):{{ $propiedad->porc_regalia_p }}">
                    {{ $propiedad->regalia }}
                </spam><br>
                <spam class="float-right" title="SANAF 5 Porciento(T)">
                    {{ $propiedad->sanaf_5_por_ciento }}
                </spam>
            </td>

            <td>
                <spam class="float-right" title="Oficina bruto real(U)">
                    {{ $propiedad->oficina_bruto_real }}
                </spam><br>
                <spam class="float-right" title="Base para honorarios socios(V)">
                    {{ $propiedad->base_honorarios_socios }}
                </spam><br>
                <spam class="float-right" title="Base para honorarios(W)">
                    {{ $propiedad->base_para_honorarios }}
                </spam><br>
                <spam class="float-right" title="Ingreso neto a oficina(AC)
{{ ($propiedad->numero_recibo)?('Recibo No.: '.$propiedad->numero_recibo):'' }}">
                    {{ $propiedad->ingreso_neto_oficina }}
                </spam>
            </td>

            <td title="Comisi&oacute;n del asesor captador
Comisi&oacute;n del gerente y
Comisi&oacute;n del asesor cerrador."
            >
                <spam class="float-right" title="Captador PRBR(X){{ $propiedad->nombre_captador }}">
                    {{ $propiedad->captador_prbr }}
                </spam><br>
                <spam class="float-right" title="Gerente(Y)">
                    {{ $propiedad->gerente }}
                </spam><br>
                <spam class="float-right" title="Cerrador PRBR(Z){{ $propiedad->nombre_cerrador }}">
                    {{ $propiedad->cerrador_prbr }}
                </spam><br>
                <spam class="float-right" title="Bonificaciones">
                    {{ $propiedad->bonificaciones }}
                </spam>
            </td>

            <td class="d-flex align-items-end">
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link" 
                        title="Mostrar los datos de esta propiedad.">
                    <span class="oi oi-eye"></span>
                </a>
                <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-link"
                        title="Editar los datos de esta propiedad.">
                    <span class="oi oi-pencil"></span>
                </a>

                @if (1 == Auth::user()->is_admin)
                <form action="{{ route('propiedades.destroy', $propiedad) }}" method="POST" 
                        class="form-inline mt-0 mt-md-0"
                        onSubmit="return confirm('Realmente, desea borrar (borrado lógico) los datos de esta propiedad de la base de datos?')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link" title="Borrar (lógico) propiedad.">
                        <span class="oi oi-trash" title="Borrar">
                        </span>
                    </button>
                </form>
                    @if ((4 <= $propiedad->resultado_id) and (7 >= $propiedad->resultado_id))
                    <a href="{{ route('agenda.emailcita', $propiedad) }}" class="btn btn-link"
                            title="Enviar correo a '{{ $propiedad->user->name }}', sobre cita con esta propiedad">
                        <span class="oi oi-envelope-closed"></span>
                    </a>
                    @endif
                @endif
            </td>
        </tr>

        <?php $propiedad->espMonB = true; // Restablecer variable cambiada antes de <precio> ?>

        @endforeach
        </tbody>
    </table>
    {{ $propiedades->links() }}
    @else
        <p>No hay propiedades registradas.</p>
    @endif

@endsection

@section('js')

<script>
function alertaCampoRequerido() {
  var fecha_desde = document.getElementById('fecha_desde').value;
  var fecha_hasta = document.getElementById('fecha_hasta').value;
  var captador = document.getElementById('captador').value;
  var cerrador = document.getElementById('cerrador').value;

  if (('' == fecha_desde) && (0 == captador) && (0 == cerrador)) {
    alert("Usted debe suministrar la fecha de reserva 'Desde' o el 'captador' o el 'cerrador'");
    return false;
  }
  return true;
}
</script>

@endsection