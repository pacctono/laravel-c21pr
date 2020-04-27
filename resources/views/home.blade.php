@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-4">
      <h5>{{ (1 < Auth::user()->id)?Auth::user()->name:'Administrador' }}</h5>
      <!--div class="ximagen">Fake Image</div-->
      <div
      @if (!file_exists($foto))
          class="bg-info" style="width:100%;height:285px;"
      @else (!file_exists($foto))
          class="bg-transparent"
      @endif (!file_exists($foto))
      >
        <img src="{{ asset($foto) }}" alt="Foto">
      </div>
      <div class="m-0 p-0" style="width:340;">
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
    <div class="col-lg-7">
      <!--h2>TITLE HEADING</h2-->
      <h5>Propiedades</h5>
    @if (Auth::user()->is_admin)
      <div id="miCarousel" class="carousel slide" data-ride="carousel" data-interval="1000">
        <ol class="carousel-indicators"><!-- Indicadors '_____' debajo de la imagen -->
          <li data-target="#miCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#miCarousel" data-slide-to="1"></li>
          <li data-target="#miCarousel" data-slide-to="2"></li>
          <li data-target="#miCarousel" data-slide-to="3"></li>
          <li data-target="#miCarousel" data-slide-to="4"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="first-slide img-fluid"
                  src="img/imagen0.jpg"
                    alt="Primera propiedad" style="height:300px;">
            <!--div class="container"-->
              <div class="carousel-caption text-left">
                <!--h1 class="col-lg-6" style="background-color:lightgrey">Hola, Alirio
                </h1-->
<?php
  $tamImagen = getimagesize('img/imagen0.jpg');
  $razon = round($tamImagen[0]/$tamImagen[1], 3); 
  $nvoAncho = round($razon * 300, 0);
?>
		<div class="bg-info text-dark" style="width:<?php echo round($nvoAncho/2, 0); ?>;">
                <p>
<?php
  echo "ancho de la Imagen: {$tamImagen[0]}, alto: {$tamImagen[1]}, razon: {$razon}, para alto 300, ancho: {$nvoAncho}\n";
?>
                </p>
                </div>
                <p><a class="btn btn-lg btn-primary" href="" role="button">
                  A1
                </a></p>
              </div><!-- carousel-caption -->
            <!--/div-->
          </div><!-- Fin de la primera slide 'carousel-item' -->
          <div class="carousel-item">
            <img class="second-slide img-fluid" src="img/imagen1.jpg"
                    alt="Segunda propiedad" style="height:300px;">
            <div class="container">
              <div class="carousel-caption">
                <h6 style="background-color:lightgrey">
<?php
  $tamImagen = getimagesize('img/imagen1.jpg');
  $razon = round($tamImagen[0]/$tamImagen[1], 3); 
  $nvoAncho = round($razon * 300, 0);
  echo "ancho de la Imagen: {$tamImagen[0]}, alto: {$tamImagen[1]}, razon: {$razon}, para alto 300, ancho: {$nvoAncho}\n";
?>
                </h6>
                <p><a class="btn btn-lg btn-primary" href="" role="button">
                  A2
                </a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="third-slide img-fluid" src="img/imagen2.jpg"
                    alt="Tercera propiedad" style="height:300px;">
            <div class="container">
              <div class="carousel-caption text-right">
                <h6 style="background-color:lightgrey">
<?php
  $tamImagen = getimagesize('img/imagen2.jpg');
  $razon = round($tamImagen[0]/$tamImagen[1], 3); 
  $nvoAncho = round($razon * 300, 0);
  echo "ancho de la Imagen: {$tamImagen[0]}, alto: {$tamImagen[1]}, razon: {$razon}, para alto 300, ancho: {$nvoAncho}\n";
?>
                </h6>
                <p><a class="btn btn-lg btn-primary" href="" role="button">
                  A3
                </a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="fourth-slide img-fluid" src="img/imagen3.jpg"
                    alt="Cuarta propiedad" style="height:300px;">
            <div class="container">
              <div class="carousel-caption text-right">
                <h6 style="background-color:lightgrey">
<?php
  $tamImagen = getimagesize('img/imagen3.jpg');
  $razon = round($tamImagen[0]/$tamImagen[1], 3); 
  $nvoAncho = round($razon * 300, 0);
  echo "ancho de la Imagen: {$tamImagen[0]}, alto: {$tamImagen[1]}, razon: {$razon}, para alto 300, ancho: {$nvoAncho}\n";
?>
                </h6>
                <p><a class="btn btn-lg btn-primary" href="" role="button">
                  A4
                </a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="fith-slide img-fluid" src="img/imagen4.jpg"
                    alt="Quinta propiedad" style="height:300px;">
            <div class="container">
              <div class="carousel-caption text-right">
                <h6 style="background-color:lightgrey">
<?php
  $tamImagen = getimagesize('img/imagen4.jpg');
  $razon = round($tamImagen[0]/$tamImagen[1], 3); 
  $nvoAncho = round($razon * 300, 0);
  echo "ancho de la Imagen: {$tamImagen[0]}, alto: {$tamImagen[1]}, razon: {$razon}, para alto 300, ancho: {$nvoAncho}\n";
