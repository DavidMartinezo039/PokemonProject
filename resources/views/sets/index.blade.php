    <h1>Lista de Sets</h1>

    @if($sets->isEmpty())
        <p>No hay sets disponibles.</p>
    @else
        <ul>
            @foreach ($sets as $set)
                <li>
                    <a href="{{ route('sets.show', $set->id) }}">{{ $set->name }}</a>
                    <!-- Aquí puedes agregar más detalles si lo deseas -->
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('sets.create') }}">Crear nuevo set</a>
