<div>
    <h2>Detalles del Set: {{ $set->name }}</h2>
    <p>Cartas del Set:</p>
    <ul>
        @foreach($set->cards as $card)
            <li>{{ $card->name }}</li> <!-- Mostrar el nombre de cada carta -->
        @endforeach
    </ul>
</div>
