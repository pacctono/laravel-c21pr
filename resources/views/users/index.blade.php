@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-1">
        @if (!isset($accion) or ('html' == $accion))
        @if ($movil)
        <h4 class="pb-1">{{ substr($title, 11) }}</h4>
        @else
        <h1 class="pb-1">{{ $title }}</h1>
        @endif
        @else ('html' == $accion)
        <h1 style="text-align:center">{{ $title }}</h1>
        @endif ('html' == $accion)

        @if (!isset($accion) or ('html' == $accion))
        <p>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                @if ($movil)
                Crear
                @else
                Crear asesor
                @endif
            </a>
        </p>
        @endif (!isset($accion) or ('html' == $accion))
    </div>

    @if ($alertar)
        <script>alert('El correo fue enviado al asesor');</script>
    @endif
@if ($users->isNotEmpty())
    <table
    @if (!isset($accion) or ('html' == $accion))
        class="table table-striped table-hover table-bordered"
    @else (!isset($accion) or ('html' == $accion))
        class="center"
    @endif (!isset($accion) or ('html' == $accion))
    >
        <thead class="thead-dark">
        <tr
        @if (isset($accion) and ('html' != $accion))
            class="encabezado"
        @endif ('html' != $accion)
        >
        @if (!$movil)
            <th scope="col">id</th>
        @endif
            <th scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('users.orden', 'name') }}" class="btn btn-link">
                    Nombre
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Nombre
            @endif (!isset($accion) or ('html' == $accion))
            </th>
            <th scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('users.orden', 'telefono') }}" class="btn btn-link">
                    Telefono
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Telefono
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @if (!$movil)
            <th scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('users.orden', 'email') }}" class="btn btn-link">
                    Correo
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Correo
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @if (Auth::user()->is_admin)
            <th scope="col">Lados</th>
            <th scope="col">Comision</th>
            <th scope="col">Puntos</th>
        @endif (Auth::user()->is_admin)
        @if (!isset($accion) or ('html' == $accion))
            <th scope="col">Acciones</th>
        @endif ('html' == $accion)
        @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
        <tr
        @if (!$user->activo)
            class="table-danger" title="Asesor no est&aacute; activo"
        @elseif (0 == ($loop->iteration % 2))
            class="table-primary"
        @else
            class="table-info"
        @endif
        >
        @if (!$movil)
            <td scope="row">{{ $user->id }}</td>
        @endif
            <td>
        @if (!isset($accion) or ('html' == $accion))
            @if ($movil)
                <a href="{{ route('users.show', $user) }}" class="btn btn-link" style="text-decoration:none;">
                    {{ $user->nombre }} {{-- nombre devuelve 'Administrador', cuando id = 1. --}}
                </a>
            @else
            @if (1 < $user->id)
                <a href="{{ route('reporte.contactosUser', [$user->id, 'id']) }}" class="btn btn-link"
                    title="Mostrar reporte de contactos del asesor.">
            @endif
                    {{ $user->nombre }}
            @if (1 < $user->id)
                </a>
            @endif
            @endif
        @else ('html' == $accion)
                {{ $user->nombre }}
        @endif ('html' == $accion)
            </td>
            <td>{{ $user->telefono_f }}
            </td>
        @if (!$movil)
            <td>
                {{ $user->email }}
            </td>
        @if (Auth::user()->is_admin)
            <td
        @if (!isset($accion) or ('html' == $accion))
            @if (1 == $user->id)
                title="Estos lados representan los 'lados' producidos por 'Otra oficina'">
                <span class="float-right">0</span>
            @else
            >
                <span class="float-right">{{ Prop::numeroVen($user->lados, 0) }}{{-- 'Prop' es un alias definido en config/app.php --}}
                </span>
            @endif (1 == $user->id)
        @else ('html' == $accion)
            style="text-align:right;">
            @if (1 == $user->id)
                0
            @else
                {{ Prop::numeroVen($user->lados, 0) }}{{-- 'Prop' es un alias definido en config/app.php --}}
            @endif (1 == $user->id)
        @endif ('html' == $accion)
            </td>
            <td
        @if (!isset($accion) or ('html' == $accion))
            @if (1 == $user->id)
                title="Este monto representa la 'comision' producida para 'Otra oficina'">
                <span class="float-right">0,00</span>
            @else
            >
                <span class="float-right">{{ Prop::numeroVen($user->comision, 2) }}{{-- 'Prop' es un alias definido en config/app.php --}}
                </span>
            @endif (1 == $user->id)
        @else ('html' == $accion)
            style="text-align:right;">
            @if (1 == $user->id)
                0,00
            @else
                {{ Prop::numeroVen($user->comision, 2) }}{{-- 'Prop' es un alias definido en config/app.php --}}
            @endif (1 == $user->id)
        @endif ('html' == $accion)
            </td>
            <td
        @if (!isset($accion) or ('html' == $accion))
            @if (1 == $user->id)
                title="Estos puntos representan los producidos por 'Otra oficina'">
                <span class="float-right">0,00</span>
            @else
            >
                <span class="float-right">{{ Prop::numeroVen($user->puntos, 2) }}{{-- 'Prop' es un alias definido en config/app.php --}}
                </span>
            @endif (1 == $user->id)
        @else ('html' == $accion)
            style="text-align:right;">
            @if (1 == $user->id)
                0,00
            @else
                {{ Prop::numeroVen($user->puntos, 2) }}{{-- 'Prop' es un alias definido en config/app.php --}}
            @endif (1 == $user->id)
        @endif ('html' == $accion)
            </td>
        @endif (Auth::user()->is_admin)
        @if (!isset($accion) or ('html' == $accion))
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
                @if (Auth::user()->is_admin)
                    @if (1 < $user->id)
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-link"
                            title="Editar los datos personales de {{ $user->name }}">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <a href="{{ route('users.updateActivo', $user) }}" id="{{ $user->id }}"
                            class="btn btn-link desAct"
{{-- onClick="seguroDesActivar('desActivar_{{ $user->id}}', '{{ $user->name }}', {{ ($user->activo)?'true':'false' }})"--}}
                    @if ($user->activo)
                            title="Desactivar al asesor {{ $user->name }}">
                        <span class="oi oi-thumb-down"></span>
                    @else ($user->activo)
                            title="Activar al asesor {{ $user->name }}">
                        <span class="oi oi-thumb-up"></span>
                    @endif ($user->activo)
                    </a>
                    <input type="hidden" id="nombre{{ $user->id }}" value="{{ $user->name }}">
                    <input type="hidden" id="activo{{ $user->id }}" value="{{ ($user->activo)?'A':'D' }}">
                    <button class="btn btn-link" title="Borrar este asesor. Mucho cuidado!!!">
                        <span class="oi oi-trash">
                        </span>
                    </button>
                    @if (0 < $user->citas()->count())
                    <a href="{{ route('agenda.correoCitas', $user) }}" class="btn btn-link"
                            title="Enviar correo a '{{ $user->name }}' con sus citas.">
                        <span class="oi oi-envelope-closed"></span>
                    </a>
                    @endif (0 < $user->citas()->count())
                    @endif (1 < $user->id)
                @endif (Auth::user()->is_admin)
                </form>
            </td>
        @endif ('html' == $accion)
        @endif (!$movil)
        </tr>
        @endForeach
        </tbody>
    </table>
{{-- De acuerdo a la reunion del lunes 25/11/2019, se elimino la paginacion de asesores.
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $users->links() }}
@endif
--}}

