@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Contacto inicial:
        {{ $contacto->name }} el
        {{ $contacto->creado_dia_semana }}
        {{ $contacto->creado_con_hora }}.
        Ha contactado: <spam class="alert-info">{{ $contacto->veces_name }}
        @if (1 == $contacto->veces_name)
                vez.
            @else
                veces.
            @endif
        </spam>
    </h4>
    <div class="card-body">
        <p>Cédula de Identidad: <spam class="alert-info">
            {{ $contacto->cedula_f }}
        </spam></p>
        <p>Telefono de contacto: <spam class="alert-info">
            {{ $contacto->telefono_f }}
        </spam>.
        Este telefono ha contactado: <spam class="alert-info">{{ $contacto->veces_telefono }}
        @if (1 == $contacto->veces_telefono)
                vez.
            @else
                veces.
            @endif
        </spam></p>
        <p>Correo de contacto: <spam class="alert-info">{{ $contacto->email }}
        </spam>.
        Este correo ha contactado: <spam class="alert-info">{{ $contacto->veces_email }}
            @if (1 == $contacto->veces_email)
                vez.
            @else
                veces.
            @endif
        </spam></p>
        <p>Atendido por: <spam class="alert-info">[{{ $contacto->user_id }}] {{ $contacto->user->name }}
        </spam></p>
        <p>Dirección: <spam class="alert-info">{{ $contacto->direccion }}
        </spam></p>
        <p>Desea: <spam class="alert-info">{{ $contacto->deseo->descripcion }}
        </spam>
        Tipo: <spam class="alert-info">{{ $contacto->Tipo->descripcion }}
        </spam></p>
        <p>Zona: <spam class="alert-info">{{ $contacto->zona->descripcion }}
        </spam>
        Precio: <spam class="alert-info">{{ $contacto->precio->descripcion }}
        </spam>
        <p>Origen: <spam class="alert-info">{{ $contacto->origen->descripcion }}
        </spam></p>
        <p>Resultado:
            <spam class="alert-info">{{ $contacto->resultado->descripcion }}
            </spam>
            @if ((3 < $contacto->resultado->id) and
                (8 > $contacto->resultado->id))
                @if (null == $contacto->fecha_evento)
                <spam class="alert-info">
                    OJO: NO FUE ASIGNADA LA FECHA. MUY EXTRAÑO.
                </spam>
                @else
                el <spam class="alert-info">
                    {{ $contacto->evento_dia_semana }}
                    {{ $contacto->evento_con_hora }}.
                </spam>
                @endif
            @endif
        </p>
        <p>Observaciones: <spam class="alert-info">{{ $contacto->observaciones }}
        </spam></p>
        <p>Esta persona fue contactada hace:
            <spam class="alert-info">
                {{ $contacto->tiempo_creado }}
            </spam>
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
