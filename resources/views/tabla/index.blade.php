@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route($rutCrear) }}" class="btn btn-primary">
                Crear {{ ucfirst($elemento) }}
            </a>
        </p>
    </div>

    @if ($arreglo->isNotEmpty())
    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
        @if (!$movil)
            <th scope="col">#</th>
            <th scope="col">id</th>
        @endif (!$movil)
            <th scope="col">Descripcion</th>
            <th scope="col">Acciones</th>
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
        @if (!$movil)
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $arrInd->id }}</td>
        @endif (!$movil)
            <td>
                <a href="{{ route('reporte.'.$enlace.ucfirst($elemento), [$arrInd->id, 'id']) }}" class="btn btn-link">
                    {{ $arrInd->descripcion }}
                </a>
            </td>
            <td>
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
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $arreglo->links() }}
    @else
        <p>No hay {{ strtolower($tipo) }} registrados.</p>
    @endif

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
