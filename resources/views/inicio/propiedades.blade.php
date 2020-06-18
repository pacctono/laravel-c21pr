@extends('layouts.inicio')

@section('content')
                <div class="container-fluid">
                    <div id="propiedades" class="m-0 p-0">
                        <!--h4 class="card-header m-0 pt-3 pb-0 px-0 colorFondo1">PROPIEDADES</h4-->
                        <div class="fondoPropiedades rounded m-0 pt-3 pb-0 px-0"
                        @if (isset($asesor) and ('A' == $tipo))
                                style="background-image:url({{ asset('img/fondoAsesoresKR.jpg') }})">
                            <div class="row justify-content-center{{-- start|end|around|between --}} m-0 p-0">
                                <div class="col-lg-4 col-sm-12">
                                    <h5>{{ (1 < $asesor->id)?$asesor->name:'Administrador' }}</h5>
                                    <div
                                    @if ($asesor->foto)
                                            class="bg-transparent"
                                    @else (!file_exists($asesor->foto))
                                            class="bg-info w-100" style="height:285px;"
                                    @endif (!file_exists($asesor->foto))
                                    >
                                        <img class="rounded" src="{{ asset($asesor::DIR_PUBIMG . $asesor->foto) }}"
                                                alt="Foto de {{ (1 < $asesor->id)?$asesor->name:'Administrador' }}">
                                    </div>
                                    <div class="m-0 p-0 w-100">
                                        <div class="row bg-transparent justify-content-center mt-0 mb-0 mx-0 p-0">
                                            <p class="rounded colorFondo1 m-0 px-1 py-0"><i class="fa fa-mobile-alt m-0 p-0"></i>
                                                {{ $asesor->telefono_f }}</p>
                                        </div>
                                        <div class="row bg-transparent justify-content-center mt-0 mb-0 mx-0 p-0">
                                            <p class="rounded colorFondo1 m-0 px-1 py-0"><i class="fa fa-at m-0 p-0"></i>
                                                {{ $asesor->email }}</p>
                                        </div>
                                    </div>
                                    <hr class="d-sm-none"><!-- Solo muestra la raya en sm -->
                                </div>
                            </div>
                        @else ('A' == $tipo)
                                style="background-image:url({{ asset('img/fondoPropiedadesLR.jpg') }})">
                        @endif ('A' == $tipo)
                        @foreach ($propiedades as $propiedad)
                            <div class="row m-0 p-0">
                                <div class="col-lg-3 col-sm-12 my-1 mx-0 p-0">
                                @if ($propiedad->imagen)
                                    <div class="bg-transparent d-block mx-auto my-0 px-0 py-0">
                                @else ($propiedad->imagen)
                                    <div class="bg-info w-100 d-block mx-auto my-0 px-0 py-0" style="height:300px;">
                                @endif ($propiedad->imagen)
                                        <img class="img-fluid rounded m-0 p-0 imagenPropiedad"
                                                src="{{ asset('storage/imgprop/'.$propiedad->imagen) }}"
                                                alt="{{ $propiedad->nombre }}" data-toggle="tooltip"
                                                title="{{ $propiedad->nombre }}">
                                    </div>
                                @if ((1 < count($propiedad->imagenes)) and ('P' != $tipo))
                                    <div class="d-block mx-auto my-0 px-0 py-0 {{--border border-dark--}}">
                                        <div class="row bg-transparent justify-content-center m-0 p-0">
                                            <a class="m-0 p-0 mostrarimagen" href=""
                                                    data-toggle="tooltip" data-html="true"
                                                    nombreBase="{{ $propiedad->id }}_{{ $propiedad->codigo }}"
                                                    img="{{ json_encode($propiedad->imagenes, JSON_FORCE_OBJECT) }}"
                                                    nombre="{{ $propiedad->nombre }}"
                                                    title="Mostrar todas las fotos">
                                                <button class="btn btn-sm m-0 p-0">Ver más fotos</button>
                                            </a>
                                        </div>
                                    </div>
                                @else (1 < count($propiedad->imagenes))
                                    <div class="d-block mx-auto my-1 px-0 py-0 {{--border border-dark--}}">
                                    </div>
                                @endif (1 < count($propiedad->imagenes))
                                </div>
                                <div class="col-lg-3 col-sm-12 m-0 p-0">
                                    <div class="d-block mx-auto my-1 pl-0 pr-0 py-0 {{--border border-dark--}}">
                                        <div class="row rounded-top colorFondo3 justify-content-center m-0 p-0">
                                            <p class="font-weight-bold m-0 p-0">
                                                {{ $propiedad->nombre }}
                                            </p>
                                        </div>
                                        <div class="rounded-bottom colorFondo3 ml-0 mr-0 my-0 p-0">
                                            <ul class="list-group list-group-flush colorFondo3 mx-1 my-0 p-0">
                                                <li class="fas fa-bed fa-lg colorFondo3 mx-0 my-1 p-0"
                                                        style="color:black" title="habitaciones">
                                                    {{ $propiedad->habitaciones??1 }}</li>
                                                <li class="fas fa-bath fa-lg colorFondo3 mx-0 my-1 p-0"
                                                        style="color:black" title="baños">
                                                    {{ $propiedad->banos??1 }}</li>
                                                <li class="fas fa-car fa-lg colorFondo3 mx-0 my-1 p-0"
                                                        style="color:black" title="puestos de estacionamiento">
                                                    {{ $propiedad->puestos??1 }}</li>
                                                <li class="fas fa-coins fa-lg colorFondo3 mx-0 my-1 p-0"
                                                        style="color:black" title="niveles">
                                                    {{ $propiedad->niveles??1 }}</li>
                                                <!--li class="list-group-item fas fa-building fa-lg"
                                                        style="color:black" title="caracteristicas"-->
                                                <li class="fas fa-landmark fa-lg colorFondo3 mx-0 my-1 p-0"
                                                        style="color:black" title="caracteristicas">
                                                    {{ $propiedad->caracteristica->descripcion }}</li>
                                                <li class="fas fa-ruler-combined fa-lg colorFondo3 mx-0 my-1 p-0"
                                                        style="color:black" title="metraje">
                                                    {{ ($propiedad->metraje)?$propiedad->metraje:'' }}
                                                    {{ ($propiedad->metraje)?'metros cuadrados':'' }}
                                                </li>
                                                <li class="fas fa-address-card fa-lg m-1 p-0"
                                                        style="color:black" title="dirección">
                                                    {{ $propiedad->direccion }}
                                                </li>
                                            </ul>
                                        @if ('A' != $tipo)
                                            <p class="m-0 p-0">
                                                <i class="fas fa-user-shield fa-lg m-1 p-0" style="color:black" title="Asesor"></i>
                                                {{ $propiedad->captador->name }}
                                            </p>
                                            <p class="m-0 p-0">
                                                <i class="fas fa-mobile-alt fa-sm mx-1 my-0 p-0" style="color:black"></i>
                                                {{ $propiedad->captador->telefono_f }}
                                            </p>
                                            <p class="m-0 p-0">
                                                <a class="btn btn-link mx-1 my-0 p-0 mostrarTooltip"
                                                        href="mailto://{{ $propiedad->captador->email }}"
                                                        data-toggle="tooltip"
                                                        title="Enviar correo a {{ $propiedad->captador->nombre }}">
                                                    <i class="fas fa-at fa-sm m-0 p-0" style="color:black"></i><!-- Tambien: fa-xs, fa-sm, fa-1x,..., fa-10x -->
                                                </a>
                                                <small class="m-0 p-0">{{ $propiedad->captador->email }}</small>
                                            </p>
                                        @endif ('A' != $tipo)
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 m-0 p-0">
                                    <div class="rounded colorFondo2 mx-0 my-1 pl-0 pr-0 py-0 {{--border border-dark--}}">
                                        <p class="text-light mx-1 my-0 p-0">{{ $propiedad->descripcion }}</p>
                                            <!--p class="m-0 p-0">{{ substr($propiedad->descripcion, 0, 200) }}...</p-->
                                    </div>
                                </div>
                                <hr class="d-sm-none"><!-- Solo muestra la raya en sm -->
                            </div>
                        @endforeach ($propiedades as $propiedad)
                        @if (('P' == $tipo) and $propiedad and (1 < count($propiedad->imagenes)))
                            <div class="row m-0 p-0">
                            @foreach ($propiedad->imagenes as $k => $ext)
                            @if ("{$propiedad->id}_{$propiedad->codigo}-$k.$ext" != $propiedad->imagen)
                                <div class="col-lg-3 col-sm-12 m-0 p-0">
                                    <div class="bg-transparent d-block mx-auto my-0 pl-0 pr-1 py-0">
                                        <img class="img-fluid rounded m-0 p-0"
                                                src="{{ asset($propiedad::DIR_PUBIMG.$propiedad->id.'_'.$propiedad->codigo.'-'.$k.'.'.$ext) }}"
                                                alt="{{ $propiedad->nombre }}">
                                    </div>
                                </div>
                            @endif ("{$propiedad->id}_{$propiedad->codigo}-$k.$ext" != $propiedad->id)
                            @endforeach
                            </div>
                        @endif (('P' == $tipo) and $propiedad and (1 < count($propiedad->imagenes)))
                        </div>
                    </div>
                </div>
@endsection

@if (!isset($accion) or ('html' == $accion))
@section('js')

<script>
    @includeIf('include.alertar')
    $(document).ready(function() {
    // revisa todas las imagenes en la página.
        $("img").each(function(){
            let img = new Image($(this));
            img.onload = function() {
                console.log($(this).attr('src') + ' - descargada!');
            }
            img.onerror = function() {
                console.log($(this).attr('src') + ' - error!');
            }
            img.onabort = function() {
                console.log($(this).attr('src') + ' - abortada!');
            }
            img.src = $(this).attr('src');
        });
    @includeIf('include.mostrarImagen')
    })
</script>

@endsection('js')
@endif (!isset($accion) or ('html' == $accion))
