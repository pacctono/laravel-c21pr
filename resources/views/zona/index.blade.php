@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('zona.create') }}" class="btn btn-primary">Crear zona</a>
        </p>
    </div>

    @if ($zonas->isNotEmpty())
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
        @foreach ($zonas as $zona)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $zona->id }}</td>
            <td>{{ $zona->descripcion }}</td>
            <td>
                <form action="{{ route('zona.destroy', $zona) }}" method="POST"
                        id="forma.{{ $zona->id }}" name="forma.{{ $zona->id }}"
                        onSubmit="return estaSeguro({{ $zona->id }})">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <a href="{{ route('zona.show', $zona) }}" class="btn btn-link">
                        <span class="oi oi-eye"></span>
                    </a>
                    <a href="{{ route('zona.edit', $zona) }}" class="btn btn-link">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <button class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $zonas->links() }}
    @else
        <p>No hay zonas registrados.</p>
    @endif

@endsection

@section('js')
<script>
function estaSeguro(id) {
    return confirm('Realmente, desea borrar los datos de este zona de la base de datos?')
}
</script>

@endsection
