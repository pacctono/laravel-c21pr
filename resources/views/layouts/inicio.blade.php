<!doctype html>
<html lang="es">

  @includeIf('layouts.head')

  <body>

@if (!isset($accion) or ('html' == $accion))
    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="http://www.century21.com.ve/@puenterealbienesraices" target="_blank">
          <img src="{{ (asset('img/logoC21prNohalo.png')) }}" title="C21 Puente Real"
                alt="C21 Puente Real" style="height:12px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse"   {{-- Permite mostrar menu en pantallas pequeñas --}}
                data-target="#navbarCollapse" aria-controls="navbarCollapse"  {{-- Por ejemplo, la pantalla de los celulares --}}
                aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mx-auto my-0 p-0">
          @foreach (array('' => 'INICIO', '#propiedades' => 'PROPIEDADES', '#asesores' => 'ASESORES',
                    '#inicio' => 'BUSCAR INMUEBLES', '#blog' => 'BLOG', '#contactanos' => 'CONTACTANOS',
                    '#ubicacion' => 'UBICACIÓN')
                    as $hMenu => $muestraMenu)
            <li class="nav-item mr-2
            @if ($hMenu == substr($view_name, 0, 
                ((strpos($view_name, '-'))?(strpos($view_name, '-')):4)))
                active">
            @else
                ">
            @endif
              <a class="nav-link" href="/{{ $hMenu }}">
                {{ $muestraMenu }} <!-- span class="sr-only">(current)</span -->
              </a>
            </li>
          @endforeach
            <li class="nav-item ml-2">
          @auth
            <a href="{{ url('/home') }}">Home</a>
          @else
            <a href="{{ route('login') }}">Login</a>
            {{-- <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>--}}
          @endauth
            </li>
          </ul>
        </div>
      </nav>
    </header>
@endif (!isset($accion) or ('html' == $accion))

    <!-- Begin page content -->
    <main role="main" class="container-fluid"><!-- rem depende del elemento raiz de la pagina, o sea del tamaño fuente del elemento <html> -->
      <div class="row mt-5 p-0 no-gutters">
        <!--div class="col-12 m-0 p-0 no-gutters"-->
          @yield('content')
        <!--/div-->
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
@endif (!isset($accion) or ('html' == $accion))

@includeWhen(((!isset($accion) or ('html' == $accion)) and (!$agent->isMobile())),
                'layouts.botonesExternos')

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
