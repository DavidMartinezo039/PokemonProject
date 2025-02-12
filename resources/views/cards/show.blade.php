@extends('layouts.navbar-layout')

@section('title', 'Cards')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/Cards/show-cards.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/navigation.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/error-message.css') }}">
@endsection

@section('content')
    <div class="show-container">
        <div class="card">
            <img src="{{ $card->images['large'] }}" alt="{{ $card->name }}" class="img-fluid">
        </div>
        <div class="info-box">
            <h1>{{ $card->name }} ({{ $card->set->name ?? 'unknown set' }})</h1>
            <div class="inline-info">
                <p><strong>Supertype:</strong> {{ $card->supertype->name ?? 'N/A' }}</p>
                <p><strong>Type:</strong> {{ $card->types->isNotEmpty() ? implode(', ', $card->types->pluck('name')->toArray()) : 'N/A' }}</p>
                <p><strong>Subtypes:</strong> {{ $card->subtypes->isNotEmpty() ? implode(', ', $card->subtypes->pluck('name')->toArray()) : 'N/A' }}</p>
                <p><strong>HP:</strong> {{ $card->hp ?? 'N/A' }}</p>
                <p><strong>Rarity:</strong> {{ $card->rarity->name ?? 'N/A' }}</p>

            @if ($card->evolvesFrom)
                    <p><strong>Evolves from:</strong> {{ $card->evolvesFrom }}</p>
                @endif

                @if (!empty($card->evolvesTo) && is_array($card->evolvesTo))
                    <p><strong>Evolves to:</strong> {{ implode(', ', $card->evolvesTo) }}</p>
                @endif
                <p><strong>Illustrator:</strong> {{ $card->artist ?? 'Desconocido' }}</p>
                <p><strong>Flavor Text:</strong> {{ $card->flavorText ?? 'N/A' }}</p>
            </div>
            <h3>Attacks</h3>
            @if (!empty($card->attacks))
                <ul>
                    @foreach ($card->attacks as $attack)
                        <li class="attack-item">
                            <strong>{{ $attack['name'] }}</strong> ({{ $attack['damage'] ?? 'No damage' }}) <br>
                            <em>Cost:</em> {{ implode(', ', $attack['cost']) }} <br>
                            <em>Text:</em> {{ $attack['text'] ?? 'N/A' }} <br>
                            <em>Converted cost:</em> {{ $attack['convertedEnergyCost'] ?? 'N/A' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>He has no attacks.</p>
            @endif

            <!-- Información de la carta -->
            <h3>Weaknesses, Resistances and Withdrawal Cost</h3>
            <div class="inline-info">
                @if (!empty($card->weaknesses))
                    <p><strong>Weaknesses:</strong>
                        {{ implode(', ', array_map(fn($weakness) => $weakness['type'] . ' (x' . $weakness['value'] . ')', $card->weaknesses)) }}
                    </p>
                @endif

                @if (!empty($card->resistances))
                    <p><strong>Resistances:</strong>
                        {{ implode(', ', array_map(fn($resistance) => $resistance['type'] . ' (-' . $resistance['value'] . ')', $card->resistances)) }}
                    </p>
                @endif

                @if (!empty($card->retreatCost))
                    <p><strong>Withdrawal Cost:</strong>
                        {{ implode(', ', $card->retreatCost) }}
                    </p>
                @endif
            </div>

            <h3>Legality in tournaments</h3>
            <div class="inline-info">
                @if (!empty($card->legalities))
                    @foreach ($card->legalities as $format => $status)
                        <p><strong>{{ ucfirst($format) }}:</strong> {{ ucfirst($status) }}</p>
                  @endforeach
                @else
                    <p>It has no legality information.</p>
               @endif
            </div>

            <h3>Precios</h3>
            <div class="inline-info">
                <div class="prices-section">
                    <p><strong>TCGPlayer:</strong> <a href="{{ $card->tcgplayer['url'] }}" target="_blank">See in TCGPlayer</a></p>
                    <div>
                        @foreach ($card->tcgplayer['prices'] as $rarity => $price)
                            <span><strong>{{ ucfirst($rarity) }}:</strong> ${{ $price['market'] ?? 'N/A' }} USD</span>
                        @endforeach
                    </div>
                </div>

                <div class="prices-section">
                    <p><strong>CardMarket:</strong> <a href="{{ $card->cardmarket['url'] }}" target="_blank">See in CardMarket</a></p>
                    <div>
                        <span><strong>Precio medio 7 días:</strong> ${{ $card->cardmarket['prices']['avg7'] ?? 'N/A' }} USD</span>
                        <span><strong>Precio medio 30 días:</strong> ${{ $card->cardmarket['prices']['avg30'] ?? 'N/A' }} USD</span>
                    </div>
                </div>
            </div>


            <a href="javascript:history.back()" class="btn btn-primary">Back to list</a>
        </div>
    </div>
@endsection
