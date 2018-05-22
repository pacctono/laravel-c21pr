@extends('layouts.app')

@section('content')
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
            <img class="first-slide" src="{{ (asset('img/fachadaConAsesores_201804.jpg')) }}"
                    alt="Primera slide" style="width:100%;height:600px;">
            <div class="container">
              <div class="carousel-caption text-left">
                <h1>Hola, {{ Auth::user()->name }}!</h1>
                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Registrese hoy</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="second-slide" src="{{ (asset('img/asesores.jpg')) }}"
                    alt="Segunda slide" style="width:100%;height:600px;">
            <div class="container">
              <div class="carousel-caption">
                <h1>Ejemplo 2</h1>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Aprende</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="third-slide" src="{{ (asset('img/inauguracion1.jpg')) }}"
                    alt="Tercera slide" style="width:100%;height:600px;">
            <div class="container">
              <div class="carousel-caption text-right">
                <h1>Ejemplo 3</h1>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Explora</a></p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="fourth-slide" src="{{ (asset('img/asesores_1roMayo-0.jpg')) }}"
                    alt="Cuarta slide" style="width:100%;height:600px;">
            <!-- div class="container">
              <div class="carousel-caption text-right">
                <h1>Ejemplo 3</h1>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Explora</a></p>
              </div>
            </div -->
          </div>
          <div class="carousel-item">
            <img class="fith-slide" src="{{ (asset('img/asesores_1roMayo-1.jpg')) }}"
                    alt="Quinta slide" style="width:100%;height:600px;">
            <!-- div class="container">
              <div class="carousel-caption text-right">
                <h1>Ejemplo 3</h1>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Explora</a></p>
              </div>
            </div -->
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
@endsection
