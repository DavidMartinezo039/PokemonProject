@extends('layouts.cards-layout')

@section('title', $set->id . ' - Cards')

@section('content')

    <div class="set-container">
        <a href="{{ route('sets.show', $set->id) }}">
            <img src="{{ $set->images['logo'] }}" alt="{{ $set->name }}" class="set-logo">
        </a>
    </div>

@endsection
