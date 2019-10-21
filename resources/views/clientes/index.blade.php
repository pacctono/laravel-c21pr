@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-1">
        @if ($movil)
        <h4 class="pb-1">{{ substr($title, 11) }}</h4>
        @else
        <h1 class="pb-1">{{ $title }}</h1>
        @endif

        <p>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            @if ($movil)
                Crear
            @else
                Crear Cliente
            @endif
            </a>
        </p>
    </div>

    @if ($clientes->isNotEmpty())
    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'cedula') }}" class="btn btn-link">
                    Cedula
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'rif') }}" class="btn btn-link">
                    Rif
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'name') }}" class="btn btn-link">
                    Nombre
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'telefono') }}" class="btn btn-link">
                    Telefono
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'email') }}" class="btn btn-link">
                    Correo
                </a>
            </th>
            <th scope="col">
                <a href="{{ route('clientes.orden', 'created_at') }}" class="btn btn-link">
                    Fec.Nac.
                </a>
            </th>
            @if (Auth::user()->is_admin)
            <th scope="col">
                <a href="{{ route('clientes.orden', 'user_id') }}" class="btn btn-link">
                    Creado por
                </a>
            </th>
            @endif
            <th scope="col">Acciones</th>
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
            <td>{{ $cliente->cedula_f }}</td>
            <td>{{ $cliente->rif_f }}</td>
            <td>{{ $cliente->name }}</td>
            <td>
                {{ $cliente->telefono_f }}
            </td>
            <td>{{ $cliente->email }}</td>
            <td>
                {{ $cliente->fec_nac }}
            </td>
            @if (Auth::user()->is_admin)
            <td>{{ $cliente->user->name }}</td>
            @endif
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
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $clientes->links() }}
    @else
        <p>No hay clientes registrados.</p>
    @endif

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
