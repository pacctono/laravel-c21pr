@if ((isset($enlace)) and (!isset($accion) or ('html' == $accion)))
    <a target="_blank" href="{{ route($enlace.'.orden', [$orden??'id', 'ver']) }}">
        <button>Ver PDF</button>
    </a>
    <a target="_blank" href="{{ route($enlace.'.orden', [$orden??'id', 'descargar']) }}">
        <button>Descargar PDF</button>
    </a>
@endif ((isset($enlace)) and (!isset($accion) or ('html' == $accion)))