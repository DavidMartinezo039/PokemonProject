@extends('layouts.cards-layout')

@section('title', $userSet->id . ' - Cards')

@section('additional-css')

    <link rel="stylesheet" href="{{ asset('View/css/User-Sets/cards-user-set.css') }}">

@endsection

@section('content')

    <a href="{{ route('user-sets.index') }}" class="back-button">{{__('Return')}}</a>

    <div class="set-container">
        @if ($userSet->cards->count() > 0)
            <a href="{{ route('user-sets.my-cards', ['userSet' => $userSet]) }}" class="create-set-button">
                <span class="plus-symbol">-</span>
            </a>
        @endif

        <div style="text-align: center">

            <div class="set-button-container">
                @livewire('pdf-button', ['userSet' => $userSet])
            </div>
            <a href="{{ route('user-sets.show', $userSet) }}">
                <img src="{{ asset($userSet->image) }}" alt="Imagen del set" class="set-logo">
                <h2 class="set-name">{{ $userSet->name }}</h2>
            </a>
        </div>

        <div class="create-set-container">
            <<a href="{{ route('user-sets.select-card', ['userSet' => $userSet]) }}" class="create-set-button">
                <span class="plus-symbol">+</span>
            </a>
        </div>
    </div>
@endsection
