@extends('layouts.app')

@section('content')
@if ('' != $alertar)
    <script>alert("{{ $alertar }}");</script>
@endif
@if ((Auth::user()->is_admin) && ($cumpleaneros->isNotEmpty()))

<div class="mb-0 col-lg-8">
  <div class="row">
    <div class="col-lg-8 justify-content-center bg-suave">
      Pr&oacute;ximos cumplea&ntilde;os
    </div>
  </div>
  @foreach ($cumpleaneros as $cumpleano)
  <div class="row">
    <div class="col-lg-4">
      {{ $cumpleano->name }}
    </div>
    <div class="col-lg-4">
      {{ $cumpleano->fecha_cumpleanos->format('d-m') }}
      @if ($hoy == $cumpleano->fecha_cumpleanos->format('d-m'))
      <a href="{{ route('agenda.cumpleano', $cumpleano) }}" class="btn btn-link"
              title="Enviar correo a '{{ $cumpleano->name }}', porque esta de cumpleaños!">
          <span class="oi oi-envelope-closed"></span>
      </a>
      @endif
    </div>
  </div>
  @endForeach
</div>

@else
<div class="container">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
          <li data-target="#myCarousel" data-slide-to="3"></li>
          <li data-target="#myCarousel" data-slide-to="4"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="first-slide" src="{{ (asset('img/imagen0.jpg')) }}"
                    alt="Primera slide" style="width:100%;height:600px;">
            <div class="container">
              <div class="carousel-caption text-left">
                <h1 class="col-lg-6" style="background-color:lightgrey">Hola,
                @if (1 < Auth::user()->id)
                  {{ Auth::user()->name }}
                @else
                  Administrador
                @endif (1 < Auth::user()->id)
                </h1>
                <p style="background-color:lightgrey">
                  {{ $texto1->descripcion??'' }}
                </p>
                <p><a class="btn btn-lg btn-primary" href="{{ $texto1->enlace??'' }}" role="button">
                  {{ $texto1->textoEnlace??'' }}
                </a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="second-slide" src="{{ (asset('img/imagen1.jpg')) }}"
                    alt="Segunda slide" style="width:100%;height:600px;">
            <div class="container">
              <div class="carousel-caption">
                <h1 style="background-color:lightgrey">
                  {{ $texto2->descripcion??'' }}
                </h1>
                <p><a class="btn btn-lg btn-primary" href="{{ $texto2->enlace??'' }}" role="button">
                  {{ $texto2->textoEnlace??'' }}
                </a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="third-slide" src="{{ (asset('img/imagen2.jpg')) }}"
                    alt="Tercera slide" style="width:100%;height:600px;">
            <div class="container">
              <div class="carousel-caption text-right">
                <h1 style="background-color:lightgrey">
                  {{ $texto3->descripcion??'' }}
                </h1>
                <p><a class="btn btn-lg btn-primary" href="{{ $texto3->enlace??'' }}" role="button">
                  {{ $texto3->textoEnlace??'' }}
                </a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="fourth-slide" src="{{ (asset('img/imagen3.jpg')) }}"
                    alt="Cuarta slide" style="width:100%;height:600px;">
            <div class="container">
              <div class="carousel-caption text-right">
                <h1 style="background-color:lightgrey">
                  {{ $texto4->descripcion??'' }}
                </h1>
                <p><a class="btn btn-lg btn-primary" href="{{ $texto4->enlace??'' }}" role="button">
                  {{ $texto4->textoEnlace??'' }}
                </a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="fith-slide" src="{{ (asset('img/imagen4.jpg')) }}"
                    alt="Quinta slide" style="width:100%;height:600px;">
            <div class="container">
              <div class="carousel-caption text-right">
                <h1 style="background-color:lightgrey">
                  {{ $texto5->descripcion??'' }}
                </h1>
                <p><a class="btn btn-lg btn-primary" href="{{ $texto5->enlace??'' }}" role="button">
                  {{ $texto5->textoEnlace??'' }}
                </a></p>
              </div>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previo</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Próximo</span>
        </a>
    </div>
</div>
@endif
@endsection
