<div class="container">
        <div class="card mb-4">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{ $card->images['large'] }}" class="img-fluid rounded-start" alt="Imagen de {{ $card->name }}">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h1 class="card-title">{{ $card->name }}</h1>
                        <p class="card-text">
                            <strong>Rareza:</strong>
                            @if ($card->rarity)
                                {{ $card->rarity->name }}
                            @else
                                <em>No tiene rareza</em>
                            @endif
                        </p>
                        <p class="card-text">
                            <strong>Subtipos:</strong>
                            @if ($card->subtypes->isEmpty())
                                <em>No tiene subtipos</em>
                            @else
                                @foreach ($card->subtypes as $subtype)
                                    <span>{{ $subtype->name }}</span>@if (!$loop->last), @endif
                                @endforeach
                            @endif
                        </p>
                        <p class="card-text">
                            <strong>Descripción:</strong> {{ $card->description ?? 'No disponible' }}
                        </p>
                        <p class="card-text">
                            <strong>Set:</strong> {{ $card->set->name ?? 'No asignado' }}
                        </p>
                        <a href="{{ route('cards.index') }}" class="btn btn-primary">Volver a la lista</a>
                    </div>
                </div>
            </div>
        </div>
</div>
