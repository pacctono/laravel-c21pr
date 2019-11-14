@if ((isset($enlace)) and (!isset($accion) or ('html' == $accion)))
@if ('reportes' == $enlace)
    <a target="_blank" href="{{ route($enlace, [$muestra??'Asesor', 'ver']) }}">
        <button>Ver PDF</button>
    </a>
    <a target="_blank" href="{{ route($enlace, [$muestra??'Asesor', 'descargar']) }}">
        <button>Descargar PDF</button>
    </a>
@elseif ('reportes.chart' == $enlace)
    <!--a target="_blank" href="{{ route($enlace, [$tipo??'line', 'ver']) }}"-->
    <a target="_blank" class="enlaceDesabilitado" name="{{ route($enlace, [$tipo??'line', 'ver']) }}">
        <button>Ver PDF</button>
    </a>
    <!--a target="_blank" href="{{ route($enlace, [$tipo??'line', 'descargar']) }}"-->
    <a target="_blank" class="enlaceDesabilitado" name="{{ route($enlace, [$tipo??'line', 'descargar']) }}">
        <button>Descargar PDF</button>
    </a>
@elseif (('contactos' == $enlace) or ('users' == $enlace) or ('turnos' == $enlace) or
        ('agenda' == $enlace) or ('propiedades' == $enlace) or ('clientes' == $enlace))
    <a target="_blank" href="{{ route($enlace.'.orden', [$orden??'id', 'ver']) }}">
        <button>Ver PDF</button>
    </a>
    <a target="_blank" href="{{ route($enlace.'.orden', [$orden??'id', 'descargar']) }}">
        <button>Descargar PDF</button>
    </a>
@else ('reportes' == $enlace)
    <a target="_blank" href="{{ route($enlace, [$orden??'id', 'ver']) }}">
        <button>Ver PDF</button>
    </a>
    <a target="_blank" href="{{ route($enlace, [$orden??'id', 'descargar']) }}">
        <button>Descargar PDF</button>
    </a>
@endif ('reportes' == $enlace)
@endif ((isset($enlace)) and (!isset($accion) or ('html' == $accion)))