<div class="row">
    @forelse ($cards as $card)
    <div class="col-md-4">
        <div class="card">
            <img src="{{ $card->images['small'] }}" alt="Imagen de {{ $card->name }}">


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

<div class="pagination-container">
    {{ $cards->links('vendor.pagination.tailwind') }}
</div>
