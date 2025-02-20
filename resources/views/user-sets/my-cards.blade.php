@extends('layouts.navbar-layout')

@section('title', 'Mis Cartas del Set')

@section('additional-css')

    <link rel="stylesheet" href="{{ asset('View/css/Cards/cards.css') }}">

@endsection

@section('content')
    <a href="{{ url()->previous() }}" class="back-button">{{__('Return')}}</a>

    <div class="cards-row">
        @foreach ($userSet->cards as $card)
            <div class="card-container">


                <form action="{{ route('user-sets.remove-card', ['userSet' => $userSet, 'card' => $card]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="remove-card-button"><div class="card">
                            <img src="{{ $card->images['small'] }}" alt="Imagen de {{ $card->name }}" class="card-img">
                        </div></button>
                </form>
            </div>
        @endforeach
    </div>

    @if ($userSet->cards instanceof \Illuminate\Pagination\LengthAwarePaginator && $userSet->cards->hasPages())
        <div class="pagination-container">
            {{ $userSet->cards->links('vendor.pagination.tailwind') }}
        </div>
    @endif
@endsection
