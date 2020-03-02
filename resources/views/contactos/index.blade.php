@extends('layouts.app')

@section('content')
    {{--<div class="d-flex justify-content-between align-items-end mb-1">
        @if (!isset($accion) or ('html' == $accion))
        @if ($movil)
        <h4 class="m-0 p-0">{{ substr($title, 11) }}</h4>
        @else
        <h3 class="m-0 p-0">{{ $title }}</h3>
        @endif
        @else (!isset($accion) or ('html' == $accion))
        <h3 style="text-align:center">{{ $title }}</h3>
        @endif (!isset($accion) or ('html' == $accion))

        @if (!isset($accion) or ('html' == $accion))
        <!--p-->
            <a href="{{ route('contactos.create') }}" class="btn btn-primary m-0 p-0">
            @if ($movil)
                Crear
            @else
                Crear Contacto Inicial
            @endif
            </a>
        <!--/p-->
        @endif (!isset($accion) or ('html' == $accion))
    </div>--}}
@if (isset($accion) and ('html' != $accion))
    <div>
        <h4 style="text-align:center">
            {{ $title }}
        </h4>
    </div>
@elseif (isset($alertar))
@if (1 == $alertar)
    <script>alert("Fue enviado el correo con la 'Oferta de Servicio' al contacto inicial.");</script>
@elseif (2 == $alertar)
    <script>alert('El correo fue enviado al asesor');</script>
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo con la 'Oferta de Servcio' al contacto inicial. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif (0 < $alertar)
@endif (isset($accion) and ('html' != $accion))

@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))),
                'contactos.vmenu')

    @if ($contactos->isNotEmpty())
{{--@if ($paginar)
    {{ $contactos->links() }}
@endif ($paginar)--}}
    <table
    @if (!isset($accion) or ('html' == $accion))
        class="table table-striped table-hover table-bordered m-0 p-0"
    @else (!isset($accion) or ('html' == $accion))
        class="center"
    @endif (!isset($accion) or ('html' == $accion))
    >
        <thead class="thead-dark">
        <tr
        @if (isset($accion) and ('html' != $accion))
            class="encabezado"
        @else (isset($accion) and ('html' != $accion))
            class="m-0 p-0"
        @endif (isset($accion) and ('html' != $accion))
        >
            <!-- th scope="col">#</th -->
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'name') }}">
                    Nombre
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'deseo_id') }}">
                    Desea
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'telefono') }}">
                    Telefono
                </a>
            </th>
        @if (!$movil)
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'email') }}">
                    Correo
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'created_at') }}">
                    Contactado
                </a>
            </th>
            @if (1 == Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'user_id') }}">
                    Contactado por
                </a>
            </th>
            @endif (1 == Auth::user()->is_admin)
        @if (!isset($accion) or ('html' == $accion))
            <th class="m-0 p-0" scope="col">Acciones</th>
        @endif (!isset($accion) or ('html' == $accion))
            @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($contactos as $contacto)
        <tr class="
        @if (0 == ($loop->iteration % 2))
            table-primary
        @else
            table-info
        @endif
        m-0 p-0">
            {{--<td scope="row">{{ $contacto->id }}</td>--}}
            <td class="m-0 p-0">
                @if ($movil)
                <a href="{{ route('contactos.show', $contacto) }}"
                    class="btn btn-link m-0 p-0" style="text-decoration:none">
                    {{ substr($contacto->name, 0, 30) }}
                </a>
                @else
                {{ $contacto->name }}
                @endif
            </td>
            <td class="m-0 p-0">
                {{ $contacto->deseo->descripcion }}
            </td>
            <td class="m-0 p-0">
                {{ $contacto->telefono_f }}
                @if ($contacto->otro_telefono)
                    @if ($contacto->telefono_f)
                    <br>
                    @endif ($contacto->telefono_f)
                    {{ $contacto->otro_telefono }}
                @endif ($contacto->otro_telefono)
            </td>
            @if (!$movil)
            <td class="m-0 p-0">
                {{ $contacto->email }}
            </td>
            <td class="m-0 p-0">
                {{ $contacto->creado_dia_semana }}
                {{ $contacto->creado }}
                @if ('' != $contacto->user_borro and $contacto->user_borro != null)
                    [B]
                @endif
            </td>
            @if (1 == Auth::user()->is_admin)
            <td class="m-0 p-0">{{ $contacto->user->name }}</td>
            @endif
        @if (!isset($accion) or ('html' == $accion))
            <td class="d-flex align-items-end m-0 p-0">
                <a href="{{ route('contactos.show', $contacto) }}"
                        class="btn btn-link m-0 p-0"
                        title="Mostrar los datos de este contacto inicial ({{ $contacto->name }}).">
                    <span class="oi oi-eye m-0 p-0"></span>
                </a>
                <a href="{{ route('contactos.edit', $contacto) }}"
                        class="btn btn-link m-0 p-0"
                        title="Editar los datos de este contacto inicial ({{ $contacto->name }}).">
                    <span class="oi oi-pencil m-0 p-0"></span>
                </a>
                @if (1 == Auth::user()->is_admin)
                <form action="{{ route('contactos.destroy', $contacto) }}" method="POST" 
                        class="form-inline m-0 p-0"
                        onSubmit="return confirm('Realmente, desea borrar (borrado lógico) los datos de este contacto inicial de la base de datos?')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link m-0 p-0"
                            title="Borrar (lógico) contacto inicial.">
                        <span class="oi oi-trash m-0 p-0"
                                title="Borrar {{ $contacto->name }}">
                        </span>
                    </button>
                </form>
                    @if ((4 <= $contacto->resultado_id) and (7 >= $contacto->resultado_id) and
                         (!is_null($contacto->fecha_evento)) and ($contacto->fecha_evento > now()))
                    <a href="{{ route('agenda.correoCita', $contacto) }}"
                            class="btn btn-link m-0 p-0"
                            title="Enviar correo a '{{ $contacto->user->name }}', sobre cita con este contacto inicial">
                        <span class="oi oi-envelope-closed my-o p-0"></span>
                    </a>
                    @endif
                @endif
            </td>
        @endif (!isset($accion) or ('html' == $accion))
        @endif (!$movil)
        </tr>
        @endforeach
        </tbody>
    </table>
@if ($paginar)
    {{ $contactos->links() }}
@endif ($paginar)

@include('include.botonesPdf', ['enlace' => 'contactos'])

@else ($contactos->isNotEmpty())
    @includeif('include.noRegistros', ['elemento' => 'contactos iniciales'])
@endif ($contactos->isNotEmpty())

@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))),
                'contactos.vmenuCierre')

@endsection
