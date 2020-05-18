@extends('layouts.inicio')

@section('content')
                <div class="container-fluid">
                    <img src="{{ (asset('img/fondoInicio.jpg')) }}"
                            alt="Conectado a la web de Century21 Puente Real"
                            class="w-100">
                    <div id="propiedades" class="card m-0 p-0">
                        <h4 class="card-header m-0 p-0">PROPIEDADES</h4>
                        <img src="{{ (asset('img/propiedades.jpg')) }}"
                                alt="Propiedades de Century21 Puente Real"
                                class="card-body w-100 m-0 p-0">
                    </div>
                    <div id="ultPropiedades" class="row w-100 colorFondo1 m-0 p-0">
                    @foreach ($propiedades as $propiedad)
                        <div class="col-lg-3 colorFondo1">
                            <img class="img-fluid m-0 p-0 imagenPropiedad"
                                    src="{{ asset('storage/imgprop/'.$propiedad['nombreImagen']) }}"
                                    alt="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}" data-toggle="tooltip"
                                    title="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}">
                            <div class="m-0 p-0 {{--border border-dark--}}">
                                <div class="row bg-transparent justify-content-center mt-1 mb-0 mx-0 p-0">
                                <p class="m-0 p-0">
                                    {{ $propiedad['nombre'] }}
                                </p>
                                </div>
                                <div class="row bg-transparent justify-content-center mt-1 mb-0 mx-0 p-0">
                                    <a class="m-0 p-0" href="/propiedades/{{ $propiedad['id'] }}">
                                        <button class="btn btn-sm m-0 p-0">Ver inmueble</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach ($propiedades as $propiedad)
                    </div>
                    <div id="asesores" class="card m-0 p-0">
                        <h4 class="card-header m-0 p-0">ASESORES</h4>
                        <div class="row card-body w-100 colorFondo1">
                        @foreach ($users as $user)
                            <div class="col-lg-3 colorFondo1">
                            @if ($user->foto)
                                <img class="d-block mx-auto @if(5>$loop->iteration) rounded-circle @endif img-fluid my-0 p-1 colorFondo1 fotoAsesor"
                                        alt="{{ $user->name }}" title="{{ $user->name }}"
                                        src="{{ asset($user::DIR_PUBIMG . $user->foto) }}">
                            @else ($foto)
                                <div class="w-100 h-75 m-0 p-1 colorFondo1">
                                    Foto de {{ $user->name }}
                                </div>
                            @endif ($foto)
                                <div class="m-0 p-0 {{--border border-dark--}}">
                                    <div class="row bg-transparent justify-content-center mt-1 mb-0 mx-0 p-0">
                                    <p class="m-0 p-0">
                                        <i class="fa fa-mobile-alt m-0 p-0"></i>
                                        {{ $user->telefono_f }}
                                    </p>
                                    </div>
                                    <div class="row bg-transparent justify-content-center mt-0 mb-1 mx-0 p-0">
                                    <p class="m-0 p-0">
                                        <i class="fa fa-at m-0 p-0"></i>
                                        {{ $user->email }}
                                    </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    <div class="row m-0 p-0" style="overflow:hidden;{{--width:700px;--}}position:relative;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3927.241471782326!2d-64.69448888583257!3d10.161017073030552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c2d734571e53301%3A0x83a8afacb4d93044!2sCentro%20Comercial%20Costanera%20Plaza%20I!5e0!3m2!1ses!2sve!4v1589509717551!5m2!1ses!2sve"
                                width="600" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0">
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
                        <img src="{{ (asset('img/ccCostaneraPlazaI.jpg')) }}"
                                alt="Centro Comercial Costanera Plaza I"
                                {{--class="m-0 p-1"--}} style="height:400px;">
                    </div>
                </div>
@endsection