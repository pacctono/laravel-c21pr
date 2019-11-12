@if ((isset($enlace)) and (!isset($accion) or ('html' == $accion)))
@if ('reportes' == $enlace)
    <a target="_blank" href="{{ route($enlace, [$muestra??'Asesor', 'ver']) }}">
        <button>Ver PDF</button>
    </a>
    <a target="_blank" href="{{ route($enlace, [$muestra??'Asesor', 'descargar']) }}">
        <button>Descargar PDF</button>
    </a>
@else ('reportes' == $enlace)
    <a target="_blank" href="{{ route($enlace.'.orden', [$orden??'id', 'ver']) }}">
        <button>Ver PDF</button>
    </a>
    <a target="_blank" href="{{ route($enlace.'.orden', [$orden??'id', 'descargar']) }}">
        <button>Descargar PDF</button>
    </a>
@endif ('reportes' == $enlace)
@endif ((isset($enlace)) and (!isset($accion) or ('html' == $accion)))