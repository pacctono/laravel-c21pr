@extends('layouts.inicio')

@section('content')
                <div class="container-fluid">
                    <div id="inicio" class="divInicio rounded m-0 pt-5 pb-0 px"
                            style="background-image:url({{ asset('img/fondoInicio.jpg') }})">
                        <!--img src="{{ (asset('img/fondoInicio.jpg')) }}"
                                alt="Conectado a la web de Century21 Puente Real"
                                class="w-100"-->
                        <div class="textoInicio">
                            <h1 class="mt-0 mb-2 d-block mx-auto p-0">¡BIENVENIDO!</h1>
                            <h2 class="my-1 d-block mx-auto p-0">¿QUÉ ESTÁS BUSCANDO?</h2>
                            <div class="row my-1 d-block mx-auto p-0">
                                <button id="ciudadC" class="ml-0 mr-1 my-0 p-1">CIUDAD DONDE DESEA EL INMUEBLE
                                    <!--span id="ciudadCspan" class="m-0 p-0" style="font-size:0.5rem"></span-->
                                </button>
                                <button id="negociacionC" class="mx-1 my-0 p-1">COMPRA O ALQUILER
                                    <!--span id="negociacionCspan" class="m-0 p-0" style="font-size:0.5rem"></span-->
                                </button>
                                <button id="tipoC" class="mx-1 my-0 p-1">CASA, APARTAMENTO, TERRENO
                                    <!--span id="tipoCspan" class="m-0 p-0" style="font-size:0.5rem"></span-->
                                </button>
                                <button id="comprar" class="bg-dark text-light ml-2 mr-0 my-0 p-2">BUSCAR</button>
                            </div>
                            <h2 class="mt-2 mb-0 d-block mx-auto p-1">DESEO VENDER O DAR EN ALQUILER MI PROPIEDAD</h2>
                            <div class="row my-1 d-block mx-auto p-0">
                                <button id="ciudadV" class="ml-0 mr-1 my-0 p-1">¿CIUDAD?
                                    <!--span id="ciudadVspan" style="font-size:0.5rem"></span-->
                                </button>
                                <button id="nombre" class="mx-1 my-0 p-1">NOMBRE Y APELLIDO
                                    <!--span id="nombrespan" class="m-0 p-0" style="font-size:0.5rem"></span-->
                                </button>
                                <button id="negociacionV" class="mx-1 my-0 p-1">VENDER O<small> dar en </small>ALQUILER
                                    <!--span id="negociacionVspan" class="m-0 p-0" style="font-size:0.5rem"></span-->
                                </button>
                                <button id="tipoV" class="mx-1 my-0 p-1">¿QUÉ DESEA VENDER O DAR EN ALQUILER?
                                    <!--span id="tipoVspan" class="m-0 p-0" style="font-size:0.5rem"></span-->
                                </button>
                                <button id="telefono" class="mx-1 my-0 p-1">NÚMERO DE CONTACTO
                                    <!--span id="telefonospan" class="m-0 p-0" style="font-size:0.5rem"></span-->
                                </button>
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
                    <div id="propiedades" class="m-0 p-0">
                        <h4 class="m-0 pt-3 pb-0 px-0 colorFondo1">PROPIEDADES</h4>
                        <div class="row justify-content-center fondoPropiedades rounded m-0 pt-3 pb-0 px-0"
                            style="background-image:url({{ asset('img/fondoPropiedadesLR.jpg') }})">
                        @foreach ($propiedades as $propiedad)
                            <div class="col-lg-3 mx-0 my-1 pl-0 pr-1 py-0">
                                <img class="img-fluid rounded m-0 p-0 imagenPropiedad"
                                        src="{{ asset('storage/imgprop/'.$propiedad['nombreImagen']) }}"
                                        alt="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}" data-toggle="tooltip"
                                        title="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}">
                                <div class="m-0 p-0 {{--border border-dark--}}">
                                    <div class="row bg-transparent justify-content-center m-0 p-0">
                                        <p class="rounded colorFondo1 m-0 p-0">
                                            {{ $propiedad['nombre'] }}
                                        </p>
                                    </div>
                                    <div class="row bg-transparent justify-content-center m-0 p-0">
                                        <a class="m-0 p-0" href="">
                                            <button class="btn btn-sm m-0 p-0 verInmueble"
                                                    idprop="{{ $propiedad['id'] }}">
                                                Ver inmueble
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach ($propiedades as $propiedad)
                        </div>
                    </div>
                    <div id="asesores" class="m-0 p-0">
                    @if ($hayFotos)
                        <h4 class="m-0 pt-3 pb-0 px-0 colorFondo1">ASESORES</h4>
                        <div class="row justify-content-center fondoAsesores rounded m-0 pt-3 pb-0 px-0"
                            style="background-image:url({{ asset('img/fondoAsesoresKR.jpg') }})">
                        @foreach ($users as $user)
                            <div class="col-lg-3 mx-0 my-1 pl-0 pr-1 py-0">
                            @if ($user->foto)
                                <img class="d-block mx-auto @if(7>$loop->iteration) rounded-circle @else rounded @endif img-fluid my-0 p-0 fotoAsesor"
                                        alt="{{ $user->nombre }}" title="{{ $user->nombre }}"
                                        src="{{ asset($user::DIR_PUBIMG . $user->foto) }}">
                            @else ($foto)
                                <div class="w-100 h-75 rounded m-0 p-1 colorFondo1">
                                    Foto de {{ $user->nombre }}
                                </div>
                            @endif ($foto)
                                <div class="m-0 p-0 {{--border border-dark--}}">
                                    <div class="row rounded colorFondo1 justify-content-center m-0 p-0">
                                        <p class="m-0 p-0">
                                            {{ $user->nombre }}
                                        </p>
                                    </div>
                                    <div class="row rounded colorFondo1 justify-content-center m-0 p-0">
                                        <p class="m-0 p-0">
                                            <i class="fas fa-mobile-alt m-0 p-0"></i>
                                            {{ $user->telefono_f }}
                                        </p>
                                    </div>
                                    <div class="row rounded colorFondo1 justify-content-center m-0 p-0">
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
                                    <div class="row rounded colorFondo1 justify-content-center m-0 p-0">
                                    @if (isset($user->ig))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://www.instagram.com/{{ $user->ig }}/?hl=es-la"
                                                target="_blank" data-toggle="tooltip"
                                                title="Ver instagram de {{ $user->nombre }}">
                                            <i class="fab fa-instagram fa-lg" style="color:salmon"></i>
                                        </a>
                                    @endif (isset($user->ig))
                                    @if (isset($user->tw))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://twitter.com/{{ $user->tw }}"
                                                targe="_blank" data-toggle="tooltip"
                                                title="Ver twitter de {{ $user->nombre }}">
                                            <i class="fab fa-twitter fa-lg" style="color:blue"></i>
                                        </a>
                                    @endif (isset($user->tw))
                                    @if (isset($user->fb))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://es-la.facebook.com/{{ $user->fb }}/"
                                                target="_blank" data-toggle="tooltip"
                                                title="Ver Facebook de {{ $user->nombre }}">
                                            <i class="fab fa-facebook fa-lg" style="color:red"></i>
                                        </a>
                                    @endif (isset($user->fb))
                                    @if (isset($user->wa))
                                        <a class="btn btn-link ml-0 mr-1 my-0 p-0 mostrarTooltip"
                                                href="https://api.whatsapp.com/send?phone=58{{ $user->telefono }}"
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
                                @if (0 < count($user->propiedadesCaptadas->where('estatus', 'A')))
                                    <div class="row rounded colorFondo1 justify-content-center m-0 p-0">
                                        <a class="m-0 p-0" href="">
                                            <button class="btn btn-sm m-0 p-0 verInmuebles"
                                                    idasesor="{{ $user->id }}">
                                                Ver inmuebles
                                            </button>
                                        </a>
                                    </div>
                                @endif ($user->propiedadesCaptadas->where('estatus', 'A'))
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @endif ($hayFotos)
                    </div>
                    <div id="blog" class="m-0 p-0">
                        <h4 class="m-0 pt-3 pb-0 px-0 colorFondo1">BLOG</h4>
                        <div class="row fondoBlog rounded m-0 pt-3 pb-0 py-0"
                            style="background-image:url({{ asset('img/fondoBlogBR.jpg') }})">
                        </div>
                    </div>
                    <div id="contactanos" class="m-0 p-0">
                        <h4 class="pt-3 pb-0 px-0 m-0 colorFondo1">CONTACTO</h4>
                        <div class="row fondoContacto rounded m-0 pt-3 pb-0 px-0"
                                style="background-image:url({{ asset('img/fondoContacto.jpg') }})">
                        <form method="POST" class="form" id="formulario" action="{{ url('contactos') }}">
                            {!! csrf_field() !!}

                            <div class="form-group row m-0 p-0">
                                <div class="col-lg-2 mr-1 ml-0 my-0 p-0">
                                    <label class="control-label colorFondo1 m-0 p-0"
                                            style="font-size:0.5rem" for="cedula">
                                        C&eacute;dula
                                    </label>
                                    <input type="text" class="form-control input-lg m-0 p-0"
                                            size="8" maxlength="8" minlength="6" name="cedula"
                                            id="cedula" placeholder="# CI"
                                            value="{{ old('cedula') }}">
                                </div>
                                <div class="col-lg-6 m-0 p-0">
                                    <label class="control-label colorFondo1 m-0 p-0"
                                            style="font-size:0.5rem" for="name">
                                        Nombre
                                    </label>
                                    <input type="text" class="form-control input-lg m-0 p-0"
                                            required size="40" maxlength="100" name="name" id="name"
                                            placeholder="Nombre y Apellido" value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="form-group row m-0 p-0">
                                <div class="col-lg-3 mr-1 ml-0 my-0 p-0">
                                    <label class="control-label colorFondo1 m-0 p-0"
                                            style="font-size:0.5rem" for="telefono">Teléfono</label>
                                    <div class="form-inline m-0 p-0">
                                        <select class="form-control input-sm m-0 p-0" name="ddn" id="ddn">
                                            <option class="m-0 p-0" value="">ddn</option>
                                        </select>
                                        <input class="form-control input-lg m-0 p-0" type="text" size="7"
                                                maxlength="7" name="telefono" id="telefonoCI"
                                                placeholder="# sin area" value="{{ old('telefono') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 mr-1 ml-0 my-0 p-0">
                                    <label class="control-label colorFondo1 m-0 p-0" 
                                            style="font-size:0.5rem" for="otro_telefono">Otro teléfono
                                    </label>
                                    <input type="text" class="form-control input-lg m-0 p-0"
                                            size="14" maxlength="14" name="otro_telefono"
                                            id="otro_telefono" placeholder="Otro # de contacto"
                                            value="{{ old('otro_telefono') }}">
                                </div>
                                <div class="col-lg-3 m-0 p-0">
                                    <label class="control-label colorFondo1 m-0 p-0"
                                            style="font-size:0.5rem" for="deseo">Desea</label>
                                    <select class="form-control input-lg m-0 p-0" name="deseo_id" id="deseo">
                                        <option value="">Qué desea?</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group m-0 p-0">
                                <label class="control-label colorFondo1 m-0 p-0"
                                        style="font-size:0.5rem" for="email">
                                    Correo electr&oacute;nico
                                </label>
                            </div>
                            <div class="form-group col-lg-6 m-0 p-0">
                                <input type="email" class="form-control input-lg m-0 p-0"
                                        size="40" maxlength="100" name="email" id="email"
                                        placeholder="correo electronico" value="{{ old('email') }}">
                            </div>

                            <div class="form-group m-0 p-0">
                                <label class="control-label colorFondo1 m-0 p-0"
                                        style="font-size:0.5rem" for="direccion">
                                    Direcci&oacute;n
                                </label>
                            </div>
                            <div class="form-group col-lg-9 m-0 p-0">
                                <textarea class="form-control input-lg m-0 p-0" rows="2"
                                    cols="50" maxlength="160" name="direccion" id="direccion"
                                    placeholder="Calle, Casa, Apto, Edificio, Barrio, Ciudad">{{ old('direccion') }}</textarea>
                                <div class="colorFondo1">Quedan <span id="numCarsdireccion">160</span> caracteres</div>
                            </div>

                            <div class="form-group row m-0 p-0">
                                <div class="col-lg-3 mr-1 ml-0 my-0 p-0">
                                    <label class="control-label colorFondo1 m-0 p-0"
                                            style="font-size:0.5rem" for="tipo">Tipo de inmueble</label>
                                    <select class="form-control input-lg m-0 p-0" name="tipo_id" id="tipo">
                                        <option value="">Qué tipo de inmueble?</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 mr-1 ml-0 my-0 p-0">
                                    <label class="control-label colorFondo1 m-0 p-0"
                                            style="font-size:0.5rem" for="precio">Costo aproximado del inmueble</label>
                                    <select class="form-control input-lg m-0 p-0" name="precio_id" id="precio">
                                        <option value="">Costo aproximado?</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 m-0 p-0">
                                    <label class="control-label colorFondo1 m-0 p-0"
                                            style="font-size:0.5rem" for="zona">Zona donde se encontraría el inmueble</label>
                                    <select class="form-control input-lg m-0 p-0" name="zona_id" id="zona">
                                        <option value="">En Qué zona?</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group m-0 p-0">
                                <label class="control-label colorFondo1 m-0 p-0"
                                        style="font-size:0.5rem" for="observaciones">Observaciones</label>
                            </div>
                            <div class="form-group col-lg-9 m-0 p-0">
                                <textarea class="form-control form-control-sm" rows="2"
                                        maxlength="190" cols="95"
                                        name="observaciones" id="observaciones" 
                                        placeholder="Escriba alguna otra información que desee suministrar.">{{ old('observaciones') }}</textarea>
                                <div id=""></div>
                                <div class="colorFondo1">Quedan <span id="numCarsobservaciones">190</span> caracteres</div>
                            </div>

                            <div class="form-row my-1 mx-0 p-0">
                                <button id="contacto" type="submit" class="btn btn-success col-lg-5 m-0 py-0 px-1">
                                    Enviar
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>
                    <div id="ubicacion" class="row m-0 p-0" style="overflow:hidden;{{--width:700px;--}}position:relative;">
                        <div class="col-lg-6 justify-content-center m-0 p-0">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3927.241471782326!2d-64.69448888583257!3d10.161017073030552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c2d734571e53301%3A0x83a8afacb4d93044!2sCentro%20Comercial%20Costanera%20Plaza%20I!5e0!3m2!1ses!2sve!4v1589509717551!5m2!1ses!2sve"
                                    class="d-block mx-auto rounded" width="600" height="400" frameborder="0" style="border:0;"
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
                                    class="d-block mx-auto rounded" alt="Centro Comercial Costanera Plaza I"
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
