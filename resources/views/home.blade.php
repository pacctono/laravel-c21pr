@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4 col-sm-12">
      <h5>{{ (1 < Auth::user()->id)?Auth::user()->name:'Administrador' }}</h5>
      <!--div class="ximagen">Fake Image</div-->
      <div
      @if ($foto)
          class="bg-transparent"
      @else (!file_exists($foto))
          class="bg-info w-100" style="height:285px;"
      @endif (!file_exists($foto))
      >
        <img src="{{ asset(Auth::user()::DIR_PUBIMG . $foto) }}"
            alt="Foto de {{ (1 < Auth::user()->id)?Auth::user()->name:'Administrador' }}">
      </div>
      <div class="m-0 p-0 w-100">
        <div class="row bg-transparent justify-content-center mt-1 mb-0 mx-0 p-0">
          <!--p><span class="oi oi-phone m-0 p-0"></span> 0424-3002814</p-->
          <p class="m-0 p-0"><i class="fa fa-mobile-alt m-0 p-0"></i> {{ Auth::user()->telefono_f }}</p>
        </div>
        <div class="row bg-transparent justify-content-center mt-1 mb-0 mx-0 p-0">
          <p class="m-0 p-0"><i class="fa fa-at m-0 p-0"></i> {{ Auth::user()->email }}</p>
        </div>
      </div>
      <hr class="d-sm-none"><!-- Solo muestra la raya en sm -->
    </div>
    <div class="col-lg-8 col-sm-12">
      <!--h2>TITLE HEADING</h2-->
      <h5>Propiedades: {{ count($misPropiedades) }}</h5>
    @if (Auth::user()->is_admin)
      <div id="miCarousel" class="carousel slide" data-ride="carousel" data-interval="1000">
        <ol class="carousel-indicators"><!-- Indicadors '_____' debajo de la imagen -->
        @for ($i = 0; $i < count($misPropiedades); $i++)
          <li data-target="#miCarousel" data-slide-to="{{ $i }}"@if (0==$i) class="active"@endif></li>
        @endfor ($i = 0; $i < 5; $i++)
        </ol>
        <div class="carousel-inner">
        @foreach ($misPropiedades as $propiedad)
          <div class="carousel-item @if(0==$loop->index)active @endif">
            <img class="img-fluid imagenPropiedad" src="{{ asset('storage/imgprop/'.$propiedad['nombreImagen']) }}"
                data-toggle="tooltip" data-html="true"
                title="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }} <u>{{ $asesor[$propiedad['asesor_id']] }}</u>"
                alt="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}" style="height:300px;">
            <div class="container">
              <div class="carousel-caption text-right">
                <p><a class="btn btn-sm btn-outline-info"
                    href="/propiedades/{{ $propiedad['id'] }}" role="button">
                  Ver Propiedad
                </a></p>
              </div><!-- carousel-caption -->
            </div>
          </div><!-- Fin de la slide 'carousel-item' -->
        @endforeach ($misPropiedades as $propiedad)
        </div><!-- Fin de las slides'carousel-inner' -->
