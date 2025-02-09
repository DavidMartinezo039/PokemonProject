@extends('layouts.navbar-layout')

@section('title', 'sets')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/Sets/sets.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/error-message.css') }}">
@endsection

@section('content')
    <div class="sets-container">
        @if(isset($message))
            <div class="error-message">
                {{ $message }}
            </div>
        @else
            @foreach ($setsBySeries as $serieName => $sets)
                <div class="serie-group">
                    <div class="serie-name">{{ $serieName }}</div>
                    <div class="sets-grid">
                        @foreach ($sets as $set)
                            <a href="{{ route('sets.cards', $set->id) }}" class="set-card">
                                <div>
                                    <img src="{{ $set->images['logo'] }}" alt="{{ $set->name }}">
                                    <p>{{ $set->name }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
