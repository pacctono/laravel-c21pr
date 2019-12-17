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
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            @if ($movil)
                Crear
            @else
                Crear Cliente
            @endif
            </a>
        </p>
        @endif (!isset($accion) or ('html' == $accion))
    </div>

    @if ($clientes->isNotEmpty())
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
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'cedula') }}">
                    Cedula
                </a>
            </th>
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'rif') }}">
                    Rif
                </a>
            </th>
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'name') }}">
                    Nombre
                </a>
            </th>
        @if (!$movil)
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'tipo') }}">
                    Tipo
                </a>
            </th>
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'telefono') }}">
                    Telefono
                </a>
            </th>
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'email') }}">
                    Correo
                </a>
            </th>
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'fecha_nacimiento') }}">
                    Fec.Nac.
                </a>
            </th>
            @if (Auth::user()->is_admin)
            <th scope="col">
                <a class=@if('html'==$accion) "btn btn-link" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'user_id') }}">
                    Creado por
                </a>
            </th>
            @endif
        @if (!isset($accion) or ('html' == $accion))
            <th scope="col">Acciones</th>
        @endif (!isset($accion) or ('html' == $accion))
        @endif (!$movil)
        </tr>
        </thead>
        <tbody>
        @foreach ($clientes as $cliente)
        <tr class="
        @if (0 == ($loop->iteration % 2))
            table-primary
        @else
            table-info
        @endif
        ">
            <td>
            @if ($movil)
                <a href="{{ route('clientes.show', $cliente) }}"
                   class="btn btn-link" style="text-decoration:none">
                    {{ $cliente->cedula_f }}
                </a>
            @else ($movil)
                {{ $cliente->cedula_f }}
            @endif ($movil)
            </td>
            <td>{{ $cliente->rif_f }}</td>
            <td>{{ $cliente->name }}</td>
        @if (!$movil)
            <td>
                {{ substr($cliente->tipo_alfa, 0, 7) }}
            </td>
            <td>
                {{ $cliente->telefono_f }}
                @if ($cliente->otro_telefono)
                    <br>{{ $cliente->otro_telefono }}
                @endif ($contacto->otro_telefono)
            </td>
            <td>{{ $cliente->email }}</td>
            <td>
                {{ $cliente->fec_nac }}
            </td>
            @if (Auth::user()->is_admin)
            <td>{{ $cliente->user->name }}</td>
            @endif
        @if (!isset($accion) or ('html' == $accion))
            <td class="d-flex align-items-end">
                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-link"
                        title="Mostrar los datos de este cliente ({{ $cliente->name }}).">
                    <span class="oi oi-eye"></span>
                </a>
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-link"
                    title="Editar los datos del cliente ({{ $cliente->name }})."
                    onclick="return seguroEditar({{ $cliente->id }}, '{{ $cliente->name }}')">
                    <span class="oi oi-pencil"></span>
                </a>

                @if (Auth::user()->is_admin)
                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST"
                        class="form-inline mt-0 mt-md-0" id="forma.{{ $cliente->id }}"
                        name="forma.{{ $cliente->id }}"
                        onSubmit="return seguroBorrar({{ $cliente->id }}, '{{ $cliente->name }}')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <input type="hidden" name="propiedades" id="propiedades.{{ $cliente->id }}"
                            value="{{ $cliente->propiedades->count()-$cliente->propiedadesBorradas($cliente->id)->count() }}">
                    <input type="hidden" name="propiedadesBorradas"
                            id="propiedadesBorradas.{{ $cliente->id }}"
                            value="{{ $cliente->propiedadesBorradas($cliente->id)->count() }}">

                    <button class="btn btn-link" title="Borrar (lógico) cliente.">
                        <span class="oi oi-trash" title="Borrar {{ $cliente->name }}"></span>
                    </button>
                </form>
                @endif
            </td>
        @endif (!isset($accion) or ('html' == $accion))
        @endif (!$movil)
        </tr>
        @endForeach
        </tbody>
    </table>
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $clientes->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

@include('include.botonesPdf', ['enlace' => 'clientes'])

@else ($clientes->isNotEmpty())
    <p>No hay clientes registrados.</p>
@endif ($clientes->isNotEmpty())

@endsection

@section('js')
<script>
function seguroBorrar(id, nombre) {
    var nroPropiedades         = document.getElementById('propiedades.'+id).value;
    var nroPropiedadesBorradas = document.getElementById('propiedadesBorradas.'+id).value;

    if (0 < nroPropiedades) {
        alert("Este cliente: '" + nombre + "', ha sido asignado a <" + nroPropiedades +
                            '> propiedades, por lo tanto, no puede borrar sus datos.');
        return false;
    }
    if (0 < nroPropiedadesBorradas) {
        return confirm("Este cliente: '" + nombre + "',  está en " + nroPropiedadesBorradas +
                            " 'Propiedades borradas', esta seguro de querer borrar sus datos " + 
                            '(incluyendo, los propiedades borradas) de la base de datos?');
    }
    return confirm("Realmente, desea borrar (borrado logico) los datos del cliente: '" + nombre +
                     "', de la base de datos?");
}
function seguroEditar(id, nombre) {
    var nroPropiedades         = document.getElementById('propiedades.'+id).value;
    var nroPropiedadesBorradas = document.getElementById('propiedadesBorradas.'+id).value;

    if ((0 < nroPropiedades) || (0 < nroPropiedadesBorradas)) {
        return confirm("Realmente, desea cambiar los datos del cliente: '" + nombre +
                     "', en la base de datos?");
    }
    return true;
}
</script>
@endsection
