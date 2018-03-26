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
            <th scope="col">
                <a href="{{ route('clientes.orden', 'name') }}" class="btn btn-link">
                    Nombre
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'telefono') }}" class="btn btn-link">
                    Telefono
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'email') }}" class="btn btn-link">
                    Correo
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'created_at') }}" class="btn btn-link">
                    Contactado
                </a>
            </th>
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
            <td>
                {{ $diaSemana[$cliente->created_at->format('w')] }}
                {{ $cliente->created_at->format('d/m/Y') }}
                @if ('' != $cliente->user_borro and $cliente->user_borro != null)
                    [B]
                @endif
            </td>
            <td>
                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-link"><span class="oi oi-eye"></span></a>
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-link"><span class="oi oi-pencil"></span></a>

                @if (1 == Auth::user()->is_admin)
                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}
                    <button class="btn btn-link"><span class="oi-trash"></span></button>
                </form>
                @endif
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $clientes->links() }}
    @else
        <p>No hay clientes registrados.</p>
    @endif

@endsection