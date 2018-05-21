@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Contacto inicial: {{ $cliente->name }}</h4>
    <div class="card-body">
        <p>Este nombre ha contactado: <spam class="alert-info">{{ $cliente->veces_name }}
        @if (1 >= $cliente->veces_name)
                vez.
            @else
                veces.
            @endif
        </spam></p>
        <p>Telefono de contacto: <spam class="alert-info">
            0{{ substr($cliente->telefono, 0, 3) }}-{{ substr($cliente->telefono, 3) }}
        </spam></p>
        <p>Este telefono ha contactado: <spam class="alert-info">{{ $cliente->veces_telefono }}
        @if (1 >= $cliente->veces_telefono)
                vez.
            @else
                veces.
            @endif
        </spam></p>
        <p>Correo de contacto: <spam class="alert-info">{{ $cliente->email }}
        </spam></p>
        <p>Este correo ha contactado: <spam class="alert-info">{{ $cliente->veces_email }}
            @if (1 >= $cliente->veces_email)
                vez.
            @else
                veces.
            @endif
        </spam></p>
        <p>Atendido por: <spam class="alert-info">[{{ $cliente->user_id }}] {{ $cliente->user->name }}
        </spam></p>
        <p>Dirección: <spam class="alert-info">{{ $cliente->direccion }}
        </spam></p>
        <p>Desea: <spam class="alert-info">{{ $cliente->deseo->descripcion }}
        </spam></p>
        <p>Propiedad: <spam class="alert-info">{{ $cliente->propiedad->descripcion }}
        </spam></p>
        <p>Zona: <spam class="alert-info">{{ $cliente->zona->descripcion }}
        </spam></p>
        <p>Precio: <spam class="alert-info">{{ $cliente->precio->descripcion }}
        </spam></p>
        <p>Origen: <spam class="alert-info">{{ $cliente->origen->descripcion }}
        </spam></p>
        <p>Resultado: <spam class="alert-info">{{ $cliente->resultado->descripcion }}
        </spam></p>
        <p>Observaciones: <spam class="alert-info">{{ $cliente->observaciones }}
        </spam></p>
        @if ($cliente->user_borro != null)
            <p>Este cliente fue borrado por {{ $cliente->userBorro->name }}
                el {{ $diaSemana[$cliente->created_at->format('w')] }}, {{ $cliente->borrado_at->format('d/m/Y') }}.
            </p>
        @endif
        @if ($cliente->user_actualizo != null)
            <p>Este cliente fue actualizado por {{ $cliente->userActualizo->name }}
                el {{ $diaSemana[$cliente->created_at->format('w')] }}, {{ $cliente->updated_at->format('d/m/Y') }}.
            </p>
        @endif

        <p>
            <!-- a href="{{ action('ClienteController@index') }}">Regresar al listado de usuarios</a -->
            <a href="{{ route('clientes.index') }}" class="btn btn-link">Regresar al listado de clientes</a>
        </p>
    </div>
</div>
@endsection
