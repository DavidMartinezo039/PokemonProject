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
                <p><strong>{{__('Supertype')}}:</strong> {{ $card->supertype->name ?? 'N/A' }}</p>
                <p><strong>{{__('Types')}}:</strong> {{ $card->types->isNotEmpty() ? implode(', ', $card->types->pluck('name')->toArray()) : 'N/A' }}</p>
                <p><strong>{{__('Subtypes')}}:</strong> {{ $card->subtypes->isNotEmpty() ? implode(', ', $card->subtypes->pluck('name')->toArray()) : 'N/A' }}</p>
                <p><strong>{{__('HP')}}:</strong> {{ $card->hp ?? 'N/A' }}</p>
                <p><strong>{{__('Rarity')}}:</strong> {{ $card->rarity->name ?? 'N/A' }}</p>

            @if ($card->evolvesFrom)
                    <p><strong>{{__('Evolves from')}}:</strong> {{ $card->evolvesFrom }}</p>
                @endif

                @if (!empty($card->evolvesTo) && is_array($card->evolvesTo))
                    <p><strong>{{__('Evolves to')}}:</strong> {{ implode(', ', $card->evolvesTo) }}</p>
                @endif
                <p><strong>{{__('Ilustrator')}}:</strong> {{ $card->artist ?? 'Desconocido' }}</p>
                <p><strong>{{__('Flavor Text')}}:</strong> {{ $card->flavorText ?? 'N/A' }}</p>
            </div>
            <h3>{{__('Attacks')}}</h3>
            @if (!empty($card->attacks))
                <ul>
                    @foreach ($card->attacks as $attack)
                        <li class="attack-item">
                            <strong>{{ $attack['name'] }}</strong> ({{ $attack['damage'] ?? 'No damage' }}) <br>
                            <em>{{__('Cost')}}:</em> {{ implode(', ', $attack['cost']) }} <br>
                            <em>{{__('Text')}}:</em> {{ $attack['text'] ?? 'N/A' }} <br>
                            <em>{{__('Converted Cost')}}:</em> {{ $attack['convertedEnergyCost'] ?? 'N/A' }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>{{__('He has no attacks')}}</p>
            @endif

            <!-- Información de la carta -->
            <h3>{{__('Weaknesses, Resistances and Withdrawal Cost')}}</h3>
            <div class="inline-info">
                @if (!empty($card->weaknesses))
                    <p><strong>{{__('Weaknesses')}}:</strong>
                        {{ implode(', ', array_map(fn($weakness) => $weakness['type'] . ' (x' . $weakness['value'] . ')', $card->weaknesses)) }}
                    </p>
                @endif

                @if (!empty($card->resistances))
                    <p><strong>{{__('Resistances')}}:</strong>
                        {{ implode(', ', array_map(fn($resistance) => $resistance['type'] . ' (-' . $resistance['value'] . ')', $card->resistances)) }}
                    </p>
                @endif

                @if (!empty($card->retreatCost))
                    <p><strong>{{__('Withdrawal Cost')}}:</strong>
                        {{ implode(', ', $card->retreatCost) }}
                    </p>
                @endif
            </div>

            <h3>{{__('Legality in tournaments')}}</h3>
            <div class="inline-info">
                @if (!empty($card->legalities))
                    @foreach ($card->legalities as $format => $status)
                        <p><strong>{{ ucfirst($format) }}:</strong> {{ ucfirst($status) }}</p>
                  @endforeach
                @else
                    <p>{{__('It has no legality information')}}</p>
               @endif
            </div>

            <h3>{{__('Prices')}}</h3>
            <div class="inline-info">
                <div class="prices-section">
                    <p><strong>TCGPlayer:</strong> <a href="{{ $card->tcgplayer['url'] }}" target="_blank">{{__('See in')}} TCGPlayer</a></p>
                </div>

                <div class="prices-section">
                    <p><strong>CardMarket:</strong> <a href="{{ $card->cardmarket['url'] }}" target="_blank">{{__('See in')}} CardMarket</a></p>
                </div>
            </div>


            <a href="javascript:history.back()" class="btn btn-primary">{{__('Back to list')}}</a>
        </div>
    </div>
@endsection
