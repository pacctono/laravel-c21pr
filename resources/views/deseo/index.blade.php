@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('deseo.create') }}" class="btn btn-primary">Crear Deseo</a>
        </p>
    </div>

    @if ($deseos->isNotEmpty())
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
        @foreach ($deseos as $deseo)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $deseo->id }}</td>
            <td>{{ $deseo->descripcion }}</td>
            <td>
                <form action="{{ route('deseo.destroy', $deseo) }}" method="POST"
                        id="forma.{{ $deseo->id }}" name="forma.{{ $deseo->id }}"
                        onSubmit="return estaSeguro({{ $deseo->id }})">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <a href="{{ route('deseo.show', $deseo) }}" class="btn btn-link">
                        <span class="oi oi-eye"></span>
                    </a>
                    <a href="{{ route('deseo.edit', $deseo) }}" class="btn btn-link">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <button class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $deseos->links() }}
    @else
        <p>No hay deseos registrados.</p>
    @endif

@endsection

@section('js')
<script>
function estaSeguro(id) {
    return confirm('Realmente, desea borrar los datos de este deseo de la base de datos?')
}
</script>

@endsection
