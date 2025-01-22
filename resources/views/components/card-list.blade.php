<div class="cards-row">
    @foreach ($cards as $card)
        <div class="card-container">
            <a href="{{ route('cards.show', $card->id) }}" class="card-link">
                <div class="card">
                    <img src="{{ $card->images['small'] }}" alt="Imagen de {{ $card->name }}" class="card-img">
                </div>
            </a>
        </div>
    @endforeach
</div>


<div class="pagination-container">
    {{ $cards->links('vendor.pagination.tailwind') }}
</div>
