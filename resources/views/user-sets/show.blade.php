@extends('layouts.navbar-layout')

@section('title', 'Set Details')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/Sets/show-set.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/User-Sets/user-sets.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/error-message.css') }}">
@endsection

@section('content')
    <div class="show-container">
        <div class="card">
            <div class="img-container">
                <img src="{{ asset('storage/' . $userSet->image) }}" alt="Imagen del set" class="set-logo">
            </div>
        </div>
        <div class="info-box">
            <h1>{{ $userSet->name }}</h1>

            <div class="inline-info">
                <p><strong>Description:</strong> {{ $userSet->description }}</p>
                <p><strong>Cartas totales:</strong> {{ $userSet->card_count }}</p>
                <form action="{{ route('user-sets.destroy', $userSet->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="create-set-button" onclick="return confirm('¿Estás seguro de que deseas eliminar este set?')">
                        <span class="plus-symbol">-</span>
                    </button>
                </form>
                <a href="{{ route('user-sets.edit', $userSet->id) }}" class="create-set-button">
                    <span class="plus-symbol">✎</span>
                </a>
            </div>

            <a href="{{ route('user-sets.index') }}" class="btn btn-primary">Back to list</a>
        </div>
    </div>
@endsection