<!-- Inicio de los controles del carousel <slide anterior> y <slide posterior> con etiuetas <a> -->
        <a class="carousel-control-prev" href="#miCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#miCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Próximo</span>
        </a>
      </div><!-- Fin del carousel -->

    @else (Auth::user()->is_admin)
      <div class="row ximagen bg-transparent m-0 p-0">
        @foreach ($misPropiedades as $propiedad)
          <a class="btn btn-link m-0 p-0" href="/propiedades/{{ $propiedad['id'] }}">
            <img class="img-fluid m-0 p-0 imagenPropiedad"
                src="{{ asset('storage/imgprop/'.$propiedad['nombreImagen']) }}"
                alt="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}" data-toggle="tooltip"
                title="{{ $propiedad['codigo'] }}-{{ $propiedad['nombre'] }}"
              @if ($agent->isMobile())
                style="height:75px;">
              @else ($agent->isMobile())
                style="height:150px;">
              @endif ($agent->isMobile())
          </a>
        @endforeach ($propiedades as $propiedad)
      </div>
    @endif (Auth::user()->is_admin)
      <hr class="d-sm-none"><!-- Solo muestra la raya en sm -->
    </div>
    {{--<div class="col-lg-1 col-sm-12">
      <h5>Redes</h5>
      <div class="xredes rounded">
      <div class="position-fixed rounded"
              style="top:25%;min-height:200px;width:60px;right:10px;z-index:100;background-color:#cccccc;">
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://www.instagram.com/c21puentereal/?hl=es-la">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/instagram.png') }}"
                  alt="Instagram" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://twitter.com/c21puentereal">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/twitter.png') }}"
                  alt="Twiter" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://es-la.facebook.com/c21puentereal/">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/facebook.png') }}"
                  alt="Facebook" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://web.whatsapp.com/">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/whatsapp.png') }}"
                  alt="Whatsapp" data-toggle="tooltip" title="Web de Whatsapp" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://web.telegram.org/#/login">
            <img class="rounded-circle mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/telegram.png') }}"
                alt="Telegram" data-toggle="tooltip" title="Web de Telegram" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Chat.png') }}"
                alt="Telegram" data-toggle="tooltip" title="Chat (no aplicado aun)" style="width:50px;height:50px;">
          </a>
        </div>
      </div>
      <hr class="d-sm-none"><!-- Solo muestra la raya en sm. Se esconde en pantallas superiores a sm -->
    </div>--}}
  </div>
  <div class="row col-lg-12 col-sm-12 my-1 justify-content-center bg-transparent" style="min-height:75px;">
    <a class="btn btn-link m-0 p-0" href="/contactos/crear">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearContacto.png') }}"
            alt="Crear Contacto" data-toggle="tooltip" title="Crear Contacto inicial" style="width:75px;height:75px;">
    </a>
    @if (Auth::user()->is_admin)
    <a class="btn btn-link m-0 p-0" href="/usuarios/nuevo">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearAsesor.png') }}"
            alt="Crear Asesor" data-toggle="tooltip" title="Crear Asesor" style="width:75px;height:75px;">
    </a>
    @endif (Auth::user()->is_admin)
    <a class="btn btn-link m-0 p-0" href="/agendaPersonal/crear">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearCita.png') }}"
            alt="Crear cita" data-toggle="tooltip" title="Crear Cita Personal" style="width:75px;height:75px;">
    </a>
    <a class="btn btn-link m-0 p-0" href="/clientes/crear">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearCliente.png') }}"
            alt="Crear cliente" data-toggle="tooltip" title="Crear Cliente" style="width:75px;height:75px;">
    </a>
    <a class="btn btn-link m-0 p-0" href="/propiedades/crear">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearPropiedad.png') }}"
            alt="crear Propiedad" data-toggle="tooltip" title="Crear Propiedad" style="width:75px;height:75px;">
    </a>
  </div>
</div>
@endsection

@section('js')

<script>
  $(function () {
      $("img.imagenPropiedad").tooltip('enable')
      $("img.enlacesExternos").tooltip('enable')
      $("img.botones").tooltip('enable')
  });
  @includeIf('include.alertar')

  $(document).ready(function() {
  @if (0 < strlen($cumpleanos))
    alertar(`{!! $cumpleanos !!}`, 'Pr&oacute;ximos cumpea&ntilde;os');
  @endif (0 < strlen($cumpleanos))
  @if ($hoyCumpleanos)
    bootbox.dialog({
      message: `<img class="bg-transparent rounded" src="{{ asset('img/felizCumpleanos.jpg') }}"
              alt="Feliz cumpleaños" style="width:765;height:510px;">`,
      size: 'large',
      onEscape: true,
      backdrop: true,
      buttons: false,
    });
  @elseif ('' != $alertar)
    alertar(`<img class="bg-transparent rounded" src="{{ asset('img/bienvenido.jpg') }}"
              alt="Bienvenido" style="width:765;height:510px;">`, `{!! $alertar !!}`);
  @endif ($hoyCumpleanos)
  })
</script>

@endsection