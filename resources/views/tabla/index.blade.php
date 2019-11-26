@extends('layouts.app')

@section('content')
    @if (!isset($accion) or ('html' == $accion))
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

    @if ('texto' != $elemento)
        <p>
            <a href="{{ route($rutCrear) }}" class="btn btn-primary">
                Crear {{ ucfirst($elemento) }}
            </a>
        </p>
    @endif ('texto' != $elemento)
    </div>
    @else (!isset($accion) or ('html' == $accion))
        <h1 style="text-align:center">{{ $title }}</h1>
    @endif (!isset($accion) or ('html' == $accion))

    @if ($arreglo->isNotEmpty())
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
        @if ((!$movil) and ('texto' != $elemento))
        @if (!isset($accion) or ('html' == $accion))
            <th scope="col">#</th>
        @endif (!isset($accion) or ('html' == $accion))
            <th scope="col">id</th>
        @endif ((!$movil) and ('texto' != $elemento))
            <th scope="col">Descripcion</th>
        @if ('texto' == $elemento)
            <th scope="col">Enlace</th>
            <th scope="col">Texto del enlace</th>
        @endif ('texto' != $elemento)
        @if (!$movil and (!isset($accion) or ('html' == $accion)))
            <th scope="col">Acciones</th>
        @endif (!$movil and (!isset($accion) or ('html' == $accion)))
        </tr>
        </thead>
        <tbody>
        @foreach ($arreglo as $arrInd)
        <tr class="
        @if (0 == ($loop->iteration % 2))
            table-primary
        @else
            table-info
        @endif
        ">
        @if ((!$movil) and ('texto' != $elemento))
        @if (!isset($accion) or ('html' == $accion))
            <th scope="row">{{ $loop->iteration }}</th>
        @endif (!isset($accion) or ('html' == $accion))
            <td>{{ $arrInd->id }}</td>
        @endif ((!$movil) and ('texto' != $elemento))
            <td>
            @if ($enlace and (!isset($accion) or ('html' == $accion)))
                <a href="{{ route('reporte.'.$enlace.ucfirst($elemento), [$arrInd->id, 'id']) }}"
                    class="btn btn-link">
                    {{ $arrInd->descripcion }}
                </a>
            @else ($enlace)
                {{ $arrInd->descripcion }}
            @endif ($enlace)
            </td>
        @if ('texto' == $elemento)
            <td>
                {{ $arrInd->enlace??'' }}
            </td>
            <td>
                {{ $arrInd->textoEnlace??'' }}
            </td>
        @endif ('texto' != $elemento)
        @if (!$movil and (!isset($accion) or ('html' == $accion)))
            <td>
            @if ($enlace)
                <form action="{{ route($rutBorrar, $arrInd) }}" method="POST"
                        id="forma.{{ $arrInd->id }}" name="forma.{{ $arrInd->id }}"
                        onSubmit="return seguroBorrar({{ $arrInd->id }}, '{{ $elemento }}', '{{ $arrInd->descripcion }}')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <input type="hidden" name="{{ $enlace }}" id="{{ $enlace }}.{{ $arrInd->id }}"
                            value="{{ $arrInd->$enlace->count()-$arrInd->$metBorradas($arrInd->id)->count() }}">
                    <input type="hidden" name="{{ $enlace }}Borradas"
                            id="{{ $enlace }}Borradas.{{ $arrInd->id }}"
                            value="{{ $arrInd->$metBorradas($arrInd->id)->count() }}">
                    <a href="{{ route($rutEditar, $arrInd) }}" class="btn btn-link"
                        onclick="return seguroEditar({{ $arrInd->id }}, '{{ $elemento }}', '{{ $arrInd->descripcion }}')">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <button class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
            @else ($enlace)
                <a href="{{ route($rutEditar, $arrInd) }}" class="btn btn-link">
                    <span class="oi oi-pencil"></span>
                </a>
                <a href="" class="btn btn-link"
                    title="Cargar al servidor el archivo de la imagen{{ $arrInd->id-1 }}.jpg">
                    <span class="oi oi-data-transfer-upload"></span>
                </a>
            @endif ($enlace)
            </td>
        @endif (!$movil and (!isset($accion) or ('html' == $accion)))
        </tr>
        @endForeach
        </tbody>
    </table>
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $arreglo->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))

@include('include.botonesPdf', ['enlace' => $elemento])

@else ($turnos->isNotEmpty())
    <p>No hay {{ strtolower($elemento) }} registrados.</p>
@endif ($turnos->isNotEmpty())

@endsection

@section('js')
<script>
function seguroBorrar(id, ele, desc) {
    var nroEnlaces         = document.getElementById('{{ $enlace }}.'+id).value;
    var nroEnlacesBorradas = document.getElementById('{{ $enlace }}Borradas.'+id).value;

    if (0 < nroEnlaces) {
        alert('Este ' + ele + ": '" + desc + "', ha sido asignado a <" + nroEnlaces +
                            '> {{ $enlace }}, por lo tanto, no puede borrar sus datos.');
        return false;
    }
    if (0 < nroEnlacesBorradas) {
        return confirm('Este ' + ele + ": '" + desc + "',  está en " + nroEnlacesBorradas +
                            " '{{ ucfirst($enlace) }} borradas', " +
                            'esta seguro de querer borrar sus datos ' + 
                            '(incluyendo, los {{ $enlace }} borradas) de la base de datos?');
    }
    return confirm('Realmente, desea borrar los datos del ' + ele + ": '" + desc +
                     "', de la base de datos?");
}
function seguroEditar(id, ele, desc) {
    var nroEnlaces         = document.getElementById('{{ $enlace }}.'+id).value;
    var nroEnlacesBorradas = document.getElementById('{{ $enlace }}Borradas.'+id).value;

    if ((0 < nroEnlaces) || (0 < nroEnlacesBorradas)) {
        return confirm('Realmente, desea cambiar la descripción del ' + ele + ": '" + desc +
                     "', en la base de datos? Las estadisticas anteriores aparecerean con " +
                     'la nueva descripcion!');
    }
    return true;
}
</script>
@endsection
