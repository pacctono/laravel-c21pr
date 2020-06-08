@extends('layouts.inicio')

@section('content')
                <div class="container-fluid">
                    <div id="propiedades" class="card m-0 p-0">
                        <h4 class="card-header m-0 pt-3 pb-0 px-0 colorFondo1">PROPIEDADES</h4>
                        <div class="row card-body fondoPropiedades m-0 pt-3 pb-0 px-0"
                            style="background-image:url({{ asset('img/fondoPropiedadesLR.jpg') }})">
                        @foreach ($propiedades as $propiedad)
                            <div class="col-lg-3 m-0 p-0">
                        @if ($propiedad->imagen)
                                <div class="bg-transparent d-block mx-auto my-0 px-2 py-0">
                        @else ($propiedad->imagen)
                                <div class="bg-info w-100 d-block mx-auto my-0 px-2 py-0" style="height:300px;">
                        @endif ($propiedad->imagen)
                                    <img class="img-fluid m-0 p-0 imagenPropiedad"
                                            src="{{ asset('storage/imgprop/'.$propiedad->imagen) }}"
                                            alt="{{ $propiedad->nombre }}" data-toggle="tooltip"
                                            title="{{ $propiedad->nombre }}">
                                </div>
                                <div class="d-block mx-auto my-0 px-2 py-0 {{--border border-dark--}}">
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                        <p class="m-0 p-0">
                                            {{ $propiedad->nombre }}
                                        </p>
                                    </div>
                                    <div class="row colorFondo1 justify-content-center m-0 p-0">
                                        <a class="m-0 p-0" href="/propiedades/{{ $propiedad->id }}">
                                            <button class="btn btn-sm m-0 p-0">Ver inmueble</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach ($propiedades as $propiedad)
                        </div>
                    </div>
                </div>
@endsection

{{--@if (!isset($accion) or ('html' == $accion))
@section('js')

@includeIf('jqwelcome')

@endsection('js')
@endif (!isset($accion) or ('html' == $accion))--}}
