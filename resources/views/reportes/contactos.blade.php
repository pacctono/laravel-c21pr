@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mt-0 mb-1 mx-0 p-0">
        <h3 class="m-0 p-0">{{ $title }}</h3>
	{{-- <p>
            <a href="{{ route('contactos.create') }}" class="btn btn-primary">
                Crear Contacto Inicial
            </a>
        </p> --}}
    </div>

    @if ($contactos->isNotEmpty())
    <table class="table table-striped table-hover table-bordered m-0 p-0">
        <thead class="thead-dark">
        <tr class="m-0 p-0">
            <th class="m-0 p-0" scope="col">
                <a href="{{ route($rutRetorno, [$id, 'name']) }}" class="btn btn-link m-0 p-0">
                    Nombre
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a href="{{ route($rutRetorno, [$id, 'telefono']) }}" class="btn btn-link m-0 p-0">
                    Telefono
                </a>
            </th>
        @if (!$movil)
            <th class="m-0 p-0" scope="col">
                <a href="{{ route($rutRetorno, [$id, 'email']) }}" class="btn btn-link m-0 p-0">
                    Correo
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a href="{{ route($rutRetorno, [$id, 'created_at']) }}" class="btn btn-link m-0 p-0">
                    Contactado
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a href="{{ route($rutRetorno, [$id, 'tipo']) }}" class="btn btn-link m-0 p-0">
                    Tipo
                </a>
            </th>
            {{--<th scope="col">Acción</th>--}}
        @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($contactos as $vcliente)
        <tr class="
        @if (0 == ($loop->iteration % 2))
            table-primary
        @else
            table-info
        @endif
        m-0 p-0">
            <td class="m-0 p-0">
            @if ($movil)
                <a href="{{ route('contactos.muestra', [$vcliente, $rutRetorno]) }}" class="btn btn-link">
                    {{ $vcliente->name }}
                </a>
            @else ($movil)
                {{ $vcliente->name }}
            @endif ($movil)
            </td>
            <td class="text-right m-0 py-0 px-1">
                {{ $vcliente->telefono_f }}
            </td>
        @if (!$movil)
            <td class="m-0 py-0 px-1">{{ $vcliente->email }}</td>
            <td class="m-0 py-0 px-1">
                {{ $vcliente->creado_dia_semana }}
                {{ $vcliente->creado }}
                @if ('' != $vcliente->user_borro and $vcliente->user_borro != null)
                    [B]
                @endif
            </td>
            <td class="m-0 py-0 px-1">{{ $vcliente->tipo_alfa }}</td>
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
