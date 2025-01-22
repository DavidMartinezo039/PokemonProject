@extends('layouts.navbar-layout')

@section('title', 'Cards')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/navigation.css') }}">
@endsection

@section('additional-js')
    <script src="{{ asset('View/js/cards.js') }}"></script>
@endsection

@section('content')

    <x-card-list :cards="$cards"/>


@endsection
