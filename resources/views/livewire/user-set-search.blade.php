<div>
    <!-- Campo de búsqueda -->
    <input type="text" placeholder="Buscar sets..." wire:model="searchTerm" wire:keydown.debounce.100ms="search" />

    @if(isset($message))
        <div class="error-message">
            {{ $message }}
        </div>
    @else
        <div class="serie-group">
            <div class="sets-grid">
                @foreach ($userSets as $set)
                    <a href="{{ route('user-sets.cards', $set->id) }}" class="set-card">
                        <img src="{{ $set->image ?? asset('images/default-set.png') }}" alt="{{ $set->name }}" class="user-set-img">
                        <p>{{ $set->name }}</p>
                        <p>{{__('Cards')}}: {{ $set->card_count }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
