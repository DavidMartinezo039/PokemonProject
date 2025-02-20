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
                <p><strong>{{__('Release date')}}:</strong> {{ $set->releaseDate }}</p>
                <p><strong>{{__('Series')}}:</strong> {{ $set->series }}</p>
                <p><strong>{{__('Total Cards')}}:</strong> {{ $set->printedTotal }}</p>
                <p><strong>{{__('Total cards including specials')}}:</strong> {{ $set->total }}</p>
                <p><strong>{{__('TCG Collection Code')}}:</strong> {{ $set->ptcgoCode }}</p>
            </div>

            <h4>{{__('Symbol')}}:</h4>
            <div class="set-symbol-img-container">
                <img src="{{ $set->images['symbol'] }}" alt="Símbolo de {{ $set->name }}" class="set-symbol-img">
            </div>

            <a href="{{ route('sets.index') }}" class="btn btn-primary">{{__('Return')}}</a>
        </div>
    </div>
@endsection
