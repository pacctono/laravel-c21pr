@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-1">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('contactos.create') }}" class="btn btn-primary">Crear Contacto Inicial</a>
        </p>
    </div>
    @if ($alertar)
        <script>alert('El correo fue enviado al asesor');</script>
    @endif
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
            @if (1 == Auth::user()->is_admin)
            <td>{{ $contacto->user->name }}</td>
            @endif
            <td class="d-flex align-items-end">
                <a href="{{ route('contactos.show', $contacto) }}" class="btn btn-link" 
                        title="Mostrar los datos de este contacto inicial.">
                    <span class="oi oi-eye"></span>
                </a>
                <a href="{{ route('contactos.edit', $contacto) }}" class="btn btn-link"
                        title="Editar los datos de este contacto inicial.">
                    <span class="oi oi-pencil"></span>
                </a>

                @if (1 == Auth::user()->is_admin)
                <form action="{{ route('contactos.destroy', $contacto) }}" method="POST" 
                        class="form-inline mt-0 mt-md-0"
                        onSubmit="return confirm('Realmente, desea borrar (borrado lógico) los datos de este contacto inicial de la base de datos?')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link" title="Borrar (lógico) contacto inicial.">
                        <span class="oi oi-trash" title="Borrar">
                        </span>
                    </button>
                </form>
                    @if ((4 <= $contacto->resultado_id) and (7 >= $contacto->resultado_id))
                    <a href="{{ route('agenda.emailcita', $contacto) }}" class="btn btn-link"
                            title="Enviar correo a '{{ $contacto->user->name }}', sobre cita con este contacto inicial">
                        <span class="oi oi-envelope-closed"></span>
                    </a>
                    @endif
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $contactos->links() }}
    @else
        <p>No hay contactos iniciales registrados.</p>
    @endif

@endsection
