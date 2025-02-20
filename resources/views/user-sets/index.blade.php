@extends('layouts.navbar-layout')

@section('title', 'Mis Sets')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/Sets/sets.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/error-message.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/User-Sets/user-sets.css') }}">
@endsection

@section('content')

    <div class="sets-container">
        <div class="create-set-container">
            <a href="{{ route('user-sets.create') }}" class="create-set-button">
                <span class="plus-symbol">+</span>
            </a>
        </div>
        <div class="serie-name">{{__('My Sets')}}</div>

        @livewire('UserSetSearch')
    </div>
@endsection