@else ($users->isNotEmpty())
    <p>No hay asesores registrados.</p>
@endif ($users->isNotEmpty())

@include('include.botonesPdf', ['enlace' => 'users'])

@endsection

@if (!isset($accion) or ('html' == $accion))
@section('js')
<script>
    $(document).ready(function() {
        $('a.desAct').click(function(ev) {
            var id = $(this).attr('id');        // Tambien $(ev.target).attr('id')
            var nombre = document.getElementById('nombre'+id).value;
            var activo = document.getElementById('activo'+id).value;
            var accion;
            if ('A' == activo)
                accion = confirm('Desea desactivar al asesor ' + nombre);
            else
                accion = confirm('Desea activar al asesor ' + nombre);
            if (!accion) {
                ev.preventDefault();
            }
        })
    })
function seguroDesActivar(sId, nombre, activo) {
    var id = document.getElementById(sId);
    var accion;

    if (activo)
        accion = confirm('Desea desactivar al asesor ' + nombre);
    else
        accion = confirm('Desea activar al asesor ' + nombre);
    if (!accion) {
        alert('Falso'+sId+id);
        id.addEventListener("click", function(event){
            event.preventDefault()
        });
    } else alert('Verdadero');
}
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
@endif (!isset($accion) or ('html' == $accion))
