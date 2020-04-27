<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Century 21 Puente Real">
    <meta name="author" content="Pablo Caraballo">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

@if (!isset($accion) or ('html' == $accion){{-- or ('reportes-chart' == $view_name)--}})
    <!-- Bootstrap core CSS -->
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"><!-- bootstrap {{ $view_name }} -->
  @if ((strpos($view_name, 'calendario') === false) and (strpos($view_name, 'editar') === false))
    <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.css" integrity="sha256-CNwnGWPO03a1kOlAsGaH5g8P3dFaqFqqGFV/1nkX5OU=" crossorigin="anonymous" /-->
    <link rel="stylesheet" href="{{ asset('open-iconic-master/font/css/open-iconic-bootstrap.css') }}">
  @endif (!strpos($view_name, 'calendario'))
    <link href="{{ asset('css/all.css') }}" rel="stylesheet"><!-- fontawesome {{ $view_name }} -->
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet"><!-- permite comenzar debajo de la barra 'header' del menu -->
    <link href="{{ asset('css/c21pr.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script><!-- jQuery -->
    <script src="{{ asset('js/bootbox.js') }}"></script><!-- bootbox -->
    <script src="{{ asset('js/bootbox.locales.js') }}"></script><!-- bootbox -->

  @yield('jshead')
@else (!isset($accion) or ('html' == $accion))
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Chart.min.css') }}" rel="stylesheet">
    <!--link href="{{ asset('css/style.css') }}" rel="stylesheet">  Probando
    <link href="{{ asset('css/c21pr.css') }}" rel="stylesheet">Probando
    <link href="{{ asset('css/Chart.min.css') }}" rel="stylesheet"-->
    <!--link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('open-iconic-master/font/css/open-iconic-bootstrap.css') }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/c21pr.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script-->
@endif (!isset($accion) or ('html' == $accion))

  </head>

  <body>

