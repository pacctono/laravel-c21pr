<!doctype html>
<html lang="es">

  @includeIf('layouts.head')

  <body>

@if (!isset($accion) or ('html' == $accion))
    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark{{--bg-transparent border border-dark--}}">
        <a class="navbar-brand" href="http://www.century21.com.ve/@puenterealbienesraices" target="_blank">
          <img src="{{ (asset('img/logoC21pr.jpg')) }}" title="C21 Puente Real"
                alt="C21 Puente Real" style="height:30px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"   {{-- Permite mostrar menu en pantallas pequeñas --}}
                data-target="#navbarCollapse" aria-controls="navbarCollapse"  {{-- Por ejemplo, la pantalla de los celulares --}}
                aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
        @auth
          <ul class="navbar-nav mr-auto col-lg-10">
          @foreach (array('' => 'INICIO', 'home' => 'Home', 'contactos' => 'Contactos',
                    'users' => 'Asesores', 'turnos' => 'Turnos', 'agenda' => 'Agenda',
                    'propiedades' => 'Propiedades', 'clientes' => 'Clientes')
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
          <ul class="navbar-nav ml-auto col-lg-2">
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
        <div class="col-12 ml-0 pl-0 no-gutters"> <!-- Cambie de sm a lg el 15/09/2019 -->
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
      <div class="container-fluid">
        <span class="text-white bg-dark pr-4"><!-- text-muted: texto color gris -->
@if (!$agent->isMobile())
          Av. Costanera, Centro Comercial Costanera Plaza I, Piso 1, Local P1-02, Nueva Barcelona, frente al CC Camino Real
          <i class="fa fa-phone-alt"></i>0281-416.0885
          <a hre="mailto:c21puentereal@gmail.com" class="btn btn-link m-0 p-0 enlaceFooter"
              data-toggle="tooltip" title="Enviar correo a Puente Real">
            <i class="fa fa-at"></i></a>.
        </span>
        <span class="text-white bg-dark pl-4"><!-- text-muted: texto color gris -->
          &copy; Copyright 2019-{{ date('Y') }}
@else (!$agent->isMobile())
          Av Costanera, CC Costanera Plaza I, Piso 1
@endif (!$agent->isMobile())
        </span>
      </div>
    </footer>
@endif ((!isset($accion) or ('html' == $accion)) and !$movil)

@includeWhen((!$agent->isMobile()), 'layouts.botonesExternos')

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
