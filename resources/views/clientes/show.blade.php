@extends('layouts.app')

@section('content')
<div class="card">
    <h4 class="card-header m-1 p-0">Cliente: {{ $cliente->name }}</h4>
    <div class="card-body m-0 p-0">
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                C&eacute;dula:<span class="alert-info">
                    {{ $cliente->cedula_f }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0 bg-suave">
            <div class="m-0 py-0 px-1">
                Rif:<span class="alert-info">
                    {{ $cliente->rif_f }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Tipo de cliente:<span class="alert-info">
                    {{ $cliente->tipo_alfa }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0 bg-suave">
            <div class="m-0 py-0 px-1">
                Telefono:<span class="alert-info">
                    {{ $cliente->telefono_f }}
                </span>
            </div>
        @if ($cliente->otro_telefono)
            <div class="m-0 py-0 px-1">
                Otro telefono:<span class="alert-info">
                    {{ $cliente->otro_telefono }}
                </span>
            </div>
        @endif ($contacto->otro_telefono)
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Correo:<span class="alert-info">
                    {{ $cliente->email }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0 bg-suave">
            <div class="m-0 py-0 px-1">
                Fecha de nacimiento:<span class="alert-info">
                    {{ $cliente->fec_nac }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Dirección:<span class="alert-info">
                    {{ $cliente->direccion }}
                </span>
            </div>
        </div>
        <div class="row my-1 mx-0 p-0 bg-suave">
            <div class="m-0 py-0 px-1">
                Observaciones:<span class="alert-info">
                    {{ $cliente->observaciones }}
                </span>
            </div>
        </div>

    @if (!(is_null($cliente->user_borro)))
        <div class="row my-1 mx-0 p-0 bg-suave">
            <div class="m-0 py-0 px-1">
                Este cliente fue borrado por {{ $cliente->userBorro->name }}
                el {{ $diaSemana[$cliente->deleted_at->format('w')] }},
                {{ $cliente->deleted_at->format('d/m/Y') }}.
            </div>
        </div>
    @endif
    @if (!(is_null($cliente->user_actualizo)))
        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                Este cliente fue actualizado por {{ $cliente->userActualizo->name }}
                el {{ $diaSemana[$cliente->updated_at->format('w')] }},
                {{ $cliente->updated_at->format('d/m/Y') }}.
            </div>
        </div>
    @endif

        <div class="row my-1 mx-0 p-0">
            <div class="m-0 py-0 px-1">
                <!-- a href="{{ action('ClienteController@index') }}">Regresar al listado de usuarios</a -->
                <a href="{{ route('clientes.orden', $orden).$nroPagina }}" class="btn btn-link">
                    Regresar al listado de clientes
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