@if (!isset($accion) or ('html' == $accion))
    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="http://www.century21.com.ve/@puenterealbienesraices" target="_blank">
          <img src="{{ (asset('img/c21pr.jpg')) }}" title="C21 Puente Real"
                alt="C21 Puente Real" style="width:32px;height:31px;">
        </a>
        <!--button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarCollapse" aria-controls="navbarCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button-->
        <div class="collapse navbar-collapse" id="navbarCollapse">
        @auth
          <ul class="navbar-nav mr-auto col-md-10">
          @foreach (array('home' => 'Home', 'contactos' => 'Contactos', 'users' => 'Asesores',
                    'turnos' => 'Turnos', 'agenda' => 'Agenda', 'propiedades' => 'Propiedades',
                    'clientes' => 'Clientes')
                    as $hMenu => $muestraMenu)
            @if ($hMenu == substr($view_name, 0, 
                ((strpos($view_name, '-'))?(strpos($view_name, '-')):4)))
            <li class="nav-item active">
            @else
            <li class="nav-item">
            @endif
              <a class="nav-link" 
            @if ('users' == $hMenu)
              href="/usuarios"
            @elseif ('turnos' == $hMenu)
              href="/{{ $hMenu }}/calendario"
            @else
              href="/{{ $hMenu }}"
            @endif
              >
              {{ $muestraMenu }} <!-- span class="sr-only">(current)</span -->
              </a>
            </li>
          @endforeach
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Estadisticas
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
              @if (Auth::user()->is_admin)
              @foreach (array('Asesor' => 'Contactos X asesor', 'Conexion' => 'Conexion X asesor')
                    as $vMEst => $muestraMEst)
                <li><a class="dropdown-item" href="{{ route('reportes', $vMEst) }}">
                  {{ $muestraMEst }}
                </a></li>
              @endforeach
              @endif
                <li><a class="dropdown-item" href="{{ route('reportes', 'Fecha') }}">
                  Contactos X fecha
                </a></li>
              @if (Auth::user()->is_admin)
              @foreach (array('Origen' => 'Contactos X origen', 'Lados' => 'Lados X asesor',
                              'Comision' => 'Comision X Asesor', 'Negociaciones' => 'Negociaciones X mes',
                              'LadMes' => 'Lados X mes', 'ComMes' => 'Comision X mes',
                              'NoConTurno' => 'No se conecto en su turno',
                              'TardeTurno' => 'Llego tarde en su turno')
                    as $vMEst => $muestraMEst)
                <li><a class="dropdown-item" href="{{ route('reportes', $vMEst) }}">
                  {{ $muestraMEst }}
                </a></li>
              @endforeach
              @endif
              </ul>
            </li>
            @if (Auth::user()->is_admin)
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Administraci&oacute;n
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#{{-- route('reportes', 'Cumpleanos') --}}">
                  Conciliaci&oacute;n
                </a></li>
                <!--li><a class="dropdown-item" href="/clientes"-->
                <li><a class="dropdown-item" href="{{ route('avisos') }}">
                  Avisos
                </a></li>
              </ul>
            </li>
            @endif (Auth::user()->is_admin)
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Reportes
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('reportes', 'Cumpleanos') }}">
                  Cumpleaños
                </a></li>
              </ul>
            </li>
            @if (Auth::user()->is_admin)
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Tablas
              <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('propiedades.grabar') }}">
                  Grabar propiedades
                </a></li>
              @foreach (array('caracteristica' => 'Caracteristica', 'deseo' => 'Deseo',
                              'feriado' => 'Dias feriados', 'formaPago' => 'Forma de pago',
                              'origen' => 'Origen', 'price' => 'Precio',
                              'resultado' => 'Resultado', 'tipo' => 'Tipo de propiedad',
                              'ciudad' => 'Ciudad', 'municipio' => 'Municipio',
                              'estado' => 'Estado', 'zona' => 'Zona', 'texto' => 'Texto',
                             )
                    as $vMTab => $muestraMTab)
                <li><a class="dropdown-item" href="{{ route($vMTab) }}">
                  {{ $muestraMTab }}
                </a></li>
              @endforeach
              </ul>
            </li>
            @endif
          </ul>
          @endauth
          <ul class="navbar-nav ml-auto col-md-2">
          @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
          @else
            <li class="nav-item dropdown">
              <button type="button" class="btn btn-info dropdown-toggle"
                              data-toggle="dropdown">
              @if (1 < Auth::user()->id)
                {{ Auth::user()->name }}
              @else
                Administrador
              @endif
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item active" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                  @csrf
                </form>
              </div>
            </li>
          @endguest
          </ul>
        </div>
      </nav>
    </header>
@endif (!isset($accion) or ('html' == $accion))

    <!-- Begin page content -->
    <main role="main" class="container"><!-- rem depende del elemento raiz de la pagina, o sea del tamaño fuente del elemento <html> -->
      <div class="row mt-1 ml-0 pl-0 no-gutters">  <!-- margen de arriba (top margin) es 0.25rem -->
        <div class="col-lg-12 ml-0 pl-0 no-gutters"> <!-- Cambie de sm a lg el 15/09/2019 -->
        @auth
          @yield('content')
        @else
          @if ('auth' == substr($view_name, 0, 4))
            @yield('content')
          @endif
        @endauth
        </div>
      </div>
    </main>

@if (!isset($accion) or ('html' == $accion))
    <footer class="footer">
      <div class="container">
        <span class="text-white bg-dark"><!-- text-muted: texto color gris -->
          Av. Costanera, Centro Comercial Costanera Plaza I, Piso 1, Local P1-02, Nueva Barcelona, frente al CC Camino Real
          <i class="fa fa-phone-alt"></i>0281-416.0885
          <a hre="mailto:c21puentereal@gmail.com" class="btn btn-link m-0 p-0 enlaceFooter"
              data-toggle="tooltip" title="Enviar correo a Puente Real">
            <i class="fa fa-at"></i></a>.
          &copy; Copyright 2019-{{ date('Y') }}
        </span>
      </div>
    </footer>
@endif (!isset($accion) or ('html' == $accion))
    @yield('js')
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script-->
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script-->
    <!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script-->
    <!--script src="{{ asset('js/app.js') }}"></script-->

    <script>
      $(function () {
          $("a.enlaceFooter").tooltip('enable')
      });
    </script>

  </body>
</html>
