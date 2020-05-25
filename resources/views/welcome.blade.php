@extends('layouts.inicio')

@section('content')
                <div class="container-fluid">
                    <div id="inicio" class="divInicio" style="background-image:url({{ asset('img/fondoInicio.jpg') }})">
                        <!--img src="{{ (asset('img/fondoInicio.jpg')) }}"
                                alt="Conectado a la web de Century21 Puente Real"
                                class="w-100"-->
                        <div class="textoInicio">
                            <h1 class="mt-0 mb-2 mx-0 p-0">¡BIENVENIDO!</h1>
                            <h2 class="my-1 mx-0 p-0">¿QUÉ ESTÁS BUSCANDO?</h2>
                            <div class="row my-1 mx-0 p-0">
                                <button id="ciudadc" class="ml-0 mr-1 my-0 p-1">CIUDAD DONDE DESEA EL INMUEBLE</button>
                                <button id="negociacion" class="mx-1 my-0 p-1">COMPRA O ALQUILER</button>
                                <button id="tipoc" class="mx-1 my-0 p-1">CASA, APARTAMENTO, TOWNHOUSE, TERRENO</button>
                                <button id="comprar" class="bg-dark text-light ml-2 mr-0 my-0 p-2">BUSCAR</button>
                            </div>
                            <h2 class="mt-2 mb-0 mx-0 p-1">DESEO VENDER MI PROPIEDAD</h2>
                            <div class="row my-1 mx-0 p-0">
                                <button id="ciudadv" class="ml-0 mr-1 my-0 p-1">¿CIUDAD?</button>
                                <button id="nombre" class="mx-1 my-0 p-1">NOMBRE Y APELLIDO</button>
                                <button id="tipov" class="mx-1 my-0 p-1">¿QUÉ DESEA VENDER?</button>
                                <button id="telefono" class="mx-1 my-0 p-1">NÚMERO DE CONTACTO</button>
                                <button id="enviar" class="bg-dark text-light ml-2 mr-0 my-0 p-2">ENVIAR MENSAJE</button>
                            </div>
                        </div>
                    </div>
                    <!--div id="propiedades" class="card m-0 p-0">
                        <h4 class="card-header m-0 p-0">PROPIEDADES</h4>
                        <img src="{{ (asset('img/propiedades.jpg')) }}"
                                alt="Propiedades de Century21 Puente Real"
                                class="card-body w-100 m-0 p-0">
                    </div>
                    <div id="ultPropiedades" class="row w-100 colorFondo1 m-0 p-0"-->
                    <div id="propiedades" class="card m-0 p-0">
                        <h4 class="card-header m-0 p-0">PROPIEDADES</h4>
                        <div class="row card-body fondoPropiedades m-0 p-0"
                            style="background-image:url({{ asset('img/fondoPropiedadesLR.jpg') }})">
                        @foreach ($propiedades as $propiedad)
                            <div class="col-lg-3">
                                <img class="img-fluid m-0 p-0 imagenPropiedad"
                                        src="{{ asset('storage/imgprop/'.$propiedad['nombreImagen']) }}"
                                        alt="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}" data-toggle="tooltip"
                                        title="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}">
                                <div class="m-0 p-0 {{--border border-dark--}}">
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                    <p class="m-0 p-0">
                                        {{ $propiedad['nombre'] }}
                                    </p>
                                    </div>
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                        <a class="m-0 p-0" href="/propiedades/{{ $propiedad['id'] }}">
                                            <button class="btn btn-sm m-0 p-0">Ver inmueble</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach ($propiedades as $propiedad)
                        </div>
                    </div>
                    <div id="asesores" class="card m-0 p-0">
                    @if ($hayFotos)
                        <h4 class="card-header m-0 p-0">ASESORES</h4>
                        <div class="row card-body fondoAsesores m-0 p-0"
                            style="background-image:url({{ asset('img/fondoAsesoresKR.jpg') }})">
                        @foreach ($users as $user)
                            <div class="col-lg-3">
                            @if ($user->foto)
                                <img class="d-block mx-auto @if(5>$loop->iteration) rounded-circle @endif img-fluid my-0 p-0 fotoAsesor"
                                        alt="{{ $user->nombre }}" title="{{ $user->nombre }}"
                                        src="{{ asset($user::DIR_PUBIMG . $user->foto) }}">
                            @else ($foto)
                                <div class="w-100 h-75 m-0 p-1 colorFondo1">
                                    Foto de {{ $user->nombre }}
                                </div>
                            @endif ($foto)
                                <div class="m-0 p-0 {{--border border-dark--}}">
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                        <p class="m-0 p-0">
                                            {{ $user->nombre }}
                                        </p>
                                    </div>
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                        <p class="m-0 p-0">
                                            <i class="fas fa-mobile-alt m-0 p-0"></i>
                                            {{ $user->telefono_f }}
                                        </p>
                                    </div>
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                        <p class="m-0 p-0">
                                            <a class="btn btn-link m-0 p-0 mostrarTooltip"
                                                    href="mailto://{{ $user->email }}"
                                                    data-toggle="tooltip"
                                                    title="Enviar correo a {{ $user->nombre }}">
                                                <i class="fas fa-at fa-sm text-dark m-0 p-0"></i><!-- Tambien: fa-xs, fa-sm, fa-1x,..., fa-10x -->
                                            </a>
                                            <small class="text-muted m-0 p-0">{{ $user->email }}</small>
                                        </p>
                                    </div>
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                    @if (isset($user->ig))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://www.instagram.com/c21puentereal/?hl=es-la"
                                                target="_blank" data-toggle="tooltip"
                                                title="Ver instagram de {{ $user->nombre }}">
                                            <i class="fab fa-instagram fa-lg" style="color:salmon"></i>
                                        </a>
                                    @endif (isset($user->ig))
                                    @if (isset($user->tw))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://twitter.com/c21puentereal"
                                                targe="_blank" data-toggle="tooltip"
                                                title="Ver twitter de {{ $user->nombre }}">
                                            <i class="fab fa-twitter fa-lg" style="color:blue"></i>
                                        </a>
                                    @endif (isset($user->tw))
                                    @if (isset($user->fb))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://es-la.facebook.com/c21puentereal/"
                                                target="_blank" data-toggle="tooltip"
                                                title="Ver Facebook de {{ $user->nombre }}">
                                            <i class="fab fa-facebook fa-lg" style="color:red"></i>
                                        </a>
                                    @endif (isset($user->fb))
                                    @if (isset($user->wa))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://web.whatsapp.com/"
                                                target="_blank" data-toggle="tooltip"
                                                title="Enviar whatsapp a {{ $user->nombre }}">
                                            <i class="fab fa-whatsapp fa-lg" style="color:green"></i>
                                        </a>
                                    @endif (isset($user->wa))
                                    @if (isset($user->te))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://web.telegram.org/#/login"
                                                target="_blank" data-toggle="tooltip"
                                                title="Enviar telegram a {{ $user->nombre }}">
                                            <i class="fab fa-telegram fa-lg" style="color:blue"></i>
                                        </a>
                                    @endif (isset($user->te))
                                    </div>
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                        <a class="m-0 p-0" href="">
                                            <button class="btn btn-sm m-0 p-0">Ver inmuebles</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @endif ($hayFotos)
                    </div>
                    <div id="blog" class="card m-0 p-0">
                        <h4 class="card-header m-0 p-0">BLOG</h4>
                        <div class="row card-body fondoBlog m-0 p-0"
                            style="background-image:url({{ asset('img/fondoBlogBR.jpg') }})">
                        </div>
                    </div>
                    <div id="contactanos" class="card m-0 p-0">
                        <h4 class="card-header m-0 p-0">CONTACTO</h4>
                        <div class="row card-body fondoContacto m-0 p-0"
                            style="background-image:url({{ asset('img/fondoContacto.jpg') }})">
                        </div>
                    </div>
                    <div id="ubicacion" class="row m-0 p-0" style="overflow:hidden;{{--width:700px;--}}position:relative;">
                        <div class="col-lg-6 justify-content-center m-0 p-0">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3927.241471782326!2d-64.69448888583257!3d10.161017073030552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c2d734571e53301%3A0x83a8afacb4d93044!2sCentro%20Comercial%20Costanera%20Plaza%20I!5e0!3m2!1ses!2sve!4v1589509717551!5m2!1ses!2sve"
                                    class="d-block mx-auto" width="600" height="400" frameborder="0" style="border:0;"
                                    allowfullscreen="" aria-hidden="false" tabindex="0">
                            </iframe>
                            {{--<iframe width="600" height="400"
                                    src="https://maps.google.com/maps?hl=es&amp;q=Centro Comercial Costanera Plaza I, Barcelona, Venezuela+(Century 21 Puente Real Bienes Raices)&amp;ie=UTF8&amp;t=&amp;z=15&amp;iwloc=B&amp;output=embed"
                                    frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                            </iframe>
                            <div style="position:absolute;width:80%;bottom:10px;left:0;right:0;margin-left:auto;margin-right:auto;color:#000;text-align:center;">
                                <small style="line-height:1.8;font-size:0px;background:#fff;">
                                    <a href="https://googlemapsembed.net/" rel="nofollow">
                                        Google Maps Embed
                                    </a>
                                </small>
                            </div>
                            <style>
                                .nvs{
                                    position:relative;
                                    text-align:right;
                                    height:325px;
                                    width:643px;
                                }
                                #gmap_canvas img{
                                    max-width:none!important;
                                    background:none!important
                                }
                            </style>--}}
                        </div>
                        <div class="col-lg-6 justify-content-center m-0 p-0">
                            <img src="{{ (asset('img/ccCostaneraPlazaI.jpg')) }}"
                                    class="d-block mx-auto" alt="Centro Comercial Costanera Plaza I"
                                    {{--class="m-0 p-1"--}} style="height:400px;">
                        </div>
                    </div>
                </div>
@endsection

@if (!isset($accion) or ('html' == $accion))
@section('js')

@includeIf('jqwelcome')

@endsection('js')
@endif (!isset($accion) or ('html' == $accion))
