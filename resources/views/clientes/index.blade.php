@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">Crear Cliente</a>
        </p>
    </div>

    @if ($clientes->isNotEmpty())
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <!-- th scope="col">#</th -->
            <th scope="col">Nombre</th>
            <th scope="col">Telefono</th>
            <th scope="col">Correo</th>
            <th scope="col">Dirección</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($clientes as $cliente)
        <tr>
            <!-- th scope="row">{{ $cliente->id }}</th -->
            <td>{{ $cliente->name }}</td>
            <td>{{ $cliente->telefono }}</td>
            <td>{{ $cliente->email }}</td>
            <td>{{ $cliente->direccion }}</td>
            <td>
                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-link"><span class="oi oi-eye"></span></a>
                <!-- form action="{-- route('clientes.destroy', $cliente) --}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}
                    <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-link"><span class="oi oi-eye"></span></a>
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-link"><span class="oi oi-pencil"></span></a>
                    <button class="btn btn-link"><span class="oi-trash"></span></button>
                </form -->
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    @else
        <p>No hay clientes registrados.</p>
    @endif

@endsection