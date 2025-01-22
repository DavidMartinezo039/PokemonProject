@extends('layouts.navbar-layout')

@section('title', 'Cards')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/cards.css') }}">
@endsection

@section('content')

    <x-card-list :cards="$cards"/>


@endsection
