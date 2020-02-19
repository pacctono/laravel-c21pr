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
                <a href="{{ route($rutRetorno, [$id, 'tipo']) }}" class="btn btn-link">
                    Tipo
                </a>
            </th>
            {{--<th scope="col">Acción</th>--}}
        @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($contactos as $vcliente)
        <tr>
            <td>
            @if ($movil)
                <a href="{{ route('contactos.muestra', [$vcliente, $rutRetorno]) }}" class="btn btn-link">
                    {{ $vcliente->name }}
                </a>
            @else ($movil)
                {{ $vcliente->name }}
            @endif ($movil)
            </td>
            <td>
                {{ $vcliente->telefono_f }}
            </td>
        @if (!$movil)
            <td>{{ $vcliente->email }}</td>
            <td>
                {{ $vcliente->creado_dia_semana }}
                {{ $vcliente->creado }}
                @if ('' != $vcliente->user_borro and $vcliente->user_borro != null)
                    [B]
                @endif
            </td>
            <td>{{ $vcliente->tipo_alfa }}</td>
            {{--<td class="d-flex align-items-end">
                <a href="{{ route('contactos.muestra', [$contacto, $rutRetorno]) }}" class="btn btn-link">
                    <span class="oi oi-eye"></span>
                </a>
                @if ((4 <= $contacto->resultado_id) and (7 >= $contacto->resultado_id))
                    <a href="{{ route('agenda.correoCita', $contacto) }}" class="btn btn-link"
                            title="Enviar correo a '{{ $contacto->user->name }}' con esta cita.">
                        <span class="oi oi-envelope-closed"></span>
                    </a>
                @endif
            </td>--}}
        @endif (!$movil)
        </tr>
        @endforeach
        </tbody>
        {{--<tfoot>
            <tr>
                <td colspan="2">
                    <a href="{{ route($tipo) }}" class="btn btn-link">
                        Volver
                    </a>
                </td>
                <td colspan="4">
                    <a href="{{ route('agenda.correoCitas', $contacto->user) }}"
                            class="btn btn-link">
                        Enviar correo de las citas con Contactos inciales
                        a {{ $contacto->user->name }}
                    </a>
                </td>
            </tr>
        </tfoot>--}}
    </table>
    <a href="{{ route($tipo) }}" class="btn btn-link">
        Volver
    </a>
    {{ $contactos->links() }}
    @else
        <p>No hay contactos iniciales registrados.</p>
    @endif

@endsection
