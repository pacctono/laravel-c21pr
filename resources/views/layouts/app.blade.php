<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.css" integrity="sha256-CNwnGWPO03a1kOlAsGaH5g8P3dFaqFqqGFV/1nkX5OU=" crossorigin="anonymous" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script>
      var $;

      window.onload=function() {

        if (!$) { $ = document.getElementById; }
      }
    </script>
  </head>

  <body>

    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="">C21 Puente Real</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
        @auth
          <ul class="navbar-nav mr-auto col-md-10">
          @foreach (['home', 'contactos', 'users', 'turnos', 'agenda', 'reportes'] as $hMenu)
            @if ($hMenu == substr($view_name, 0, 
                ((strpos($view_name, '-'))?(strpos($view_name, '-')):4)))
            <li class="nav-item active">
            @else
            <li class="nav-item">
            @endif
            @if ('users' != $hMenu)
              <a class="nav-link" href="/{{ $hMenu }}">
            @else
              <a class="nav-link" href="/usuarios">
            @endif
            @if ('users' != $hMenu)
                {{ ucfirst($hMenu) }} <!-- span class="sr-only">(current)</span -->
            @else
              Asesores
            @endif
              </a>
            </li>
          @endforeach
          </ul>
          @endauth
          <ul class="navbar-nav ml-auto col-md-2">
          @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
          @else
            <li class="nav-item dropdown">
              <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                {{ Auth::user()->name }}
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item active" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
        <div class="row mt-3">
            <div class="col-sm-12">
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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
