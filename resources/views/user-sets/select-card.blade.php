@extends('layouts.navbar-layout')

@section('title', 'Seleccionar Carta')

@section('additional-css')

    <link rel="stylesheet" href="{{ asset('View/css/Cards/cards.css') }}">

@endsection

@section('content')
    <a href="{{ url()->previous() }}" class="back-button">Volver</a>

    <div class="cards-row">
        @foreach ($cards as $card)
            <div class="card-container">
                <form action="{{ route('user-sets.add-card', ['userSetId' => $userSet->id, 'cardId' => $card->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="add-card-button"><div class="card">
                            <img src="{{ $card->images['small'] }}" alt="Imagen de {{ $card->name }}" class="card-img">
                        </div></button>
                </form>

            </div>
        @endforeach
    </div>

    @if ($cards instanceof \Illuminate\Pagination\LengthAwarePaginator && $cards->hasPages())
        <div class="pagination-container">
            {{ $cards->links('vendor.pagination.tailwind') }}
        </div>
    @endif
@endsection
