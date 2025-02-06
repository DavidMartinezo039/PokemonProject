<div class="cards-row">
    @if(session('message'))
        <div class="error-message">
            <p>{{ session('message') }}</p>
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

@if ($cards instanceof \Illuminate\Pagination\LengthAwarePaginator && $cards->hasPages())
    <div class="pagination-container">
        {{ $cards->links('vendor.pagination.tailwind') }}
    </div>
@endif

