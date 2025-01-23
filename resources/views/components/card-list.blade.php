<div class="cards-row">
    @if($cards->isEmpty())
        <div class="error-message">
            <p>No hay cartas disponibles.</p>
        </div>
    @else
    @foreach ($cards as $card)
        <div class="card-container">
            <a href="{{ route('cards.show', $card->id) }}" class="card-link">
                <div class="card">
                    <img src="{{ $card->images['small'] }}" alt="Imagen de {{ $card->name }}" class="card-img">
                </div>
            </a>
        </div>
    @endforeach
    @endif
</div>


<div class="pagination-container">
    {{ $cards->links('vendor.pagination.tailwind') }}
</div>
