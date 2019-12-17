@extends('layouts.app')

@section('content')
<div class="card">
    <div class="row card-header ft-grande">
        <div class="col-lg-10">
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
        <div class="col-lg-2">
            <a href="{{ route('contactos.create') }}" class="btn btn-primary"
                title="Dejar esta p&aacute;gina e ir a crear un nuevo Contacto Inicial">
                Crear Contacto Inicial
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                C&eacute;dula de Identidad:
                <span class="alert-info">
                    {{ $contacto->cedula_f }}
                </span>
            </div>
        </div>
        <div class="row bg-suave my-1 py-1">
            <div class="mx-1 px-2">
                Telefono de contacto:
                <span class="alert-info">
                    {{ $contacto->telefono_f }}
                </span>.
            </div>
            <div class="mx-1 px-2">
                Este telefono ha contactado:
                <span class="alert-info">{{ $contacto->veces_telefono }}
                @if (1 == $contacto->veces_telefono)
                    vez.
                @else
                    veces.
                @endif
                </span>
            </div>
        @if ($contacto->otro_telefono)
            <div class="mx-1 px-2">
                Otro telefono:<span class="alert-info">
                    {{ $contacto->otro_telefono }}
                </span>
            </div>
        @endif ($contacto->otro_telefono)
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Correo de contacto:
                <span class="alert-info">
                    {{ $contacto->email }}
                </span>.
            </div>
            <div class="col-lg">
                Este correo ha contactado:
                <span class="alert-info">
                    {{ $contacto->veces_email }}
                @if (1 == $contacto->veces_email)
                    vez.
                @else
                    veces.
                @endif
                </span>
            </div>
        </div>
        <div class="row bg-suave my-1 py-1">
            <div class="mx-1 px-2">
                Atendido por:
                <span class="alert-info">
                    [{{ $contacto->user_id }}] {{ $contacto->user->name }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Dirección:
                <span class="alert-info">
                    {{ $contacto->direccion }}
                </span>
            </div>
        </div>
        <div class="row bg-suave my-1 py-1">
            <div class="mx-1 px-2">
                Desea:
                <span class="alert-info">
                    {{ $contacto->deseo->descripcion }}
                </span>
            </div>
            <div class="col-lg">
                Tipo:
                <span class="alert-info">
                    {{ $contacto->Tipo->descripcion }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Zona:
                <span class="alert-info">
                    {{ $contacto->zona->descripcion }}
                </span>
            </div>
            <div class="col-lg">
                Precio:
                <span class="alert-info">
                    {{ $contacto->precio->descripcion }}
                </span>
            </div>
        </div>
        <div class="row bg-suave my-1 py-1">
            <div class="mx-1 px-2">
                Origen:
                <span class="alert-info">
                    {{ $contacto->origen->descripcion }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Resultado:
                <span class="alert-info">
                    {{ $contacto->resultado->descripcion }}
                </span>
            </div>
            @if ((3 < $contacto->resultado->id) and
                (8 > $contacto->resultado->id))
            <div class="col-lg">
                @if (null == $contacto->fecha_evento)
                <span class="alert-info">
                    OJO: NO FUE ASIGNADA LA FECHA. MUY EXTRAÑO.
                </span>
                @else
                el
                <span class="alert-info">
                    {{ $contacto->evento_dia_semana }}
                    {{ $contacto->evento_con_hora }}.
                </span>
                @endif
            </div>
            @endif
        </div>
        <div class="row bg-suave my-1 py-1">
            <div class="mx-1 px-2">
                Observaciones:
                <span class="alert-info">
                    {{ $contacto->observaciones }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Esta persona fue contactada hace:
                <span class="alert-info">
                    {{ $contacto->tiempo_creado }}
                </span>
            </div>
        </div>
    @if ($contacto->user_borro != null)
        <div class="row bg-suave my-1 py-1">
            <div class="mx-1 px-2">
                Este contacto inicial fue borrado por {{ $contacto->userBorro->name }} el
                {{ $contacto->borrado_dia_semana }}
                {{ $contacto->borrado_con_hora }}.
            </div>
        </div>
    @endif
    @if ($contacto->user_actualizo != null)
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Este contacto inicial fue actualizado por {{ $contacto->userActualizo->name }}
                el
                {{ $contacto->actualizado_dia_semana }}
                {{ $contacto->actualizado_con_hora }}.
            </div>
        </div>
    @endif

        <div class="row my-1 py-1">
            @if ('' == $col_id)
            <form method="POST" class="form-horizontal" action="{{ url('clientes') }}">
                {!! csrf_field() !!}

            <div class="form-row my-0 py-0">  {{-- margen(m) arriba y abajo(y) 0 y padding(p) arriba y abajo(y) 0(0) --}}
                <input type="hidden" name="cedula" id="cedula" value="{{ $contacto->cedula }}">
                <input type="hidden" name="name" id="name" value="{{ $contacto->name }}">
                <div class="form-group form-inline mx-1 px-2 tipoCliente">
                    <label class="control-label" for="tipo">*Tipo</label>
                    <select class="form-control form-control-sm" name="tipo" id="tipo">
                        <option value="">Tipo de cliente</option>
                    @foreach ($tipos as $opcion => $muestra)
                        <option value="{{$opcion}}"
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
                <div class="form-group form-inline mx-1 px-2">
                    <button type="submit" class="btn btn-success" id="convertir">
                        convertir Contacto Inicial a Cliente
                    </button>
                </div>
            </div>
            </form>
            @endif ('' == $col_id)
            <div class="mx-1 px-2">
            <!-- a href="{{ action('ContactoController@index') }}">Regresar al listado de contactos iniciales</a -->
            @if ('' == $col_id)
        	    <a href="{{ route($rutRetorno) }}" class="btn btn-link">
	        @else
	            <a href="{{ route($rutRetorno, [$contacto[$col_id], 'id']) }}" class="btn btn-link">
            @endif ('' == $col_id)
		            Regresar
                </a>
            </div>
        </div>
    </div>
</div>
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