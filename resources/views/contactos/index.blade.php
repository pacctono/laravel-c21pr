@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('contactos.create') }}" class="btn btn-primary">Crear Contacto Inicial</a>
        </p>
    </div>

    @if ($contactos->isNotEmpty())
    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
            <!-- th scope="col">#</th -->
            <th scope="col">
                <a href="{{ route('contactos.orden', 'name') }}" class="btn btn-link">
                    Nombre
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('contactos.orden', 'telefono') }}" class="btn btn-link">
                    Telefono
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('contactos.orden', 'email') }}" class="btn btn-link">
                    Correo
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('contactos.orden', 'created_at') }}" class="btn btn-link">
                    Contactado
                </a>
            </th>
            @if (1 == Auth::user()->is_admin)
            <th scope="col">
                <a href="{{ route('contactos.orden', 'user_id') }}" class="btn btn-link">
                    Contactado por
                </a>
            </th>
            @endif
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($contactos as $contacto)
        <tr>
            <!-- th scope="row">{{ $contacto->id }}</th -->
            <td>{{ $contacto->name }}</td>
            <td>
                0{{ substr($contacto->telefono, 0, 3) }}
                -{{ substr($contacto->telefono, 3, 3) }}
                .{{ substr($contacto->telefono, 6) }}
            </td>
            <td>{{ $contacto->email }}</td>
            <td>
                {{ substr($diaSemana[$contacto->created_at->timezone('America/Caracas')->dayOfWeek], 0, 3) }}
                {{ $contacto->created_at->timezone('America/Caracas')->format('d/m/Y') }}
                @if ('' != $contacto->user_borro and $contacto->user_borro != null)
                    [B]
                @endif
            </td>
            @if (1 == Auth::user()->is_admin)
            <td>{{ $contacto->user->name }}</td>
            @endif
            <td class="d-flex align-items-end">
                <a href="{{ route('contactos.show', $contacto) }}" class="btn btn-link">
                    <span class="oi oi-eye"></span>
                </a>
                <a href="{{ route('contactos.edit', $contacto) }}" class="btn btn-link">
                    <span class="oi oi-pencil"></span>
                </a>

                @if (1 == Auth::user()->is_admin)
                <form action="{{ route('contactos.destroy', $contacto) }}" method="POST" 
                        class="form-inline mt-0 mt-md-0">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}
                    <button class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
                @endif
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $contactos->links() }}
    @else
        <p>No hay contactos iniciales registrados.</p>
    @endif

@endsection
