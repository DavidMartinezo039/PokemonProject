<div class="row">
    @if($cards->isEmpty())
        <p>No hay cartas disponibles.</p>
    @else
        @foreach ($cards as $card)
            <div class="col-md-4">
                <div class="card">
                    <img src="{{ $card->images['small'] }}" alt="Imagen de {{ $card->name }}" style="width: 100px; height: 100px;">
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
        @endforeach
    @endif
</div>
