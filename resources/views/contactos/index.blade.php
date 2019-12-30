@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-1">
        @if (!isset($accion) or ('html' == $accion))
        @if ($movil)
        <h4 class="pb-1">{{ substr($title, 11) }}</h4>
        @else
        <h1 class="pb-1">{{ $title }}</h1>
        @endif
        @else (!isset($accion) or ('html' == $accion))
        <h1 style="text-align:center">{{ $title }}</h1>
        @endif (!isset($accion) or ('html' == $accion))

        @if (!isset($accion) or ('html' == $accion))
        <p>
            <a href="{{ route('contactos.create') }}" class="btn btn-primary">
            @if ($movil)
                Crear
            @else
                Crear Contacto Inicial
            @endif
            </a>
        </p>
        @endif (!isset($accion) or ('html' == $accion))
    </div>
@if (isset($alertar))
@if (1 == $alertar)
    <script>alert("Fue enviado el correo con la 'Oferta de Servicio' al contacto inicial.");</script>
@elseif (2 == $alertar)
    <script>alert('El correo fue enviado al asesor');</script>
@elseif (0 > $alertar)
    <script>alert("No fue enviado el correo con la 'Oferta de Servcio' al contacto inicial. Probablemente, problemas con Internet! Revise su conexión");</script>
@endif (0 < $alertar)
@endif (isset($alertar))
    @if ($contactos->isNotEmpty())
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
        @endif (isset($accion) and ('html' != $accion))
        >
            <!-- th scope="col">#</th -->
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'name') }}">
                    Nombre
                </a>
            </th>
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'telefono') }}">
                    Telefono
                </a>
            </th>
        @if (!$movil)
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'email') }}">
                    Correo
                </a>
            </th>
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'created_at') }}">
                    Contactado
                </a>
            </th>
            @if (1 == Auth::user()->is_admin)
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                   @else "enlaceDesabilitado" name=
                   @endif "{{ route('contactos.orden', 'user_id') }}">
                    Contactado por
                </a>
            </th>
            @endif (1 == Auth::user()->is_admin)
        @if (!isset($accion) or ('html' == $accion))
            <th scope="col">Acciones</th>
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
        ">
            {{--<td scope="row">{{ $contacto->id }}</td>--}}
            <td>
                @if ($movil)
                <a href="{{ route('contactos.show', $contacto) }}" class="btn btn-link" style="text-decoration:none">
                    {{ substr($contacto->name, 0, 30) }}
                </a>
                @else
                {{ $contacto->name }}
                @endif
            </td>
            <td>
                {{ $contacto->telefono_f }}
                @if ($contacto->otro_telefono)
                    <br>{{ $contacto->otro_telefono }}
                @endif ($contacto->otro_telefono)
            </td>
            @if (!$movil)
            <td>
                {{ $contacto->email }}
            </td>
            <td>
                {{ $contacto->creado_dia_semana }}
                {{ $contacto->creado }}
                @if ('' != $contacto->user_borro and $contacto->user_borro != null)
                    [B]
                @endif
            </td>
            @if (1 == Auth::user()->is_admin)
            <td>{{ $contacto->user->name }}</td>
            @endif
        @if (!isset($accion) or ('html' == $accion))
            <td class="d-flex align-items-end">
                <a href="{{ route('contactos.show', $contacto) }}" class="btn btn-link" 
                        title="Mostrar los datos de este contacto inicial ({{ $contacto->name }}).">
                    <span class="oi oi-eye"></span>
                </a>
                <a href="{{ route('contactos.edit', $contacto) }}" class="btn btn-link"
                        title="Editar los datos de este contacto inicial ({{ $contacto->name }}).">
                    <span class="oi oi-pencil"></span>
                </a>

                @if (1 == Auth::user()->is_admin)
                <form action="{{ route('contactos.destroy', $contacto) }}" method="POST" 
                        class="form-inline mt-0 mt-md-0"
                        onSubmit="return confirm('Realmente, desea borrar (borrado lógico) los datos de este contacto inicial de la base de datos?')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button class="btn btn-link" title="Borrar (lógico) contacto inicial.">
                        <span class="oi oi-trash" title="Borrar {{ $contacto->name }}">
                        </span>
                    </button>
                </form>
                    @if ((4 <= $contacto->resultado_id) and (7 >= $contacto->resultado_id) and
                         (!is_null($contacto->fecha_evento)) and ($contacto->fecha_evento > now()))
                    <a href="{{ route('agenda.correoCita', $contacto) }}" class="btn btn-link"
                            title="Enviar correo a '{{ $contacto->user->name }}', sobre cita con este contacto inicial">
                        <span class="oi oi-envelope-closed"></span>
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
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $contactos->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

@include('include.botonesPdf', ['enlace' => 'contactos'])

@else ($contactos->isNotEmpty())
    <p>No hay contactos iniciales registrados.</p>
@endif ($contactos->isNotEmpty())

@endsection
