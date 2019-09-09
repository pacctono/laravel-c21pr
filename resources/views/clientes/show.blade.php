@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Contacto inicial: {{ $cliente->name }}</h4>
    <div class="card-body">
        <p>Este nombre ha contactado: <span class="alert-info">{{ $cliente->veces_name }}
        @if (1 >= $cliente->veces_name)
                vez.
            @else
                veces.
            @endif
        </span></p>
        <p>Telefono de contacto: <span class="alert-info">
            0{{ substr($cliente->telefono, 0, 3) }}-{{ substr($cliente->telefono, 3) }}
        </span></p>
        <p>Este telefono ha contactado: <span class="alert-info">{{ $cliente->veces_telefono }}
        @if (1 >= $cliente->veces_telefono)
                vez.
            @else
                veces.
            @endif
        </span></p>
        <p>Correo de contacto: <span class="alert-info">{{ $cliente->email }}
        </span></p>
        <p>Este correo ha contactado: <span class="alert-info">{{ $cliente->veces_email }}
            @if (1 >= $cliente->veces_email)
                vez.
            @else
                veces.
            @endif
        </span></p>
        <p>Atendido por: <span class="alert-info">[{{ $cliente->user_id }}] {{ $cliente->user->name }}
        </span></p>
        <p>Dirección: <span class="alert-info">{{ $cliente->direccion }}
        </span></p>
        <p>Desea: <span class="alert-info">{{ $cliente->deseo->descripcion }}
        </span></p>
        <p>Tipo: <span class="alert-info">{{ $cliente->tipo->descripcion }}
        </span></p>
        <p>Zona: <span class="alert-info">{{ $cliente->zona->descripcion }}
        </span></p>
        <p>Precio: <span class="alert-info">{{ $cliente->precio->descripcion }}
        </span></p>
        <p>Origen: <span class="alert-info">{{ $cliente->origen->descripcion }}
        </span></p>
        <p>Resultado: <span class="alert-info">{{ $cliente->resultado->descripcion }}
        </span></p>
        <p>Observaciones: <span class="alert-info">{{ $cliente->observaciones }}
        </span></p>
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
