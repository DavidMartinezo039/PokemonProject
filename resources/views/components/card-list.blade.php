<div class="cards-row">
    @forelse ($cards as $card)
        <div class="card-container">
            <div class="card-link"
                 data-id="{{ $card->id }}"
                 data-name="{{ $card->name }}"
                 data-image="{{ $card->images['large'] }}"
                 data-rarity="{{ $card->rarity->name ?? __('It has no rarity') }}"
                 data-set="{{ $card->set->name ?? __('It has no set') }}"
                 onclick="openModal(this)">
                <div class="card">
                    <img src="{{ $card->images['small'] }}" alt="Imagen de {{ $card->name }}" class="card-img">
                </div>
            </div>
        </div>
    @empty
        @if(session('message'))
            <div class="error-message">
                <p>{{ session('message') }}</p>
            </div>
        @endif
    @endforelse
</div>

@if ($cards instanceof \Illuminate\Pagination\LengthAwarePaginator && $cards->hasPages())
    <div class="pagination-container">
        {{ $cards->links('vendor.pagination.tailwind') }}
    </div>
@endif

<!-- Modal para mostrar la carta en grande -->
<div id="cardModal" class="modal" onclick="clickOutside(event)">
    <div class="modal-content">
        <!-- Imagen de la carta -->
        <img id="modalCardImage" src="" alt="Carta" class="modal-card-img">

        <!-- Contenedor de los detalles -->
        <div class="modal-details">
            <h2 id="modalCardName"></h2>
            <p><strong>{{__('Rarity')}}:</strong> <span id="modalCardRarity"></span></p>
            <p><strong>{{__('Set')}}:</strong> <span id="modalCardSet"></span></p>

            <!-- Botón grande y llamativo -->
            <a id="viewCardButton" class="view-card-button" href="#">{{__('See More Details')}}</a>
        </div>

        <!-- Botón de cerrar -->
        <span class="close" onclick="closeModal()">&times;</span>
    </div>
</div>

