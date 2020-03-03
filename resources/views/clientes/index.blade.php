@extends('layouts.app')

@section('content')

@if (isset($accion) and ('html' != $accion))
    <div>
        <h4 style="text-align:center">
            {{ $title }}
        </h4>
    </div>
@endif (isset($accion) and ('html' != $accion))

@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))),
                'clientes.vmenu', ['nCol' => 2])

@if ($clientes->isNotEmpty())
{{--@if ($paginar)
    {{ $clientes->links() }}
@endif ($paginar)--}}
    <table
    @if (!isset($accion) or ('html' == $accion))
        class="table table-striped table-hover table-bordered m-0 p-0"
        style="font-size:0.75rem"
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
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'cedula') }}">
                    Cedula
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'rif') }}">
                    Rif
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'name') }}">
                    Nombre
                </a>
            </th>
        @if (!$movil)
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'tipo') }}">
                    Tipo
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'telefono') }}">
                    Telefono
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'email') }}">
                    Correo
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'fecha_nacimiento') }}">
                    Fec.Nac.
                </a>
            </th>
            @if (Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('clientes.orden', 'user_id') }}">
                    Creado por
                </a>
            </th>
            @endif
        @if (!isset($accion) or ('html' == $accion))
            <th class="m-0 p-0" scope="col">Acciones</th>
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
        m-0 p-0">
            <td class="text-right m-0 py-0 pl-0 pr-1">
            @if ($movil)
                <a href="{{ route('clientes.show', $cliente) }}"
                   class="btn btn-link m-0 p-0" style="text-decoration:none">
                    {{ $cliente->cedula_f }}
                </a>
            @else ($movil)
                {{ $cliente->cedula_f }}
            @endif ($movil)
            </td>
            <td class="text-right m-0 py-0 pl-0 pr-1">{{ $cliente->rif_f }}</td>
            <td class="m-0 py-0 pl-1 pr-0">{{ $cliente->name }}</td>
        @if (!$movil)
            <td class="m-0 py-0 pl-1 pr-0">
                {{ substr($cliente->tipo_alfa, 0, 7) }}
            </td>
            <td class="text-right m-0 py-0 pl-0 pr-1">
                {{ $cliente->telefono_f }}
                @if ($cliente->otro_telefono)
                    @if ($cliente->telefono_f)
                    <br>
                    @endif ($cliente->telefono_f)
                    {{ $cliente->otro_telefono }}
                @endif ($cliente->otro_telefono)
            </td>
            <td class="m-0 py-0 pl-1 pr-0">{{ $cliente->email }}</td>
            <td class="text-center m-0 p-0">
                {{ $cliente->fec_nac }}
            </td>
            @if (Auth::user()->is_admin)
            <td class="m-0 py-0 pl-1 pr-0">{{ $cliente->user->name }}</td>
            @endif
        @if (!isset($accion) or ('html' == $accion))
            <td class="d-flex align-items-end m-0 py-0 px-1">
                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-link m-0 p-0"
                        title="Mostrar los datos de este cliente ({{ $cliente->name }}).">
                    <span class="oi oi-eye m-0 p-0"></span>
                </a>
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-link m-0 p-0"
                    title="Editar los datos del cliente ({{ $cliente->name }})."
                    onclick="return seguroEditar({{ $cliente->id }}, '{{ $cliente->name }}')">
                    <span class="oi oi-pencil m-0 p-0"></span>
                </a>

                @if (Auth::user()->is_admin)
                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST"
                        class="form-inline m-0 p-0" id="forma.{{ $cliente->id }}"
                        name="forma.{{ $cliente->id }}"
                        onSubmit="return seguroBorrar({{ $cliente->id }}, '{{ $cliente->name }}')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <input type="hidden" name="propiedades" id="propiedades.{{ $cliente->id }}"
                            value="{{ $cliente->propiedades->count()-$cliente->propiedadesBorradas($cliente->id)->count() }}">
                    <input type="hidden" name="propiedadesBorradas"
                            id="propiedadesBorradas.{{ $cliente->id }}"
                            value="{{ $cliente->propiedadesBorradas($cliente->id)->count() }}">

                    <button class="btn btn-link m-0 p-0" title="Borrar (lógico) cliente.">
                        <span class="oi oi-trash m-0 p-0" title="Borrar {{ $cliente->name }}"></span>
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
@if ($paginar)
    {{ $clientes->links() }}
@endif ($paginar)

@include('include.botonesPdf', ['enlace' => 'clientes'])

@else ($clientes->isNotEmpty())
    <p>No hay clientes registrados.</p>
@endif ($clientes->isNotEmpty())

@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))),
                'clientes.vmenuCierre')

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
