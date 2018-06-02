@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('precio.create') }}" class="btn btn-primary">Crear precio</a>
        </p>
    </div>

    @if ($precios->isNotEmpty())
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
        @foreach ($precios as $precio)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $precio->id }}</td>
            <td>{{ $precio->descripcion }}</td>
            <td>
                <form action="{{ route('precio.destroy', $precio) }}" method="POST"
                        id="forma.{{ $precio->id }}" name="forma.{{ $precio->id }}"
                        onSubmit="return estaSeguro({{ $precio->id }})">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <a href="{{ route('precio.show', $precio) }}" class="btn btn-link">
                        <span class="oi oi-eye"></span>
                    </a>
                    <a href="{{ route('precio.edit', $precio) }}" class="btn btn-link">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <button class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $precios->links() }}
    @else
        <p>No hay precios registrados.</p>
    @endif

@endsection

@section('js')
<script>
function estaSeguro(id) {
    return confirm('Realmente, desea borrar los datos de este precio de la base de datos?')
}
</script>

@endsection
