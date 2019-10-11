<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="favicon.ico">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap core CSS -->
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.css" integrity="sha256-CNwnGWPO03a1kOlAsGaH5g8P3dFaqFqqGFV/1nkX5OU=" crossorigin="anonymous" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/c21pr.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('jshead')

  </head>

  <body>

    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="http://www.century21.com.ve/@puenterealbienesraices" target="_blank">
          <img src="{{ (asset('img/c21pr.jpg')) }}" title="C21 Puente Real"
                alt="C21 Puente Real" style="width:32px;height:31px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarCollapse" aria-controls="navbarCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
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
            @if ('users' != $hMenu)
              href="/{{ $hMenu }}"
            @else
              href="/usuarios"
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
                <li><a class="dropdown-item" href="{{ route('reportes', 'Asesor') }}">
                  Contactos X asesor
                </a></li>
                <li><a class="dropdown-item" href="{{ route('reportes', 'Conexion') }}">
                  Conexión X asesor
                </a></li>
                @endif
                <li><a class="dropdown-item" href="{{ route('reportes', 'Fecha') }}">
                  Contactos X fecha
                </a></li>
                @if (Auth::user()->is_admin)
                <li><a class="dropdown-item" href="{{ route('reportes', 'Origen') }}">
                  Contactos X origen
                </a></li>
                <li><a class="dropdown-item" href="{{ route('reportes', 'Lados') }}">
                  Lados X Asesor
                </a></li>
                <li><a class="dropdown-item" href="{{ route('reportes', 'Comision') }}">
                  Comision X Asesor
                </a></li>
                <li><a class="dropdown-item" href="{{ route('reportes', 'Negociaciones') }}">
                  Negociaciones X mes
                </a></li>
                <li><a class="dropdown-item" href="{{ route('reportes', 'LadMes') }}">
                  Lados X mes
                </a></li>
                <li><a class="dropdown-item" href="{{ route('reportes', 'ComMes') }}">
                  Comision X mes
                </a></li>
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
                <li><a class="dropdown-item" href="{{ route('clientes.index') }}">
                  Clientes
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
                <li><a class="dropdown-item" href="{{ route('caracteristica') }}">
                  Caracteristica
                </a></li>
                <li><a class="dropdown-item" href="{{ route('ciudad') }}">
                  Ciudad
                </a></li>
                <li><a class="dropdown-item" href="{{ route('deseo') }}">
                  Deseo
                </a></li>
                <li><a class="dropdown-item" href="{{ route('estado') }}">
                  Estado
                </a></li>
                <li><a class="dropdown-item" href="{{ route('municipio') }}">
                  Municipio
                </a></li>
                <li><a class="dropdown-item" href="{{ route('origen') }}">
                  Origen
                </a></li>
                <li><a class="dropdown-item" href="{{ route('precio') }}">
                  Precio
                </a></li>
                <li><a class="dropdown-item" href="{{ route('resultado') }}">
                  Resultado
                </a></li>
                <li><a class="dropdown-item" href="{{ route('tipo') }}">
                  Tipo de propiedad
                </a></li>
                <li><a class="dropdown-item" href="{{ route('zona') }}">
                  Zona
                </a></li>
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

    <!-- Begin page content -->
    <main role="main" class="container">
        <div class="row mt-1">  <!-- margen de arriba (top margin) es 1 -->
            <div class="col-lg-12"> <!-- Cambie de sm a lg el 15/09/2019 -->
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

    <footer class="footer">
      <div class="container">
        <span class="text-muted">Piso 1, Centro Comercial Costanera Plaza I, Barcelona, 0281-416.0885.</span>
      </div>
    </footer>
    @yield('js')
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script-->
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script-->
    <!--script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script-->
    <!--script src="{{ asset('js/app.js') }}"></script-->
  </body>
</html>
