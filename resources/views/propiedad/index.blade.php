@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('propiedad.create') }}" class="btn btn-primary">Crear propiedad</a>
        </p>
    </div>

    @if ($propiedades->isNotEmpty())
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
        @foreach ($propiedades as $propiedad)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $propiedad->id }}</td>
            <td>{{ $propiedad->descripcion }}</td>
            <td>
                <form action="{{ route('propiedad.destroy', $propiedad) }}" method="POST"
                        id="forma.{{ $propiedad->id }}" name="forma.{{ $propiedad->id }}"
                        onSubmit="return estaSeguro({{ $propiedad->id }})">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <a href="{{ route('propiedad.show', $propiedad) }}" class="btn btn-link">
                        <span class="oi oi-eye"></span>
                    </a>
                    <a href="{{ route('propiedad.edit', $propiedad) }}" class="btn btn-link">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <button class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $propiedades->links() }}
    @else
        <p>No hay propiedades registrados.</p>
    @endif

@endsection

@section('js')
<script>
function estaSeguro(id) {
    return confirm('Realmente, desea borrar los datos de este propiedad de la base de datos?')
}
</script>

@endsection
