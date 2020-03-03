@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mt-0 mb-1 pt-0 mb-1">
        @if (!isset($accion) or ('html' == $accion))
        @if ($movil)
        <h4 class="my-0 py-0">{{ substr($title, 11) }}</h4>
        @else
        <h3 class="my-0 py-0">{{ $title }}</h3>
        @endif
        @else ('html' == $accion)
        <h3 style="text-align:center">{{ $title }}</h3>
        @endif ('html' == $accion)

        @if (!isset($accion) or ('html' == $accion))
        <!--p-->
            <a href="{{ route('users.create') }}" class="btn btn-primary my-0 py-0">
                @if ($movil)
                Crear
                @else
                Crear asesor
                @endif
            </a>
        <!--/p-->
        @endif (!isset($accion) or ('html' == $accion))
    </div>

@if (isset($alertar))
@if (0 < $alertar)
  <script>alert('El correo con la(s) cita(s) fue enviado al asesor');</script>
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo al asesor. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif (0 < $alertar)
@endif (isset($alertar))

@if ($users->isNotEmpty())
    <table
    @if (!isset($accion) or ('html' == $accion))
        class="table table-striped table-hover table-bordered my-0 py-0"
    @else (!isset($accion) or ('html' == $accion))
        class="center"
    @endif (!isset($accion) or ('html' == $accion))
    >
        <thead class="thead-dark">
        <tr
        @if (isset($accion) and ('html' != $accion))
            class="encabezado"
        @else (isset($accion) and ('html' != $accion))
            class="my-0 py-0"
        @endif (isset($accion) and ('html' != $accion))
        >
        @if (!$movil)
            <th class="my-0 py-0" scope="col">id</th>
        @endif
            <th class="my-0 py-0" scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('users.orden', 'name') }}" class="btn btn-link my-0 py-0">
                    Nombre
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Nombre
            @endif (!isset($accion) or ('html' == $accion))
            </th>
            <th class="my-0 py-0" scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('users.orden', 'telefono') }}" class="btn btn-link my-0 py-0">
                    Tel&eacute;fono
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Tel&eacute;fono
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @if (!$movil)
            <th class="my-0 py-0" scope="col">
            @if (!isset($accion) or ('html' == $accion))
                <a href="{{ route('users.orden', 'email') }}" class="btn btn-link my-0 py-0">
                    Correo
                </a>
            @else (!isset($accion) or ('html' == $accion))
                Correo
            @endif (!isset($accion) or ('html' == $accion))
            </th>
        @if (Auth::user()->is_admin)
            <th class="my-0 py-0" scope="col">Lados</th>
            <th class="my-0 py-0" scope="col">Comision</th>
            <th class="my-0 py-0" scope="col">Puntos</th>
        @endif (Auth::user()->is_admin)
        @if (!isset($accion) or ('html' == $accion))
            <th class="my-0 py-0" scope="col" style="width:20%;">Acciones</th>
        @endif ('html' == $accion)
        @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
        <tr
        @if (!$user->activo)
            title="Asesor no est&aacute; activo" class="table-danger
        @elseif (0 == ($loop->iteration % 2))
            class="table-primary
        @else
            class="table-info
        @endif
                m-0 p-0">
        @if (!$movil)
            <td class="text-right m-0 py-0 px-1" scope="row">{{ $user->id }}</td>
        @endif
            <td class="text-left m-0 py-0 px-1">
        @if (!isset($accion) or ('html' == $accion))
            @if ($movil)
                <a href="{{ route('users.show', $user) }}" class="btn btn-link m-0 p-0"
                    style="text-decoration:none;">
                    {{ $user->nombre }} {{-- nombre devuelve 'Administrador', cuando id = 1. --}}
                </a>
            @elseif ((1 < $user->id) and ((0 < $user->propiedades->count()) or
                                      (0 < $user->propiedadesCaptadas->count()) or
                                      (0 < $user->propiedadesCerradas->count())))
                <a href="{{ route('reporte.propiedadesUser', [$user->id, 'id']) }}"
                    class="btn btn-link m-0 p-0"
                    title="Mostrar reporte de propiedades del asesor.">
                    {{ $user->nombre }}
                </a>
            @else
                {{ $user->nombre }}
            @endif
        @else ('html' == $accion)
                {{ $user->nombre }}
        @endif ('html' == $accion)
            </td>
            <td class="text-right m-0 py-0 px-1">{{ $user->telefono_f }}
            </td>
        @if (!$movil)
            <td class="m-0 p-0">
                {{ $user->email }}
            </td>
        @if (Auth::user()->is_admin)
            <td
        @if (!isset($accion) or ('html' == $accion))
                class="text-right m-0 py-0 px-1"
            @if (1 == $user->id)
                title="Estos lados representan los 'lados' producidos por 'Otra oficina'"><!-- cierra td -->
                0
            @else
            >
                {{ Prop::numeroVen($user->lados, 0) }}{{-- 'Prop' es un alias definido en config/app.php --}}
            @endif (1 == $user->id)
        @else ('html' == $accion)<!-- PDF -->
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
                class="m-0 py-0 px-1"
            @if (1 == $user->id)
                title="Este monto representa la 'comision' producida para 'Otra oficina'"><!-- cierra td -->
                <span class="float-right m-0 p-0">0,00</span>
            @else
            >
                <span class="float-right m-0 p-0">
                    {{ Prop::numeroVen($user->comision, 2) }}{{-- 'Prop' es un alias definido en config/app.php --}}
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
                class="m-0 py-0 px-1"
            @if (1 == $user->id)
                title="Estos puntos representan los producidos por 'Otra oficina'"><!-- cierra td -->
                <span class="float-right m-0 p-0">0,00</span>
            @else
            >
                <span class="float-right m-0 p-0">
                    {{ Prop::numeroVen($user->puntos, 2) }}{{-- 'Prop' es un alias definido en config/app.php --}}
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
            <td class="d-flex align-items-end m-0 py-0 px-1">
                <a href="{{ route('users.show', $user) }}" class="btn btn-link my-0 py-0 mx-0 px-0"
                        title="Motrar los datos personales de {{ $user->name }}">
                    <span class="oi oi-eye my-0 py-0 ml-0 mr-1 pl-0 pr-0"></span>
                </a>
                @if (Auth::user()->is_admin)
                    @if (1 < $user->id)
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-link my-0 py-0 mx-0 px-0"
                            title="Editar los datos personales de {{ $user->name }}">
                        <span class="oi oi-pencil my-0 py-0 mx-1 px-0"></span>
                    </a>
                    <a href="{{ route('users.updateActivo', $user) }}" id="{{ $user->id }}"
                            class="btn btn-link desAct my-0 py-0 mx-0 px-0"
                    @if ($user->activo)
                            title="Desactivar al asesor {{ $user->name }}">
                        <span class="oi oi-thumb-down my-0 py-0 mx-1 px-0"></span>
                    @else ($user->activo)
                            title="Activar al asesor {{ $user->name }}">
                        <span class="oi oi-thumb-up my-0 py-0 mx-1 px-0"></span>
                    @endif ($user->activo)
                    </a>
                <form action="{{ route('users.destroy', $user) }}" method="POST"
                        class="form-inline my-0 py-0"
                        onSubmit="return estaSeguro({{ $user->id }})">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" name="contactos" id="contactos.{{ $user->id }}"
                            value="{{ $user->contactos->count()-$user->contactosBorrados->count() }}">
                    <input type="hidden" name="contactosBorrados"
                            id="contactosBorrados.{{ $user->id }}"
                            value="{{ $user->contactosBorrados->count() }}">
                    <input type="hidden" id="nombre{{ $user->id }}"
                            value="{{ $user->name }}">
                    <input type="hidden" id="activo{{ $user->id }}"
                            value="{{ ($user->activo)?'A':'D' }}">
                    <button class="btn btn-link my-0 py-0 mx-0 px-0"
                            title="Borrar este asesor. Mucho cuidado!!!">
                        <span class="oi oi-trash my-0 py-0 mx-1 px-0"></span>
                    </button>
                </form>
                    @if (0 < $user->citas()->count())
                    <a href="{{ route('agenda.correoCitas', $user) }}" class="btn btn-link my-0 py-0 mx-0 px-0"
                            title="Enviar correo a '{{ $user->name }}' con sus citas.">
                        <span class="oi oi-envelope-closed my-0 py-0 mx-1 px-0"></span>
                    </a>
                    @endif (0 < $user->citas()->count())
                    @if ((0 < $user->contactos->count()) or (0 < $user->clientes->count()))
                    <a href="{{ route('reporte.contactosUser', [$user->id, 'id']) }}"
                        class="btn btn-link my-0 py-0 mx-0 px-0"
                        title="Mostrar reporte de contactos y clientes del asesor.">
                        <span class="oi oi-people my-0 py-0 mx-1 px-0"></span>
                    </a>
                    @endif ((0 < $user->contactos->count()) or (0 < $user->clientes->count()))
                    @if (0 < $user->avisos()->count())
                    <a href="" class="btn btn-link my-0 py-0 mx-0 px-0 aviso"
                            id="aviso{{ $user->id }}"
                            title="'{{ $user->name }}' tiene {{ $user->avisos()->count() }} aviso(s).">
                        <span class="oi oi-bell my-0 py-0 ml-1 mr-0 px-0"></span>
                    </a>
                    <!--div id="div{{ $user->id }}"></div-->
                    {{-- <input type="hidden" id="numAvisos{{ $user->id }}"
                            value="{{ $user->avisos()->count() }}">
                    @foreach($user->avisos as $aviso)
                        <input type="hidden" id="tipo{{ $user->id }}_{{ $loop->index }}"
                                value="{{ $aviso->tipo }}">
                        <input type="hidden" id="fecha{{ $user->id }}_{{ $loop->index }}"
                                value="{{ $aviso->fec }}">
                        <input type="hidden" id="descripcion{{ $user->id }}_{{ $loop->index }}"
                                value="{{ $aviso->descripcion }}">
                    @endForeach --}}
                    @endif (0 < $user->citas()->count())
                    @endif (1 < $user->id)
                @endif (Auth::user()->is_admin)
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
        });
        $("a.aviso").click(function(ev) {
            ev.preventDefault();
            var user_id = $(this).attr('id').substring(5);        // 'avisoid'
            // Funciona y devuelve una tabla. Ahora, hay que buscarla donde desplegarla.
            // el id: 'div'+user_id fue comenatdo arriba, intencionalmente.
            $.ajax({url: "/avisos/asesor/"+user_id, success: function(resultado) {
                        //$("#div"+user_id).html(resultado);
                        alert(resultado);   // Mientras se resuelve donde desplegar la tabla.
                    }});
        });
    });
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
