@extends('layouts.navbar-layout')

@section('title', 'Set Details')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/Sets/show-set.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/navigation.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/error-message.css') }}">
@endsection

@section('content')
    <div class="show-container">
        <div class="card">
            <div class="img-container">
                <img src="{{ $set->images['logo'] }}" alt="Logo de {{ $set->name }}" class="img-fluid">
            </div>
        </div>
        <div class="info-box">
            <h1>{{ $set->name }}</h1>

            <div class="inline-info">
                <p><strong>Fecha de lanzamiento:</strong> {{ $set->releaseDate }}</p>
                <p><strong>Serie:</strong> {{ $set->series }}</p>
                <p><strong>Cartas totales:</strong> {{ $set->printedTotal }}</p>
                <p><strong>Cartas totales incluyendo especiales:</strong> {{ $set->total }}</p>
                <p><strong>Código de coleccion de TCG:</strong> {{ $set->ptcgoCode }}</p>
            </div>

            <h4>Símbolo:</h4>
            <div class="set-symbol-img-container">
                <img src="{{ $set->images['symbol'] }}" alt="Símbolo de {{ $set->name }}" class="set-symbol-img">
            </div>

            <a href="{{ route('sets.index') }}" class="btn btn-primary">Back to list</a>
        </div>
    </div>
@endsection
