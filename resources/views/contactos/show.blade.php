@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Contacto inicial:
        {{ $contacto->name }} el
        {{ $diaSemana[$contacto->created_at->timezone('America/Caracas')->dayOfWeek] }}
        {{ $contacto->created_at->timezone('America/Caracas')->format('d/m/Y') }}.
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
            {{ $contacto->cedula }}
        </spam></p>
        <p>Telefono de contacto: <spam class="alert-info">
            0{{ substr($contacto->telefono, 0, 3) }}-{{ substr($contacto->telefono, 3, 3) }}-{{ substr($contacto->telefono, 6) }}
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
        Propiedad: <spam class="alert-info">{{ $contacto->propiedad->descripcion }}
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
            @if ((3 < $contacto->resultado->id) and (8 > $contacto->resultado->id))
            el <spam class="alert-info">
                {{ $diaSemana[$contacto->fecha_evento->dayOfWeek] }}
                {{ $contacto->fecha_evento->format('d/m/Y H:i (h:i a)') }}
            </spam>
            @endif
        </p>
        <p>Observaciones: <spam class="alert-info">{{ $contacto->observaciones }}
        </spam></p>
        @if ($contacto->user_borro != null)
            <p>Este contacto inicial fue borrado por {{ $contacto->userBorro->name }}
                el {{ $diaSemana[$contacto->borrado_en->timezone('America/Caracas')->format('w')] }},
                    {{ $contacto->borrado_en->format('d/m/Y H:i a') }}.
            </p>
        @endif
        @if ($contacto->user_actualizo != null)
            <p>Este contacto inicial fue actualizado por {{ $contacto->userActualizo->name }}
                el {{ $diaSemana[$contacto->updated_at->timezone('America/Caracas')->format('w')] }},
                    {{ $contacto->updated_at->format('d/m/Y H:i a') }}.
            </p>
        @endif

        <p>
            <!-- a href="{{ action('ContactoController@index') }}">Regresar al listado de contactos iniciales</a -->
            <a href="{{ route('contactos.index') }}" class="btn btn-link">Regresar al listado de contactos iniciales</a>
        </p>
    </div>
</div>
@endsection
