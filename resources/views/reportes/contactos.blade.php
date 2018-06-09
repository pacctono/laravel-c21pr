@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

	{{-- <p>
            <a href="{{ route('contactos.create') }}" class="btn btn-primary">Crear Contacto Inicial</a>
        </p> --}}
    </div>

    @if ($contactos->isNotEmpty())
    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col">
                <a href="{{ route($rutRetorno, [$id, 'name']) }}" class="btn btn-link">
                    Nombre
                </a>
            </th>
            <th scope="col">
                <a href="{{ route($rutRetorno, [$id, 'telefono']) }}" class="btn btn-link">
                    Telefono
                </a>
            </th>
            <th scope="col">
                <a href="{{ route($rutRetorno, [$id, 'email']) }}" class="btn btn-link">
                    Correo
                </a>
            </th>
            <th scope="col">
                <a href="{{ route($rutRetorno, [$id, 'created_at']) }}" class="btn btn-link">
                    Contactado
                </a>
            </th>
            <th scope="col">
                <a href="{{ route($rutRetorno, [$id, 'user_id']) }}" class="btn btn-link">
                    Contactado por
                </a>
            </th>
            <th scope="col">Ac.</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($contactos as $contacto)
        <tr>
            <td>{{ $contacto->name }}</td>
            <td>
                {{ $contacto->telefono_f }}
            </td>
            <td>{{ $contacto->email }}</td>
            <td>
                {{ $contacto->creado_dia_semana }}
                {{ $contacto->creado_en }}
                @if ('' != $contacto->user_borro and $contacto->user_borro != null)
                    [B]
                @endif
            </td>
            <td>{{ $contacto->user->name }}</td>
            <td class="d-flex align-items-end">
                <a href="{{ route('contactos.muestra', [$contacto, $rutRetorno]) }}" class="btn btn-link">
                    <span class="oi oi-eye"></span>
                </a>
            </td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    <a href="{{ route($tipo) }}" class="btn btn-link">
                        Volver
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>
    {{ $contactos->links() }}
    @else
        <p>No hay contactos iniciales registrados.</p>
    @endif

@endsection
