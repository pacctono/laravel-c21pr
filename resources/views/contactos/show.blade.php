@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header ft-grande">
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
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4">
                C&eacute;dula de Identidad:
                <span class="alert-info">
                    {{ $contacto->cedula_f }}
                </span>
            </div>
        </div>
        <div class="row bg-suave">
            <div class="col-lg-4">
                Telefono de contacto:
                <span class="alert-info">
                    {{ $contacto->telefono_f }}
                </span>.
            </div>
            <div class="col-lg">
                Este telefono ha contactado:
                <span class="alert-info">{{ $contacto->veces_telefono }}
                @if (1 == $contacto->veces_telefono)
                    vez.
                @else
                    veces.
                @endif
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
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
        <div class="row bg-suave">
            <div class="col-lg-4">
                Atendido por:
                <span class="alert-info">
                    [{{ $contacto->user_id }}] {{ $contacto->user->name }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                Dirección:
                <span class="alert-info">
                    {{ $contacto->direccion }}
                </span>
            </div>
        </div>
        <div class="row bg-suave">
            <div class="col-lg-4">
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
        <div class="row">
            <div class="col-lg-4">
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
        <div class="row bg-suave">
            <div class="col-lg-4">
                Origen:
                <span class="alert-info">
                    {{ $contacto->origen->descripcion }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
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
        <div class="row bg-suave">
            <div class="col-lg-8">
                Observaciones:
                <span class="alert-info">
                    {{ $contacto->observaciones }}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                Esta persona fue contactada hace:
                <span class="alert-info">
                    {{ $contacto->tiempo_creado }}
                </span>
            </div>
        </div>
    @if ($contacto->user_borro != null)
        <div class="row bg-suave">
            <div class="col-lg-8">
                Este contacto inicial fue borrado por {{ $contacto->userBorro->name }} el
                {{ $contacto->borrado_dia_semana }}
                {{ $contacto->borrado_con_hora }}.
            </div>
        </div>
    @endif
    @if ($contacto->user_actualizo != null)
        <div class="row">
            <div class="col-lg-8">
                Este contacto inicial fue actualizado por {{ $contacto->userActualizo->name }}
                el
                {{ $contacto->actualizado_dia_semana }}
                {{ $contacto->actualizado_con_hora }}.
            </div>
        </div>
    @endif

        <div class="row">
            <div class="col-lg-4">
            <!-- a href="{{ action('ContactoController@index') }}">Regresar al listado de contactos iniciales</a -->
            @if ('' == $col_id)
        	    <a href="{{ route($rutRetorno) }}" class="btn btn-link">
	        @else
	            <a href="{{ route($rutRetorno, [$contacto[$col_id], 'id']) }}" class="btn btn-link">
	        @endif
		            Regresar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
