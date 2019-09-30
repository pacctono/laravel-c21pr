@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

	{{-- <p>
            <a href="{{ route('contactos.create') }}" class="btn btn-primary">
                Crear Contacto Inicial
            </a>
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
        @if (!$movil)
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
            <th scope="col">Acción</th>
        @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($contactos as $contacto)
        <tr>
            <td>
            @if ($movil)
                <a href="{{ route('contactos.muestra', [$contacto, $rutRetorno]) }}" class="btn btn-link">
                    {{ $contacto->name }}
                </a>
            @else ($movil)
                {{ $contacto->name }}
            @endif ($movil)
            </td>
            <td>
                {{ $contacto->telefono_f }}
            </td>
        @if (!$movil)
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
                @if ((4 <= $contacto->resultado_id) and (7 >= $contacto->resultado_id))
                    <a href="{{ route('agenda.emailcita', $contacto) }}" class="btn btn-link"
                            title="Enviar correo a '{{ $contacto->user->name }}' con esta cita.">
                        <span class="oi oi-envelope-closed"></span>
                    </a>
                @endif
            </td>
        @endif (!$movil)
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <a href="{{ route($tipo) }}" class="btn btn-link">
                        Volver
                    </a>
                </td>
                <td colspan="4">
                    <a href="{{-- route('agenda.emailtodascitas', $tipo) --}}" class="btn btn-link">
                        Enviar correo{{ (('users'==$tipo)?'':', a los asesores, ') }}
                        de las citas con Contactos inciales
                        {{ (('users'==$tipo)?'a':'para este') }}
                        {{ (('users'==$tipo)?$contacto->user->name:$tipo) }}
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
