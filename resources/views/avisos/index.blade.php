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
                'avisos.vmenu', ['nCol' => 2])

@if ($avisos->isNotEmpty())
{{--@if ($paginar)
    {{ $avisos->links() }}
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
                    @endif "{{ route('avisos', 'user_id') }}">
                    Asesor
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('avisos', 'fecha') }}">
                    Fecha
                </a>
            </th>
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('avisos', 'descripcion') }}">
                    Descripcion
                </a>
            </th>
        @if (!$movil)
            @if (Auth::user()->is_admin)
            <th class="m-0 p-0" scope="col">
                <a class=@if('html'==$accion) "btn btn-link m-0 p-0" href=
                    @else "enlaceDesabilitado" name=
                    @endif "{{ route('avisos', 'user_id') }}">
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
        @foreach ($avisos as $aviso)
        <tr class="
        @if ('C' == $aviso->tipo)
            table-danger
        @elseif ('T' == $aviso->tipo)
            table-warning
        @elseif (0 == ($loop->iteration % 2))
            table-primary
        @else
            table-info
        @endif
        my-0 py-0">
            <td class="text-left m-0 py-0 px-1" id="asesor.{{ $aviso->id }}">
                {{ $aviso->user->name }}
            </td>
            <td class="text-right m-0 py-0 px-1" id="fec.{{ $aviso->id }}">
                {{ $aviso->fec }}
            </td>
            <td class="m-0 py-0 px-1" id="descripcion.{{ $aviso->id }}">
                {!! $aviso->descripcion !!}
            </td>
        @if (!$movil)
            @if (Auth::user()->is_admin)
            <td class="m-0 py-0 px-1">
                {{ (1 == $aviso->user_creo)?'Administrador':$aviso->userCreo->name }}
            </td>
            @endif
        @if (!isset($accion) or ('html' == $accion))
            <td class="d-flex align-items-end m-0 py-0 px-1">
            {{--@if (('C' != $aviso->tipo) and ('T' != $aviso->tipo))       <!-- Nada que mostrar -->
                <a href="{{ route('avisos.show', $aviso) }}" class="btn btn-link m-0 p-0"
                        title="Mostrar los datos de este aviso ({{ $aviso->desc }}).">
                    <span class="oi oi-eye m-0 p-0"></span>
                </a>
            @else
                &nbsp;
            @endif (('C' != $aviso->tipo) and ('T' != $aviso->tipo))--}}
            @if (('C' != $aviso->tipo) and ('T' != $aviso->tipo))           <!-- Es un turno, no se puede editar. -->
                <a href="{{ route('avisos.editar', $aviso) }}" class="btn btn-link m-0 p-0 aviso"
                    title="Editar los datos de este aviso ({{ $aviso->desc }})." id="{{ $aviso->id }}">
                    <span class="oi oi-pencil m-0 p-0"></span>
                </a>
            @else
                &nbsp;
            @endif (('C' != $aviso->tipo) and ('T' != $aviso->tipo))

            @if (Auth::user()->is_admin)
            @if (('C' != $aviso->tipo) and ('T' != $aviso->tipo))           <!-- Es un turno, no se puede borrar. -->
                <form action="{{ route('avisos.destroy', $aviso) }}" method="POST"
                        class="form-inline m-0 p-0 aviso" id="forma.{{ $aviso->id }}"
                        name="forma.{{ $aviso->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button class="btn btn-link m-0 p-0" title="Borrar (lógico) aviso.">
                        <span class="oi oi-trash m-0 p-0" title="Borrar este aviso ({{ $aviso->desc }})."></span>
                    </button>
                </form>
            @else
                &nbsp;
            @endif (('C' != $aviso->tipo) and ('T' != $aviso->tipo))
            @endif (Auth::user()->is_admin)
            </td>
        @endif (!isset($accion) or ('html' == $accion))
        @endif (!$movil)
        </tr>
        @endForeach
        </tbody>
    </table>
@if ($paginar)
    {{ $avisos->links() }}
@endif ($paginar)

@include('include.botonesPdf', ['enlace' => 'avisos'])

@else ($avisos->isNotEmpty())
    <p>No hay avisos registrados.</p>
@endif ($avisos->isNotEmpty())

@includeWhen((!$movil and (!isset($accion) or ('html' == $accion))),
                'avisos.vmenuCierre')

@endsection

@section('js')

@includeIf("avisos.jqmenu")
<script>
    $(document).ready(function() {
        $("a.aviso").click(function(ev){
            let accion = confirm(`Realmente desea editar este aviso ().`);
            if (!accion) ev.preventDefault();
        })
        $("form.aviso").submit(function(ev){
            let accion = confirm(`Realmente desea borrar este aviso ().`);
            if (!accion) ev.preventDefault();
        })
    })
</script>
@endsection
