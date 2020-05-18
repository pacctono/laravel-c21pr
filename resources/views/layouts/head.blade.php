  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ config('app.name', 'Century 21 Puente Real') }}">
    <meta name="author" content="Pablo Caraballo">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <title>{{ config('app.name', 'Century 21 Puente Real') }}</title>

@if (!isset($accion) or ('html' == $accion){{-- or ('reportes-chart' == $view_name)--}})
    <!-- Bootstrap core CSS y otros -->
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
