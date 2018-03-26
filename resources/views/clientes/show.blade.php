@extends('layouts.app')

@section('content')
    <div class="card">
        <h4 class="card-header">Cliente: {{ $cliente->name }}</h4>
        <div class="card-body">
            <p>Este nombre ha contactado: {{ $cliente->veces_name }}
            @if (1 >= $cliente->veces_name)
                    vez.
                @else
                    veces.
                @endif
            </p>
            <p>Telefono del cliente: 0{{ substr($cliente->telefono, 0, 3) }}-{{ substr($cliente->telefono, 3) }}</p>
            <p>Este telefono ha contactado: {{ $cliente->veces_telefono }}
            @if (1 >= $cliente->veces_telefono)
                    vez.
                @else
                    veces.
                @endif
            </p>
            <p>Correo del cliente: {{ $cliente->email }}</p>
            <p>Este correo ha contactado: {{ $cliente->veces_email }}
                @if (1 >= $cliente->veces_email)
                    vez.
                @else
                    veces.
                @endif
            </p>
            <p>Atendido por: [{{ $cliente->user_id }}] {{ $cliente->user->name }}</p>
            <p>Dirección: {{ $cliente->direccion }}</p>
            <p>Desea: {{ $cliente->deseo->descripcion }}</p>
            <p>Propiedad: {{ $cliente->propiedad->descripcion }}</p>
            <p>Zona: {{ $cliente->zona->descripcion }}</p>
            <p>Precio: {{ $cliente->precio->descripcion }}</p>
            <p>Origen: {{ $cliente->origen->descripcion }}</p>
            <p>Resultado: {{ $cliente->resultado->descripcion }}</p>
            <p>Observaciones: {{ $cliente->observaciones }}</p>
            @if ($cliente->user_borro != null)
                <p>Este cliente fue borrado por {{ $cliente->userBorro->name }} el {{ $cliente->borrado_en }}.
                </p>
            @endif
            @if ($cliente->user_actualizo != null)
                <p>Este cliente fue actualizado por {{ $cliente->userActualizo->name }} el {{ $cliente->updated_at->format('d/m/Y') }}.
                </p>
            @endif

            <p>
                <!-- a href="{{ action('ClienteController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ url('/clientes') }}" class="btn btn-link">Regresar al listado de clientes</a>
            </p>
        </div>
    </div>
@endsection