?>
                </h6>
                <p><a class="btn btn-lg btn-primary" href="" role="button">
                  A5
                </a></p>
              </div>
            </div>
          </div>
        </div>
<!-- Inicio de los controles del carousel <slide anterior> y <slide posterior> con etiuetas <a> -->
        <a class="carousel-control-prev" href="#miCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previo</span>
        </a>
        <a class="carousel-control-next" href="#miCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Próximo</span>
        </a>
      </div><!-- Fin del carousel -->

    @else (Auth::user()->is_admin)
      <div class="row ximagen bg-transparent m-0 p-0">
          <img class="img-responsive m-0 p-0" src="{{ asset('img/imagen0.jpg') }}" alt="Propiedad" style="height:150px;">
          <img class="img-responsive m-0 p-0" src="{{ asset('img/imagen1.jpg') }}" alt="Propiedad" style="height:150px;">
          <img class="img-responsive m-0 p-0" src="{{ asset('img/imagen2.jpg') }}" alt="Propiedad" style="height:150px;">
          <img class="img-responsive m-0 p-0" src="{{ asset('img/imagen3.jpg') }}" alt="Propiedad" style="height:150px;">
          <img class="img-responsive m-0 p-0" src="{{ asset('img/imagen4.jpg') }}" alt="Propiedad" style="height:150px;">
          <img class="img-responsive m-0 p-0" src="{{ asset('img/inauguracion1.jpg') }}" alt="Propiedad" style="height:150px;">
      </div>
    @endif (Auth::user()->is_admin)
    </div>
    <div class="col-lg-1">
      <h5>Redes</h5>
      <div class="xredes rounded">
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://www.instagram.com/c21puentereal/?hl=es-la">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Instagram.png') }}"
                  alt="Instagram" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <!--div class="row">
          <img class="rounded-circle mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Instagram.png') }}" alt="Instagram" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
        </div-->
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://twitter.com/c21puentereal">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Twiter.png') }}"
                  alt="Twiter" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <!--div class="row text-center">
          <img class="rounded-circle mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Twiter.png') }}" alt="Twiter" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
        </div-->
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://es-la.facebook.com/c21puentereal/">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Facebook.png') }}"
                  alt="Facebook" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
          </a>
        </div>
        <!--div class="row">
          <img class="rounded-circle mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Facebook.png') }}" alt="Facebook" data-toggle="tooltip" title="@c21puentereal" style="width:50px;height:50px;">
        </div-->
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://web.whatsapp.com/">
            <img class="rounded mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Whatsapp.png') }}"
                  alt="Whatsapp" data-toggle="tooltip" title="Web de Whatsapp" style="width:50px;height:50px;">
          </a>
        </div>
        <div class="row justify-content-center">
          <a class="btn btn-link m-0 p-0" href="https://web.telegram.org/#/login">
            <img class="rounded-circle mx-auto d-block my-1 enlacesExternos" src="{{ asset('iconos/Telegram.png') }}"
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
    </div>
  </div>
  <div class="row col-lg-11 my-1 justify-content-center bg-transparent" style="height:75px;">
    <!--img class="border border-dark rounded mx-1" src="{{ asset('botones/crearContacto.png') }}" alt="Crear Contacto" style="border:solid 1px #000000;width:75px;height:75px;"-->
    <a class="btn btn-link m-0 p-0" href="/contactos/crear">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearContacto.png') }}"
            alt="Crear Contacto" data-toggle="tooltip" title="Crear Contacto inicial" style="width:75px;height:75px;">
    </a>
    @if (Auth::user()->is_admin)
    <a class="btn btn-link m-0 p-0" href="/usuarios/nuevo">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearCliente.png') }}"
            alt="Crear Asesor" data-toggle="tooltip" title="Crear Asesor" style="width:75px;height:75px;">
    </a>
    @endif (Auth::user()->is_admin)
    <!--img class="border border-dark rounded mx-1" src="{{ asset('botones/crearCita.png') }}" alt="Crear cita" style="border:solid 1px #000000;width:75px;height:75px;"-->
    <a class="btn btn-link m-0 p-0" href="/agendaPersonal/crear">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearCita.png') }}"
            alt="Crear cita" data-toggle="tooltip" title="Crear Cita Personal" style="width:75px;height:75px;">
    </a>
    <a class="btn btn-link m-0 p-0" href="/clientes/crear">
      <img class="border border-dark rounded-circle mx-1 botones" src="{{ asset('botones/crearCliente.png') }}"
            alt="Crear cliente" data-toggle="tooltip" title="Crear Cliente" style="width:75px;height:75px;">
    </a>
    <!--img class="border border-dark rounded mx-1" src="{{ asset('botones/crearPropiedad.png') }}" alt="crear Propiedad" style="border:solid 1px #000000;width:75px;height:75px;"-->
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