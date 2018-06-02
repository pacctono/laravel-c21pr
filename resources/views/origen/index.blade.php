@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">{{ $title }}</h1>

        <p>
            <a href="{{ route('origen.create') }}" class="btn btn-primary">Crear origen</a>
        </p>
    </div>

    @if ($origenes->isNotEmpty())
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
        @foreach ($origenes as $origen)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $origen->id }}</td>
            <td>{{ $origen->descripcion }}</td>
            <td>
                <form action="{{ route('origen.destroy', $origen) }}" method="POST"
                        id="forma.{{ $origen->id }}" name="forma.{{ $origen->id }}"
                        onSubmit="return estaSeguro({{ $origen->id }})">
                    {{ csrf_field() }}
                    {{ method_field('DELETE' )}}

                    <a href="{{ route('origen.show', $origen) }}" class="btn btn-link">
                        <span class="oi oi-eye"></span>
                    </a>
                    <a href="{{ route('origen.edit', $origen) }}" class="btn btn-link">
                        <span class="oi oi-pencil"></span>
                    </a>
                    <button class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
            </td>
        </tr>
        @endForeach
        </tbody>
    </table>
    {{ $origenes->links() }}
    @else
        <p>No hay origenes registrados.</p>
    @endif

@endsection

@section('js')
<script>
function estaSeguro(id) {
    return confirm('Realmente, desea borrar los datos de este origen de la base de datos?')
}
</script>

@endsection
