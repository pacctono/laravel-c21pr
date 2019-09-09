@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Contacto inicial:
        {{ $contacto->name }} el
        {{ $contacto->creado_dia_semana }}
        {{ $contacto->creado_con_hora }}.
        Ha contactado: <span class="alert-info">{{ $contacto->veces_name }}
        @if (1 == $contacto->veces_name)
                vez.
            @else
                veces.
            @endif
        </span>
    </h4>
    <div class="card-body">
        <p>Cédula de Identidad: <span class="alert-info">
            {{ $contacto->cedula_f }}
        </span></p>
        <p>Telefono de contacto: <span class="alert-info">
            {{ $contacto->telefono_f }}
        </span>.
        Este telefono ha contactado: <span class="alert-info">{{ $contacto->veces_telefono }}
        @if (1 == $contacto->veces_telefono)
                vez.
            @else
                veces.
            @endif
        </span></p>
        <p>Correo de contacto: <span class="alert-info">{{ $contacto->email }}
        </span>.
        Este correo ha contactado: <span class="alert-info">{{ $contacto->veces_email }}
            @if (1 == $contacto->veces_email)
                vez.
            @else
                veces.
            @endif
        </span></p>
        <p>Atendido por: <span class="alert-info">[{{ $contacto->user_id }}] {{ $contacto->user->name }}
        </span></p>
        <p>Dirección: <span class="alert-info">{{ $contacto->direccion }}
        </span></p>
        <p>Desea: <span class="alert-info">{{ $contacto->deseo->descripcion }}
        </span>
        Tipo: <span class="alert-info">{{ $contacto->Tipo->descripcion }}
        </span></p>
        <p>Zona: <span class="alert-info">{{ $contacto->zona->descripcion }}
        </span>
        Precio: <span class="alert-info">{{ $contacto->precio->descripcion }}
        </span>
        <p>Origen: <span class="alert-info">{{ $contacto->origen->descripcion }}
        </span></p>
        <p>Resultado:
            <span class="alert-info">{{ $contacto->resultado->descripcion }}
            </span>
            @if ((3 < $contacto->resultado->id) and
                (8 > $contacto->resultado->id))
                @if (null == $contacto->fecha_evento)
                <span class="alert-info">
                    OJO: NO FUE ASIGNADA LA FECHA. MUY EXTRAÑO.
                </span>
                @else
                el <span class="alert-info">
                    {{ $contacto->evento_dia_semana }}
                    {{ $contacto->evento_con_hora }}.
                </span>
                @endif
            @endif
        </p>
        <p>Observaciones: <span class="alert-info">{{ $contacto->observaciones }}
        </span></p>
        <p>Esta persona fue contactada hace:
            <span class="alert-info">
                {{ $contacto->tiempo_creado }}
            </span>
        </p>
        @if ($contacto->user_borro != null)
            <p>Este contacto inicial fue borrado por {{ $contacto->userBorro->name }} el
                {{ $contacto->borrado_dia_semana }}
                {{ $contacto->borrado_con_hora }}.
            </p>
        @endif
        @if ($contacto->user_actualizo != null)
            <p>Este contacto inicial fue actualizado por {{ $contacto->userActualizo->name }}
                el
                {{ $contacto->actualizado_dia_semana }}
                {{ $contacto->actualizado_con_hora }}.
            </p>
        @endif

        <p>
            <!-- a href="{{ action('ContactoController@index') }}">Regresar al listado de contactos iniciales</a -->
            @if ('' == $col_id)
	    <a href="{{ route($rutRetorno) }}" class="btn btn-link">
	    @else
	    <a href="{{ route($rutRetorno, [$contacto[$col_id], 'id']) }}" class="btn btn-link">
	    @endif
		Regresar
            </a>
        </p>
    </div>
</div>
@endsection
