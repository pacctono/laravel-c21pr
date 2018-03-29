@extends('layouts.app')

@section('content')
<div class="card col-8">
    <h4 class="card-header">{{ $title }}</h4>
    <div class="card-body">
    @if ($errors->any())
    <div class="alert alert-danger">
        <h5>Por favor corrige los errores debajo:</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    </div>
</div>

@endsection