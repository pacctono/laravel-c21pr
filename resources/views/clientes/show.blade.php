@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header">Cliente: {{ $cliente->name }}</h4>
    <div class="card-body">
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                C&eacute;dula:<span class="alert-info">
                    {{ $cliente->cedula_f }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1 bg-suave">
            <div class="mx-1 px-2">
                Rif:<span class="alert-info">
                    {{ $cliente->rif_f }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Telefono:<span class="alert-info">
                    {{ $cliente->telefono_f }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1 bg-suave">
            <div class="mx-1 px-2">
                Correo:<span class="alert-info">
                    {{ $cliente->email }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Fecha de nacimiento:<span class="alert-info">
                    {{ $cliente->fec_nac }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1 bg-suave">
            <div class="mx-1 px-2">
                Dirección:<span class="alert-info">
                    {{ $cliente->direccion }}
                </span>
            </div>
        </div>
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Observaciones:<span class="alert-info">
                    {{ $cliente->observaciones }}
                </span>
            </div>
        </div>

    @if (!(is_null($cliente->user_borro)))
        <div class="row my-1 py-1 bg-suave">
            <div class="mx-1 px-2">
                Este cliente fue borrado por {{ $cliente->userBorro->name }}
                el {{ $diaSemana[$cliente->deleted_at->format('w')] }},
                {{ $cliente->deleted_at->format('d/m/Y') }}.
            </div>
        </div>
    @endif
    @if (!(is_null($cliente->user_actualizo)))
        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                Este cliente fue actualizado por {{ $cliente->userActualizo->name }}
                el {{ $diaSemana[$cliente->updated_at->format('w')] }},
                {{ $cliente->updated_at->format('d/m/Y') }}.
            </div>
        </div>
    @endif

        <div class="row my-1 py-1">
            <div class="mx-1 px-2">
                <!-- a href="{{ action('ClienteController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ route('clientes.orden', $orden).$nroPagina }}" class="btn btn-link">
                    Regresar al listado de clientes
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
