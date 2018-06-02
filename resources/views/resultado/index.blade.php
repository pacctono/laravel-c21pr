@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('resultado.create') }}" class="btn btn-primary">Crear resultado</a>
        </p>
    </div>

    @if ($resultados->isNotEmpty())
    <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">id</th>
            <th scope="col">Descripcion</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($resultados as $resultado)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $resultado->id }}</td>
            <td>{{ $resultado->descripcion }}</td>
            <td>
                <form action="{{ route('resultado.destroy', $resultado) }}" method="POST"
                        id="forma.{{ $resultado->id }}" name="forma.{{ $resultado->id }}"
                        onSubmit="return estaSeguro({{ $resultado->id }})">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <a href="{{ route('resultado.show', $resultado) }}" class="btn btn-link">
                        <span class="oi oi-eye"></span>
                    </a>
                    <a href="{{ route('resultado.edit', $resultado) }}" class="btn btn-link">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <button class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $resultados->links() }}
    @else
        <p>No hay resultados registrados.</p>
    @endif

@endsection

@section('js')
<script>
function estaSeguro(id) {
    return confirm('Realmente, desea borrar los datos de este resultado de la base de datos?')
}
</script>

@endsection
