@if ((isset($enlace)) and (!isset($accion) or ('html' == $accion)))
<div class="m-0 p-0">
@if ('reportes' == $enlace)
    <a target="_blank" class="m-0 p-0" href="{{ route($enlace, [$muestra??'Asesor', 'ver']) }}">
        <button type="button" class="btn btn-dark m-0 p-1">Ver PDF</button>
    </a>
    <a target="_blank" class="m-0 p-0" href="{{ route($enlace, [$muestra??'Asesor', 'descargar']) }}">
        <button type="button" class="btn btn-dark m-0 p-1">Descargar PDF</button>
    </a>
@elseif ('reportes.chart' == $enlace)
    <!--a target="_blank" class="m-0 p-0" href="{{ route($enlace, [$tipo??'line', 'ver']) }}"-->
    <a target="_blank" class="enlaceDesabilitado m-0 p-0" name="{{ route($enlace, [$tipo??'line', 'ver']) }}">
        <button type="button" class="btn btn-dark m-0 p-1">Ver PDF</button>
    </a>
    <!--a target="_blank" class="m-0 p-0" href="{{ route($enlace, [$tipo??'line', 'descargar']) }}"-->
    <a target="_blank" class="enlaceDesabilitado m-0 p-0" name="{{ route($enlace, [$tipo??'line', 'descargar']) }}">
        <button type="button" class="btn btn-dark m-0 p-1">Descargar PDF</button>
    </a>
@elseif (('contactos' == $enlace) or ('users' == $enlace) or ('turnos' == $enlace) or
        ('agenda' == $enlace) or ('propiedades' == $enlace) or ('clientes' == $enlace))
    <a target="_blank" class="m-0 p-0" href="{{ route($enlace.'.orden', [$orden??'id', 'ver']) }}">
        <button type="button" class="btn btn-dark m-0 p-1">Ver PDF</button>
    </a>
    <a target="_blank" class="m-0 p-0" href="{{ route($enlace.'.orden', [$orden??'id', 'descargar']) }}">
        <button type="button" class="btn btn-dark m-0 p-1">Descargar PDF</button>
    </a>
@else ('reportes' == $enlace)
    <a target="_blank" class="m-0 p-0" href="{{ route($enlace, [$orden??'id', 'ver']) }}">
        <button type="button" class="btn btn-dark m-0 p-1">Ver PDF</button>
    </a>
    <a target="_blank" class="m-0 p-0" href="{{ route($enlace, [$orden??'id', 'descargar']) }}">
        <button type="button" class="btn btn-dark m-0 p-1">Descargar PDF</button>
    </a>
@endif ('reportes' == $enlace)
</div>
@endif ((isset($enlace)) and (!isset($accion) or ('html' == $accion)))
