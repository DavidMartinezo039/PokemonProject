<div class="row">
    @forelse ($cards->items() as $card) <!-- Usamos forelse para manejar el caso de no haber cartas -->
    <div class="col-md-4">
        <div class="card">
            <!-- Comprobamos si 'images' es un array y si la clave 'small' está presente -->
            @if (isset($card->images['small']) && !empty($card->images['small']))
                <img src="{{ $card->images['small'] }}" alt="Imagen de {{ $card->name }}" style="width: 100px; height: 100px;">
            @else
                <p>No imagen disponible</p>
            @endif

            <div class="card-body">
                <h5 class="card-title">{{ $card->name }}</h5>
                <p class="card-text">Rareza:
                    @if ($card->rarity)
                        {{ $card->rarity->name }}
                    @else
                        <em>No tiene rareza</em>
                    @endif
                </p>
            </div>
        </div>
    </div>
    @empty
        <p>{{ $message }}</p> <!-- Mostrar mensaje si no hay cartas -->
    @endforelse
</div>

<!-- Paginación -->
<div class="pagination-container">
    {{ $cards->links() }}
</div>
