@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-1">
        @if ($movil)
        <h4 class="pb-1">{{ substr($title, 11) }}</h4>
        @else
        <h1 class="pb-1">{{ $title }}</h1>
        @endif

        <p>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                @if ($movil)
                Crear
                @else
                Crear asesor
                @endif
            </a>
        </p>
    </div>

    @if ($alertar)
        <script>alert('El correo fue enviado al asesor');</script>
    @endif
@if ($users->isNotEmpty())
    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
        @if (!$movil)
            <th scope="col">#</th>
        @endif
            <th scope="col">Nombre</th>
        @if ($movil)
            <th scope="col">Contacto</th>
        @else
            <th scope="col">Teléfono</th>
            <th scope="col">Correo</th>
        @if (Auth::user()->is_admin)
            <th scope="col">Comision</th>
        @endif
            <th scope="col">Acciones</th>
        @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
        <tr
        @if (!$user->activo)
            class="table-danger" title="Asesor no est&aacute; activo"
        @endif
        >
        @if (!$movil)
            <td scope="row">{{ $user->id }}</td>
        @endif
            <td>
            @if (1 < $user->id)
                <a href="{{ route('reporte.contactosUser', [$user->id, 'id']) }}" class="btn btn-link"
                    title="Mostrar reporte de contactos del asesor.">
            @endif
                    {{ $user->name }}
            @if (1 < $user->id)
                </a>
            @endif
            <td>{{ $user->telefono_f }}
        @if ($movil)
            <br>
        @else
            </td>
            <td>
        @endif
                {{ $user->email }}
            </td>
        @if (!$movil)
        @if (Auth::user()->is_admin)
            <td class="float-right"
            @if (1 == $user->id)
                title="Este monto representa la 'comision' producida para 'Otra oficina'"
            @endif
            >
                {{ Prop::numeroVen($user->comision, 2) }}{{-- 'Prop' es un alias definido en config/app.php --}}
            </td>
        @endif
            <td>
                <form action="{{ route('users.destroy', $user) }}" method="POST"
                        id="forma.{{ $user->id }}" name="forma.{{ $user->id }}"
                        onSubmit="return estaSeguro({{ $user->id }})">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <input type="hidden" name="contactos" id="contactos.{{ $user->id }}"
                            value="{{ $user->contactos->count()-$user->contactosBorrados->count() }}">
                    <input type="hidden" name="contactosBorrados"
                            id="contactosBorrados.{{ $user->id }}"
                            value="{{ $user->contactosBorrados->count() }}">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-link"
                            title="Motrar los datos personales de {{ $user->name }}">
                        <span class="oi oi-eye"></span>
                    </a>
                    @if (1 < $user->id)
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-link"
                            title="Editar los datos personales de {{ $user->name }}">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <button class="btn btn-link" title="Borrar este asesor. Mucho cuidado!!!">
                        <span class="oi oi-trash">
                        </span>
                    </button>
                    @if (0 < $user->contactos->count())
                    <a href="{{ route('agenda.emailcitas', $user) }}" class="btn btn-link"
                            title="Enviar correo a '{{ $user->name }}' con sus citas.">
                        <span class="oi oi-envelope-closed"></span>
                    </a>
                    @endif
                    @endif
                </form>
            </td>
        @endif
        </tr>
        @endForeach
        </tbody>
    </table>
@if (!$movil)
    {{ $users->links() }}
@endif
@else
    <p>No hay asesores registrados.</p>
@endif

@endsection

@section('js')
<script>
function estaSeguro(id) {
    var nroContactos         = document.getElementById('contactos.'+id).value;
    var nroContactosBorrados = document.getElementById('contactosBorrados.'+id).value;

    if (0 < nroContactos) {
        alert('Este asesor ha creado ' + nroContactos +
                            ' contactos iniciales, por lo tanto, no puede borrar sus datos.');
        return false;
    }
    if (0 < nroContactosBorrados) {
        return confirm('Este asesor tiene ' + nroContactosBorrados +
                            " 'Contactos Iniciales borrados', " +
                            'esta seguro de querer borrar sus datos de la base de datos?');
    }
    return confirm('Realmente, desea borrar los datos de este asesor de la base de datos?')
//  submit();
}
</script>

@endsection
