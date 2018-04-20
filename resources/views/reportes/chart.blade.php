@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="pb-1">{{ $title }}</h4>
</div>

<div>{!! $chart->container() !!}</div>

@if ($contactos->isNotEmpty())
<table class="table table-striped table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Asesor</th>
      <th scope="col">Atendidos</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($contactos as $contacto)
    <tr>
      <td>{{ $contacto->user->name }}</td>
      <td>{{ $contacto->atendidos }}</td>
    </tr>
  @endForeach
  </tbody>
</table>
@else
<p>No hay contactos registrados.</p>
@endif

@endsection

@section('js')
{{-- //www.chartjs.org/docs/latest/ --}}
    <script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
    {!! $chart->script() !!}

@endsection