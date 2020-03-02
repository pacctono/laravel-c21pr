@extends('layouts.app')

@section('content')
<div class="card">
@if (isset($alertar))
@if (1 == $alertar)
    <script>alert("Fue enviado el correo con la 'Oferta de Servicio' al contacto inicial.");</script>
{{--@elseif (2 == $alertar)
    <script>alert('El correo fue enviado al asesor');</script>--}}
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo con la 'Oferta de Servcio' al contacto inicial. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif (0 > $alertar)
@endif (isset($alertar))
    <div class="row card-header ft-grande m-0 p-0">
        <div class="col-lg-10 m-0 py-0 px-1">
            Contacto inicial:
            {{ $contacto->name }} el
            {{ $contacto->creado_dia_semana }}
            {{ $contacto->creado_con_hora }}.
            Ha contactado:
            <span class="alert-info">{{ $contacto->veces_name }}
        @if (1 == $contacto->veces_name)
                vez.
        @else
                veces.
        @endif
            </span>
        </div>
        <div class="col-lg-2 m-0 py-0 px-1">
            <a href="{{ route('contactos.create') }}" class="btn btn-primary m-0 py-0 px-1"
                title="Dejar esta p&aacute;gina e ir a crear un nuevo Contacto Inicial">
                Crear Contacto Inicial
            </a>
        </div>
    </div>
    <div class="card-body m-0 p-0">
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                C&eacute;dula de Identidad:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->cedula_f }}
                </span>
            </div>
        </div>
        <div class="row bg-suave my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Telefono de contacto:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->telefono_f }}
                </span>.
            </div>
            <div class="m-0 py-0 px-1">
                Este telefono ha contactado:
                <span class="alert-info m-0 p-0">{{ $contacto->veces_telefono }}
                @if (1 == $contacto->veces_telefono)
                    vez.
                @else
                    veces.
                @endif
                </span>
            </div>
        @if ($contacto->otro_telefono)
            <div class="m-0 py-0 px-1">
                Otro telefono:<span class="alert-info m-0 p-0">
                    {{ $contacto->otro_telefono }}
                </span>
            </div>
        @endif ($contacto->otro_telefono)
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Correo de contacto:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->email }}
                </span>.
            </div>
            <div class="col-lg m-0 py-0 px-1">
                Este correo ha contactado:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->veces_email }}
                @if (1 == $contacto->veces_email)
                    vez.
                @else
                    veces.
                @endif
                </span>
            </div>
        </div>
        <div class="row bg-suave my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Atendido por:
                <span class="alert-info m-0 p-0|">
                    [{{ $contacto->user_id }}] {{ $contacto->user->name }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Dirección:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->direccion }}
                </span>
            </div>
        </div>
        <div class="row bg-suave my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Desea:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->deseo->descripcion }}
                </span>
            </div>
            <div class="col-lg m-0 py-0 px-1">
                Tipo:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->Tipo->descripcion }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Zona:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->zona->descripcion }}
                </span>
            </div>
            <div class="col-lg m-0 py-0 px-1">
                Precio:
                <span class="alert-info m-0 p-0">
                @if ((1 == $contacto->deseo_id) or (2 == $contacto->deseo_id))
                    {{ $contacto->price->descripcion }}
                    {{-- Entre {{ $contacto->price->menor }} y {{ $contacto->price->mayor --}}
                @elseif ((3 == $contacto->deseo_id) or (4 == $contacto->deseo_id))
                    {{ $contacto->price->descripcion_alquiler }}
                @else ((1 == $contacto->deseo_id) or (2 == $contacto->deseo_id))
                    &nbsp;
                @endif ((1 == $contacto->deseo_id) or (2 == $contacto->deseo_id))
                </span>
            </div>
        </div>
        <div class="row bg-suave my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Origen:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->origen->descripcion }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Resultado:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->resultado->descripcion }}
                </span>
            </div>
            @if ((3 < $contacto->resultado->id) and
                (8 > $contacto->resultado->id))
            <div class="col-lg m-0 py-0 px-1">
                @if (null == $contacto->fecha_evento)
                <span class="alert-info m-0 p-0">
                    OJO: NO FUE ASIGNADA LA FECHA. MUY EXTRAÑO.
                </span>
                @else
                el
                <span class="alert-info m-0 p-0">
                    {{ $contacto->evento_dia_semana }}
                    {{ $contacto->evento_con_hora }}.
                </span>
                @endif
            </div>
            @endif
        </div>
        <div class="row bg-suave my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Observaciones:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->observaciones }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Esta persona fue contactada hace:
                <span class="alert-info m-0 p-0">
                    {{ $contacto->tiempo_creado }}
                </span>
            </div>
        </div>
    @if ($contacto->user_borro != null)
        <div class="row bg-suave my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Este contacto inicial fue borrado por {{ $contacto->userBorro->name }} el
                {{ $contacto->borrado_dia_semana }}
                {{ $contacto->borrado_con_hora }}.
            </div>
        </div>
    @endif
    @if ($contacto->user_actualizo != null)
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Este contacto inicial fue actualizado por {{ $contacto->userActualizo->name }}
                el
                {{ $contacto->actualizado_dia_semana }}
                {{ $contacto->actualizado_con_hora }}.
            </div>
        </div>
    @endif

        <div class="row my-1 mx-0 p-0">
            @if ('' == $col_id)
            <form method="POST" class="form-horizontal" action="{{ url('clientes') }}">
                {!! csrf_field() !!}

            <div class="form-row m-0 py-0 px-1">  {{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
                <input type="hidden" name="cedula" id="cedula" value="{{ $contacto->cedula }}">
                <input type="hidden" name="name" id="name" value="{{ $contacto->name }}">
                <div class="form-group form-inline m-0 p-0 tipoCliente">
                    <label class="control-label m-0 py-0 px-1" for="tipo">*Tipo</label>
                    <select class="form-control form-control-sm m-0 py-0 px-1" name="tipo" id="tipo">
                        <option class="m-0 py-0 px-1" value="">Tipo de cliente</option>
                    @foreach ($tipos as $opcion => $muestra)
                        <option class="m-0 py-0 px-1" value="{{$opcion}}"
                        @if (old('tipo') == $opcion)
                            selected
                        @endif
                            >{{$muestra}}</option>
                    @endforeach
                    </select>
                </div>
                <input type="hidden" name="ddn" id="ddn" value="{{ substr($contacto->telefono, 0, 3) }}">
                <input type="hidden" name="telefono" id="telefono" value="{{ substr($contacto->telefono, 3) }}">
                <input type="hidden" name="otro_telefono" id="otro_telefono" value="{{ $contacto->otro_telefono }}">
                <input type="hidden" name="email" id="email" value="{{ $contacto->email }}">
                <input type="hidden" name="direccion" id="email" value="{{ $contacto->direccion }}">
                <input type="hidden" name="observaciones" id="email" value="{{ $contacto->observaciones }}">
                <input type="hidden" name="contacto_id" id="email" value="{{ $contacto->id }}">
                <div class="form-group form-inline m-0 p-0">
                    <button type="submit" class="btn btn-success m-0 p-1" id="convertir">
                        convertir Contacto Inicial a Cliente
                    </button>
                </div>
            </div>
            </form>
            @endif ('' == $col_id)
            <div class="m-0 py-0 px-1">
            <!-- a href="{{ action('ContactoController@index') }}">Regresar al listado de contactos iniciales</a -->
            @if ('' == $col_id)
        	    <a href="{{ route($rutRetorno) }}" class="btn btn-link m-0 p-1">
	        @else
                <a href="{{ route($rutRetorno, [$contacto[$col_id], 'id']) }}"
                    class="btn btn-link m-0 p-1">
            @endif ('' == $col_id)
		            Regresar
                </a>
            @if (($contacto->email) and ((2 == $contacto->deseo_id) or (4 == $contacto->deseo_id)))
                <a href="{{ route('contacto.correo', [$contacto, 2]) }}"
                        class="btn btn-link m-0 p-1"
                        title="Enviar correo con la 'Oferta de Servicio' a este contacto inicial.">
                    Oferta de Servicio
                </a>
            @endif (($contacto->email) and ((2 == $contacto->deseo_id) or (4 == $contacto->deseo_id)))
            </div>
        </div>
    </div>
</div>

@if ($propiedades->isNotEmpty())
    <table
    @if (!isset($accion) or ('html' == $accion))
        class="table table-striped table-hover table-bordered m-0 p-0"
    @else (!isset($accion) or ('html' == $accion))
        class="center"
    @endif (!isset($accion) or ('html' == $accion))
    >
        <thead class="thead-dark my-0 py-0">
        <tr
        @if ((isset($accion) and ('html' != $accion)))
            class="encabezado"
        @else ((isset($accion) and ('html' != $accion)))
            class="m-0 p-0"
        @endif ((isset($accion) and ('html' != $accion)))
        >
            <caption class="h2 font-weight-bold m-0 p-0"
                style="caption-side:top;text-align:center">
                Propiedades disponibles
            </caption>
            <th class="m-0 p-0" scope="col">
                C&oacute;digo
            </th>
            <th class="m-0 p-0" scope="col">
                Nombre
            </th>
            <th class="m-0 p-0" scope="col">
                Precio
            </th>
            <th class="m-0 p-0" scope="col">
                Ciudad
            </th>
            <th class="m-0 p-0" scope="col">
                Captador
            </th>
            <th class="m-0 p-0" scope="col">
                Mostrar
            </th>
        </tr>
        @foreach ($propiedades as $propiedad)
        <tr class="table-success m-0 p-0">
            <td class="text-right m-0 py-0 px-1">
                {{ $propiedad->codigo }}
            </td>
            <td class="m-0 py-0 px-1">
                {{ $propiedad->nombre }}
            </td>
            <td class="m-0 py-0 px-1">
                <span class="float-right" title="Precio del inmueble">
                    {{ $propiedad->precio_ven }}
                </span>
            </td>
            <td class="m-0 py-0 px-1">
                {{ $propiedad->ciudad->descripcion }}
            </td>
            <td class="m-0 py-0 px-1">
                {{ (1<$propiedad->asesor_captador_id)?$propiedad->captador->name:$propiedad->asesor_captador }}
            </td>
            @if (!isset($accion) or ('html' == $accion))
            <td class="m-0 py-0 px-1" class="d-flex align-items-end">
                <a href="{{ route('propiedades.show', $propiedad) }}" class="btn btn-link m-0 p-0" 
                        title="Mostrar los datos de la propiedad ({{ $propiedad->nombre }}).">
                    <span class="oi oi-eye"></span>
                </a>
            </td>
            @endif (!isset($accion) or ('html' == $accion))
        </tr>
        @endforeach
        </tbody>
    </table>
@endif ($propiedades->isNotEmpty())
@endsection

@section('js')
<script>
    $(document).ready(function(){
        $("div.tipoCliente").hide();  // Clase "tipoCliente"

        $("#convertir").click(function(ev){         // Id "convertir"
            $("div.tipoCliente").show();  // Clase "tipoCliente"
            if ('' == $("#tipo").val()) {
                ev.preventDefault();
                alert("Para 'convertir' un 'Contacto inicial' a 'cliente' debe seleccionar el 'tipo de cliente'");
                $("#tipo").focus();
            //} else {
            }
        })
    })
</script>

@endsection
