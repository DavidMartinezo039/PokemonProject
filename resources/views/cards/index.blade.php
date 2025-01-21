@extends('layouts.navbar-layout') <!-- Extiende la plantilla principal -->

@section('title', 'Cards') <!-- Título de la página -->

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('css/cards.css') }}">
@endsection

@section('content')

    <h1>Lista de Cartas</h1>

    <x-card-list :cards="$cards"/>
@endsection
