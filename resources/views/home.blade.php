@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            @if ($errors->any())
                <div class="card-header">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <img src="{{ (asset('img/inauguracion1.jpg')) }}"
                        alt="Conectado a la web de Century21 Puente Real"
                        width="1040" height="600"
                    >
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
