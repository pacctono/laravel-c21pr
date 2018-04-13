@extends('layouts.app')

@section('content')

    <div>{!! $chart->container() !!}</div>

@endsection

@section('js')
{{-- //www.chartjs.org/docs/latest/ --}}
    <script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
    {!! $chart->script() !!}

@endsection