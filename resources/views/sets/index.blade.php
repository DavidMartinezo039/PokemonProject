<h1>Lista de Sets</h1>

@if(isset($message))
    <div class="alert alert-info">
        {{ $message }}
    </div>
@else
    <ul>
        @foreach ($sets as $set)
            <li>
                <a href="{{ route('sets.show', $set->id) }}">
                    <img src="{{ $set->images['logo'] }}" alt="Logo de {{ $set->name }}" style="width: 100px; height: 100px; object-fit: contain;">
                </a>
            </li>
        @endforeach
    </ul>
@endif

<a href="{{ route('sets.create') }}">Crear nuevo set</a>
