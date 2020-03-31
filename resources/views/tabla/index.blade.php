@extends('layouts.app')

@section('content')
    @if (!isset($accion) or ('html' == $accion))
    <div class="d-flex justify-content-between align-items-end my-0 py-0">
        <h3 class="my-0 py-0">{{ $title }}</h3>

    @if ('texto' != $elemento)
        <a href="{{ route($rutCrear) }}" class="btn btn-primary my-0 py-0">
            Crear {{ ucfirst($elemento) }}
        </a>
    @endif ('texto' != $elemento)
    </div>
    @else (!isset($accion) or ('html' == $accion))
        <h3 style="text-align:center">{{ $title }}</h3>
    @endif (!isset($accion) or ('html' == $accion))

    @if ($arreglo->isNotEmpty())
@if ((!$movil) and (!isset($accion) or ('html' == $accion)))
    {{ $arreglo->links() }}
@endif ((!$movil) and (!isset($accion) or ('html' == $accion)))
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
        @else
            class="m-0 p-0"
        @endif ('html' != $accion)
        >
        @if ((!$movil) and ('texto' != $elemento))
        @if (!isset($accion) or ('html' == $accion))
            <th class="my-0 py-0" scope="col">#</th>
        @endif (!isset($accion) or ('html' == $accion))
            <th class="my-0 py-0" scope="col">id</th>
        @endif ((!$movil) and ('texto' != $elemento))
        @if ('feriado' == $elemento)
            <th class="my-0 py-0" scope="col">Fecha</th>
            <th class="my-0 py-0" scope="col">Tipo</th>
        @endif ('feriado' == $elemento)
            <th class="my-0 py-0" scope="col">Descripcion</th>
        @if ('texto' == $elemento)
            <th class="my-0 py-0" scope="col">Enlace</th>
            <th class="my-0 py-0" scope="col">Texto del enlace</th>
        @endif ('texto' != $elemento)
        @if (!$movil and (!isset($accion) or ('html' == $accion)))
            <th class="my-0 py-0" scope="col">Acciones</th>
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
        my-0 py-0">
        @if ((!$movil) and ('texto' != $elemento))
        @if (!isset($accion) or ('html' == $accion))
            <th class="my-0 py-0" scope="row">{{ $loop->iteration }}</th>
        @endif (!isset($accion) or ('html' == $accion))
            <td class="my-0 py-0">{{ $arrInd->id }}</td>
        @endif ((!$movil) and ('texto' != $elemento))
        @if ('feriado' == $elemento)
            <td class="my-0 py-0" scope="col">
                {{ $arrInd->fecha_dia_semana }}, {{ $arrInd->fecha_en }}
            </td>
            <td class="my-0 py-0" scope="col">
                {{ $arrInd->tipo }}
            </td>
        @endif ('feriado' == $elemento)
            <td class="my-0 py-0">
            @if ($enlace and (!isset($accion) or ('html' == $accion)) and (0 < $arrInd->$enlace->count()))
                <a href="{{ route('reporte.'.$enlace.ucfirst($elemento), [$arrInd->id, 'id']) }}"
                    class="btn btn-link m-0 p-0" title="{{ $arrInd->$enlace->count() . ' ' .$enlace }}">
                    {{ $arrInd->descripcion }}
                </a>
            @else ($enlace){{-- $enlace:contactos|propiedades|False --}}
                {{ $arrInd->descripcion }}
            @endif ($enlace)
            </td>
        @if ('texto' == $elemento)
            <td class="my-0 py-0">
                {{ $arrInd->enlace??'' }}
            </td>
            <td class="my-0 py-0">
                {{ $arrInd->textoEnlace??'' }}
            </td>
        @endif ('texto' != $elemento)
        @if (!$movil and (!isset($accion) or ('html' == $accion)))
            <td class="d-flex align-items-end m-0 p-0">
            @if ($enlace){{-- $enlace:contactos|propiedades|False --}}
                <a href="{{ route($rutEditar, $arrInd) }}" class="btn btn-link m-0 p-0"
                    onclick="return seguroEditar({{ $arrInd->id }}, '{{ $elemento }}', '{{ $arrInd->descripcion }}')">
                    <span class="oi oi-pencil m-0 py-0 px-1"></span>
                </a>
                <form action="{{ route($rutBorrar, $arrInd) }}" method="POST"
                        id="forma.{{ $arrInd->id }}" name="forma.{{ $arrInd->id }}"
                        onSubmit="return seguroBorrar({{ $arrInd->id }}, '{{ $elemento }}', '{{ $arrInd->descripcion }}')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <input type="hidden" name="{{ $enlace }}" id="{{ $enlace }}.{{ $arrInd->id }}"
                            value="{{ $arrInd->$enlace->count()-$arrInd->$metBorradas($arrInd->id)->count() }}">
                    <input type="hidden" name="{{ $metBorradas }}"
                            id="{{ $metBorradas }}.{{ $arrInd->id }}"
                            value="{{ $arrInd->$metBorradas($arrInd->id)->count() }}">
                    <button class="btn btn-link m-0 p-0"><span class="oi oi-trash m-0 py-0 px-1"></span></button>
                </form>
                @if (('tipo' == $elemento) and (0 < $arrInd->contactos->count()))
                <a href="{{ route('reporte.contactos'.ucfirst($elemento), [$arrInd->id, 'id']) }}"
                    class="btn btn-link m-0 p-0" title="{{ $arrInd->contactos->count() . ' contactos' }}">
                    <span class="oi oi-people m-0 py-0 px-1"></span>
                </a>
                @endif ('tipo' == $elemento)
            @else ($enlace)
                <a href="{{ route($rutEditar, $arrInd) }}" class="btn btn-link">
                    <span class="oi oi-pencil m-0 py-0 px-1"></span>
                </a>
            @if ('texto' == $elemento)
                <a href="" class="btn btn-link m-0 p-0"
                    title="Cargar al servidor el archivo de la imagen{{ $arrInd->id-1 }}.jpg">
                    <span class="oi oi-data-transfer-upload m-0 py-0 px-1"></span>
                </a>
            @endif ('texto' == $elemento)
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
    var nroEnlacesBorradas = document.getElementById('{{ $metBorradas }}.'+id).value;

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
    var nroEnlacesBorradas = document.getElementById('{{ $metBorradas }}.'+id).value;

    if ((0 < nroEnlaces) || (0 < nroEnlacesBorradas)) {
        return confirm('Realmente, desea cambiar la descripción del ' + ele + ": '" + desc +
                     "', en la base de datos? Las estadisticas anteriores aparecerean con " +
                     'la nueva descripcion!');
    }
    return true;
}
</script>
@endsection
