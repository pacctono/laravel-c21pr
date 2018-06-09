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
            <th scope="col">#</th>
            <th scope="col">id</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($arreglo as $arrInd)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $arrInd->id }}</td>
            <td>
                <a href="{{ route('reporte.contactos'.ucfirst($elemento), [$arrInd->id, 'id']) }}" class="btn btn-link">
                    {{ $arrInd->descripcion }}
                </a>
            </td>
            <td>
                <form action="{{ route($rutBorrar, $arrInd) }}" method="POST"
                        id="forma.{{ $arrInd->id }}" name="forma.{{ $arrInd->id }}"
                        onSubmit="return seguroBorrar({{ $arrInd->id }}, '{{ $elemento }}', '{{ $arrInd->descripcion }}')">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <input type="hidden" name="contactos" id="contactos.{{ $arrInd->id }}"
                            value="{{ $arrInd->contactos->count()-$arrInd->contactosBorrados($arrInd->id)->count() }}">
                    <input type="hidden" name="contactosBorrados"
                            id="contactosBorrados.{{ $arrInd->id }}"
                            value="{{ $arrInd->contactosBorrados($arrInd->id)->count() }}">
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
    var nroContactos         = document.getElementById('contactos.'+id).value;
    var nroContactosBorrados = document.getElementById('contactosBorrados.'+id).value;

    if (0 < nroContactos) {
        alert('Este ' + ele + ": '" + desc + "', ha sido asignado a <" + nroContactos +
                            '> contactos iniciales, por lo tanto, no puede borrar sus datos.');
        return false;
    }
    if (0 < nroContactosBorrados) {
        return confirm('Este ' + ele + ": '" + desc + "',  está en " + nroContactosBorrados +
                            " 'Contactos Iniciales borrados', " +
                            'esta seguro de querer borrar sus datos ' + 
                            '(incluyendo, los contactos iniciales borrados) de la base de datos?');
    }
    return confirm('Realmente, desea borrar los datos del ' + ele + ": '" + desc +
                     "', de la base de datos?");
}
function seguroEditar(id, ele, desc) {
    var nroContactos         = document.getElementById('contactos.'+id).value;
    var nroContactosBorrados = document.getElementById('contactosBorrados.'+id).value;

    if ((0 < nroContactos) || (0 < nroContactosBorrados)) {
        return confirm('Realmente, desea cambiar la descripción del ' + ele + ": '" + desc +
                     "', en la base de datos? Las estadisticas anteriores aparecerean con " +
                     'la nueva descripcion!');
    }
    return true;
}
</script>
@endsection
